<?php

function emptyConfig()
{
  return [
    'command' => ''
  ];
}

function convertAndGetSettings($filename)
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
    $j = json_encode(emptyConfig());
  }
  return json_decode($j, true);
}
