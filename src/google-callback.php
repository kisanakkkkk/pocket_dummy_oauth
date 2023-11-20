<?php

session_start();
require '../vendor/autoload.php';

$configFile = 'config.conf';
$configContent = file_get_contents($configFile);

// Decode the JSON content into an associative array
$config = json_decode($configContent, true);

$client_id = $config['google_client_id'];
$client_secret = $config['google_client_secret'];
$redirect_uri = $config['google_redirect_uri'];
$simple_api_key = $config['google_api_key'];

$client = new Google_Client();
$client->setApplicationName("PHP Google OAuth Login Example");
$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setRedirectUri($redirect_uri);
$client->addScope("https://www.googleapis.com/auth/userinfo.email");

if (isset($_GET['code'])) {
    $client->authenticate($_GET['code']);
  	$_SESSION['access_code'] = $_GET['code']; 
    $_SESSION['access_token'] = $client->getAccessToken();
    header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}

if (isset($_SESSION['access_token'])) {
    $client->setAccessToken($_SESSION['access_token']);
    $oauth2 = new Google_Service_Oauth2($client);
    $_SESSION['access_token'] = $_SESSION['access_token']['access_token'];
	$userInfo = $oauth2->userinfo->get();

    $_SESSION['id'] = $userInfo->id.'</br>';
	$_SESSION['name'] = $userInfo->familyName.'</br>';
	$_SESSION['email'] = $userInfo->email.'</br>';
  	header('Location: profile.php');
}
