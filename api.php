<?php

use GuzzleHttp\Client as GuzzleHttpClient;
use LeonardoTeixeira\Pushover\Client;
use LeonardoTeixeira\Pushover\Exceptions\PushoverException;
use LeonardoTeixeira\Pushover\Message;
use LeonardoTeixeira\Pushover\Priority;

include_once 'sumup.common.php';
include 'vendor/autoload.php';

# Add API resources to this file

function getEndpointsfppsumup()
{
  $endpoints = array(
    array(
      'method' => 'GET',
      'endpoint' => 'version',
      'callback' => 'fppSumupVersion'
    ),
    array(
      'method' => 'POST',
      'endpoint' => 'event',
      'callback' => 'fppSumupEvent'
    )
  );
  return $endpoints;
}

// GET /api/plugin/fpp-sumup/version
function fppSumupVersion()
{
  $result = array();
  $result['version'] = 'fpp-sumup v1.0.0';

  return json_encode($result);
}

# POST /api/plugin/fpp-sumup/event
function fppSumupEvent()
{
  $event = json_decode(file_get_contents('php://input'), true);
  header("Content-Type: application/json");

  $amount = ($event['amount'] / 100);
  $currency = $event['currency'];

  // Other Felids can be added to this
  $paymentData = [
    'formatted_amount' => number_format($amount, 2) . ' ' . $currency,
    'amount' => $amount,
    'timestamp' => round(microtime(true) * 1000),
    // 'userUuid' => $payload['userUuid'],
  ];

  // Get currentTransactions
  $currentTransactions = convertAndGetSettingsSumUp('sumup-transactions');
  // Push new transaction
  array_push($currentTransactions, $paymentData);
  // Store transaction to json file
  writeToJsonFileSumUp('transactions', $currentTransactions);
  // Store transation account
  totalTransactionsSumUp($amount);
  // Write transaction to log file
  // customLogsSumUp($payload);

  // Get sumup config
  $config = convertAndGetSettingsSumUp('sumup');
  if ($config['effect_activate'] == 'yes' && $config['command'] != '') {
    // Run default command
    customLogsSumUp('Run default command');
    sumUpRunCommand([
      'command' => $config['command'],
      'args' => $config['args'],
      'formatted_amount' => $paymentData['formatted_amount'],
    ]);
    // Check if pushover is active
    if (isset($config['pushover']) && $config['pushover']['activate'] == 'yes') {
      sumUpPushover($config, $paymentData);
    }
    // Check if publish is active
    if (isset($config['publish']) && $config['publish']['activate'] == 'yes') {
      // sumUpPublishTransactionDetails($payload);
    }
    // Store userUuid and if they have activated publish or not
    // sumUpStoreCustomer($config, $paymentData);
  }
  return json_encode([
    'error' => false,
    'message' => 'All ok'
  ]);
}

/**
 * Make pushover message and send it
 *
 * @param array $config
 * @param array $paymentData
 * @return void
 */
function sumUpPushover($config = [], $paymentData = [])
{
  $build_message = sumUpBuildMessage($paymentData, $config['pushover']['message']);

  $client = new Client($config['pushover']['user_key'], $config['pushover']['app_token']);
  $message = new Message($build_message, 'FPP CARD READER', Priority::HIGH);

  try {
    $client->push($message);
    customLogsSumUp('The pushover message has been pushed!');
  } catch (PushoverException $e) {
    customLogsSumUp('PUSHOVER ERROR: ', $e->getMessage());
  }
}

/**
 * Publish Transaction Details to fpp-zettle.co.uk
 *
 * @param array $payload
 * @return void
 */
function sumUpPublishTransactionDetails($payload = [])
{
  $client = new GuzzleHttpClient([
    "base_uri" => "https://fpp-zettle.co.uk",
    'headers' => [
      'Content-Type' => 'application/json'
    ]
  ]);

  $options = [
    'form_params' => [
      'amount' => $payload['amount'],
      'currency' => $payload['currency'],
    ],
  ];

  $response = $client->post("/api/transactions", $options);
  customLogsSumUp('Publish Transaction Details to fpp-zettle.co.uk');
  // customLogsSumUp($response->getBody());
}

/**
 * Store custom data on fpp-zettle.co.uk
 *
 * @param array $config
 * @param array $data
 * @return void
 */
function sumUpStoreCustomer($config = [], $data = [])
{
  $client = new GuzzleHttpClient([
    "base_uri" => "https://fpp-zettle.co.uk",
    'headers' => [
      'Content-Type' => 'application/json'
    ]
  ]);

  $options = [
    'form_params' => [
      'user_uuid' => $data['userUuid'],
      'active' => isset($config['publish']) && $config['publish']['activate'] == 'yes' ? TRUE : FALSE,
    ],
  ];

  $client->post("/api/customers", $options);
}

/**
 * Build message for Overlay Model Effect
 *
 * @param array $paymentData
 * @param array $data command data
 * @return string
 */
function sumUpBuildMessage($paymentData = [], $data = [], $url_encode = false)
{
  $replacement_values = [
    $paymentData['formatted_amount'],
  ];

  if ($url_encode) {
    $replacement_values = array_map('urlencode', $replacement_values);
  }

  // Find and replace values in array as payment details
  $text = str_replace([
    '{{AMOUNT}}',
  ],  $replacement_values, is_array($data) ? end($data) : $data);

  customLogsSumUp('Build Message Output: ' . $text);
  return $text;
}

/**
 * Run command
 *
 * @param array $data command details
 * @return void
 */
function sumUpRunCommand($data = [])
{
  // Build command url from selected command
  // $url = 'http://localhost/api/command/' . urlencode($data['command']);
  $url = 'http://localhost/api/command/';
  // Get command args
  $command_args = $data['args'];
  // Check if command is "Overlay Model Effect"
  if ($data['command'] == 'Overlay Model Effect') {
    $text = sumUpBuildMessage([
      'formatted_amount' => $data['formatted_amount']
    ], $command_args);
    // Remove and replace last item from array
    array_pop($command_args);
    $command_args[] = $text;
  } else if ($data['command'] == 'URL') {
    customLogsSumUp("Is URL");
    $updated_url = sumUpBuildMessage([
      'formatted_amount' => $data['formatted_amount']
    ], $command_args[0], true);
    $command_args[0] = $updated_url;

    $updated_post_body = sumUpBuildMessage([
      'formatted_amount' => $data['formatted_amount']
    ], $command_args[2]);
    $command_args[2] = $updated_post_body;
  }

  if ($data['command'] != 'Overlay Model Effect') {
    // Write command args back into $data, but only for commands other than Overlay Model Effect,
    // which never used to do it - is this a bug that needs fixing?
    $data['args'] = $command_args;
  }

  customLogsSumUp('Sending command: ' . $data);

  // Fire the command
  $query = json_encode($data);
  $ch    = curl_init();
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HEADER, false);
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
  curl_exec($ch);
  curl_close($ch);
  // Write to log file
  customLogsSumUp('command fired');
}
