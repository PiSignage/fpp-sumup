<?php
include 'vendor/autoload.php';
include_once "/opt/fpp/www/common.php";
include_once 'sumup.common.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$a = session_id();
if (empty($a)) {
  session_start();
}
$_SESSION['session_id'] = session_id();

$command_array = array(
  'clear_config' => 'ClearConfig',
  'save_pushover' => 'SavePushover',
  'save_publish' => 'SavePublish',
  'update_json' => 'UpdateJson',
  'clear_transactions' => 'ClearTransactions'
);

$command = "";
$args = array();

if (isset($_GET['command']) && !empty($_GET['command'])) {
  $command = $_GET['command'];
  $args = $_GET;
} elseif (isset($_POST['command']) && !empty($_POST['command'])) {
  $command = $_POST['command'];
  $args = $_POST;
}

if (array_key_exists($command, $command_array)) {
  global $debug;

  if ($debug) {
    error_log("Calling " . $command);
  }

  call_user_func($command_array[$command]);
}
return;

function ClearConfig()
{
  setPluginJSON('fpp-sumup', emptyConfigSumUp());
  setPluginJSON('fpp-sumup-transactions', []);

  echo json_encode([
    'error' => false
  ]);
}

function setPluginJSON($plugin, $js)
{
  global $settings;

  $cfgFile = $settings['configDirectory'] . "/plugin." . $plugin . ".json";
  file_put_contents($cfgFile, json_encode($js, JSON_PRETTY_PRINT));
  // echo json_encode($js, JSON_PRETTY_PRINT);
}

function SavePushOver()
{
  UpdateJson2('pushover', [
    'activate' => $_POST['activate'],
    'app_token' => $_POST['app_token'],
    'user_key' => $_POST['user_key'],
    'message' => $_POST['message'],
  ]);

  echo jsonOutputSumUp([
    'error' => false,
    'message' => 'Pushover Updated!'
  ]);
}

function SavePublish()
{
  UpdateJson2('publish', [
    'activate' => $_POST['activate'],
    //'location' => $_POST['location'],
  ]);

  echo jsonOutputSumUp([
    'error' => false,
    'message' => 'Publish Updated!'
  ]);
}

function UpdateJson()
{
  $pluginJson = convertAndGetSettingsSumUp('sumup');

  switch ($_POST['option']) {
    case 'setup':
      unset($_POST['option']);
      $pluginJson = array_merge($pluginJson, $_POST);
      break;

    case 'effect':
      unset($_POST['option']);

      $pluginJson = array_merge($pluginJson, $_POST);
      if ($pluginJson['multisyncCommand'] == "false") {
        $pluginJson['multisyncCommand'] = false;
      } else {
        $pluginJson['multisyncCommand'] = true;
      }
      break;
  }

  setPluginJSON('fpp-sumup', $pluginJson);

  echo json_encode([
    'error' => false
  ]);
}

function UpdateJson2($option, $data)
{
  $pluginJson = convertAndGetSettingsSumUp('sumup');

  switch ($option) {
    case 'pushover':
      $pluginJson['pushover']['activate'] = $data['activate'];
      $pluginJson['pushover']['app_token'] = $data['app_token'];
      $pluginJson['pushover']['user_key'] = $data['user_key'];
      $pluginJson['pushover']['message'] = $data['message'];
      break;

    case 'publish':
      $pluginJson['publish']['activate'] = $data['activate'];
      //$pluginJson['publish']['location'] = $data['location'];
  }

  setPluginJSON('fpp-sumup', $pluginJson);

  return true;
}

function ClearTransactions()
{
  setPluginJSON('fpp-zettle-transactions', []);
  echo json_encode([
    'error' => false
  ]);
}
