<?php

//initialize facebook sdk
//https://www.cloudways.com/blog/add-facebook-login-in-php/

require 'vendor/autoload.php';
session_start();

$configFile = 'config.conf';
$configContent = file_get_contents($configFile);

// Decode the JSON content into an associative array
$config = json_decode($configContent, true);

$fb = new Facebook\Facebook([
 'app_id' => $config['fb_app_id'],
 'app_secret' => $config['fb_app_secret'],
 'default_graph_version' => 'v2.5',
]);

$helper = $fb->getRedirectLoginHelper();
$permissions = ['email']; // optional

	try {
		if (isset($_SESSION['facebook_access_token'])) {
			$accessToken = $_SESSION['facebook_access_token'];
		} else {
	  		$accessToken = $helper->getAccessToken();
		}
	} catch(Facebook\Exceptions\facebookResponseException $e) {
		echo 'Graph returned an error: ' . $e->getMessage();
	  exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {


		echo 'Facebook SDK returned an error: ' . $e->getMessage();
		exit;
	 }
	if (isset($accessToken)) {
		$_SESSION['logged_in'] = true;
		if (isset($_SESSION['facebook_access_token'])) {
			$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
		} else {
			$_SESSION['facebook_access_token'] = (string) $accessToken;
			$oAuth2Client = $fb->getOAuth2Client();
			$longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
			$_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;
			$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
		}
		if (isset($_GET['code'])) {
			$_SESSION['access_token'] =$_GET['facebook_access_token'];
			$_SESSION['access_code'] =$_GET['code'];
			header('Location: profile.php');

		}

		try {
			$profile_request = $fb->get('/me?fields=name,first_name,last_name,email');
			$requestPicture = $fb->get('/me/picture?redirect=false&height=200'); //getting user picture

			$picture = $requestPicture->getGraphUser();

			$profile = $profile_request->getGraphUser();

			$fbid = $profile->getProperty('id');           // To Get Facebook ID

			$fbfullname = $profile->getProperty('name');   // To Get Facebook full name

			$fbemail = $profile->getProperty('email');    //  To Get Facebook email

	# save the user nformation in session variable
			$_SESSION['id'] = $fbid.'</br>';
			$_SESSION['name'] = $fbfullname.'</br>';

			$_SESSION['email'] = $fbemail.'</br>';

		} catch(Facebook\Exceptions\FacebookResponseException $e) {
			echo 'Graph returned an error: ' . $e->getMessage();

			session_destroy();

			header("Location: ./");

			exit;

	} catch(Facebook\Exceptions\FacebookSDKException $e) {

	// When validation fails or other local issues
	echo 'Facebook SDK returned an error: ' . $e->getMessage();
	exit;
	}
}