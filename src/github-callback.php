<?php

# URL of github api
$authorizeURL = 'https://github.com/login/oauth/authorize';
$tokenURL = 'https://github.com/login/oauth/access_token';
$apiURLBase = 'https://api.github.com/';

session_start();
$configFile = 'config.conf';
$configContent = file_get_contents($configFile);

// Decode the JSON content into an associative array
$config = json_decode($configContent, true);


function apiRequest($url, $post=FALSE, $headers=array()) {
  $ch = curl_init($url);

  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Linux useragent'); //change agent string

  if($post)
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));

  $headers[] = 'Accept: application/json';

  # add access token to header
  if(isset($_SESSION['access_token']))
    $headers[] = 'Authorization: Bearer ' . $_SESSION['access_token'];

  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

  $response = curl_exec($ch);
  return json_decode($response); //decode response
}

if (isset($_SESSION['access_token'])) {
  $user = apiRequest($apiURLBase.'user');
  $_SESSION['id'] = $user->id.'</br>';
  $_SESSION['name'] = $user->login.'</br>';
  $_SESSION['email'] = $user->email.'</br>';

  header('Location: profile.php');

} else {

# fail result if no session token
  echo '<h3>Not logged in</h3>';
  echo '<p><a href="?action=login">Log In</a></p>';
}

if(isset($_GET['code'])) {

  // Exchange the auth code for a token
  $token = apiRequest($tokenURL, array(
    'client_id' => $config['github_client_id'],
    'client_secret' => $config['github_secret_id'],
    'redirect_uri' => $config['github_redirect_uri'],
    'code' => $_GET['code']
  ));
  $_SESSION['access_token'] = $token->access_token;
  $_SESSION['access_code'] = $_GET['code'];

  header('Location: ' . $config['github_redirect_uri']);
}