<?php

# Add API resources to this file

function getEndpointsfppsumup()
{
  $endpoints = array(
    array(
      'method' => 'POST',
      'endpoint' => 'event',
      'callback' => 'fppSumupEvent'
    )
  );
  return $endpoints;
}

# POST /api/plugin/fpp-sumup/event
function fppSumupEvent()
{
  // Get sumup config
  $config = convertAndGetSettings('sumup');
  // Check an command has set
  if ($config['command'] != '') {
    // Build command url from selected command on setup page
    $url = 'http://localhost/api/command/'.urlencode($config['command']);
    // Get command args
    $data = $config['args'];
    // Check if command is "Overlay Model Effect"
    if ($config['command'] == 'Overlay Model Effect') {
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
      // custom_logs('command fired');
    }
  }
  return true;
}
