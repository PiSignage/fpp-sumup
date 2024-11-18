<?php

function emptyConfigSumUp()
{
  return [
    'command' => ''
  ];
}

function convertAndGetSettingsSumUp($filename)
{
  global $settings;

  $cfgFile = $settings['configDirectory'] . "/plugin.fpp-" . $filename . ".json";
  if (file_exists($cfgFile)) {
    $j = file_get_contents($cfgFile);
    $json = json_decode($j, true);
    return $json;
  }
  // Create json for config not found
  if ($filename == 'sumup') {
    $j = json_encode(emptyConfigSumUp());
  }
  // Create json for transactions not found
  if ($filename == 'sumup-transactions') {
    $j = json_encode([]);
  }
  return json_decode($j, true);
}

function writeToJsonFileSumUp($filename, $data)
{
  global $settings;

  $cfgFile = $settings['configDirectory'] . "/plugin.fpp-sumup-" . $filename . ".json";
  $json_data = json_encode($data);
  file_put_contents($cfgFile, $json_data);
}

function readConfigSumUp()
{
  global $settings;

  $url = $settings['configDirectory'] . "/plugin.fpp-sumup.json";
  $config = file_get_contents($url);
  $config = utf8_encode($config);
  return json_decode($config);
}

function customLogsSumUp($message)
{
  global $settings;
  if (is_array($message)) {
    $message = json_encode($message);
  }
  $file = fopen($settings['logDirectory'] . "/fpp-sumup.log", "a");
  fwrite($file, "\n" . date('Y-m-d H:i:s') . " :: " . $message);
  fclose($file);
  return;
}

function totalTransactionsSumUp($amount = 0)
{
  global $settings;

  $filepath = $settings['configDirectory'] . "/plugin.fpp-sumup-transactions-total.txt";
  // Check if exists
  if (!file_exists($filepath)) {
    file_put_contents($filepath, 0);
  }
  // Get current total
  $current = (int) file_get_contents($filepath);
  // Check if amount is set
  if ($amount > 0) {
    // Add amount to current total
    $newTotal = $current + $amount;
    // Write to file
    file_put_contents($filepath, $newTotal);
    return number_format($newTotal, 2);
  } else {
    return number_format($current, 2);
  }
}

function jsonOutputSumUp($array)
{
  return json_encode($array);
}
