<?php

//initialize facebook sdk
//https://www.cloudways.com/blog/add-facebook-login-in-php/

require '../vendor/autoload.php';
session_start();


$configFile = 'config.conf';
$configContent = file_get_contents($configFile);

$config = json_decode($configContent, true);
// Decode the JSON content into an associative array
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
	if (isset($_POST['fb_oauth_click'])) {
	    fb_oauth_process();
	}elseif(isset($_POST['github_oauth_click'])){
		github_oauth_process();
	}
	elseif(isset($_POST['google_oauth_click'])){
		google_oauth_process();
	}
}

function fb_oauth_process(){
	global $config;

	$fb = new Facebook\Facebook([
	 'app_id' => $config['fb_app_id'],
	 'app_secret' => $config['fb_app_secret'],
	 'default_graph_version' => 'v2.5',
	]);

	$helper = $fb->getRedirectLoginHelper();
	$permissions = ['email']; // optional

	$loginUrl = $helper->getLoginUrl($config['fb_redirect_uri'], $permissions);
	header("Location: $loginUrl");
	
}

function github_oauth_process(){
	global $config;

	# URL of github api
	$authorizeURL = 'https://github.com/login/oauth/authorize';
	$tokenURL = 'https://github.com/login/oauth/access_token';
	$apiURLBase = 'https://api.github.com/';



	$github = array(
		'client_id' => $config['github_client_id'],
		'redirect_uri' => $config['github_redirect_uri'],
		'scope' => 'user'
	);
	header('Location: ' . $authorizeURL . '?' . http_build_query($github));

}


function google_oauth_process(){
	global $config;

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
	
	// Send Client Request
	$authUrl = $client->createAuthUrl();

	header("Location: $authUrl");

}

if (!isset($_SESSION['logged_in'])) {
	?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SSO Login</title>
</head>
<body>
    <h1>SSO Login</h1>

    <!-- GitHub Login Form -->
    <form method="post">
        <input type="hidden" name="github_oauth_click" value="github_oauth_click">
        <button type="submit" style="background-color: #24292e; color: #ffffff; padding: 10px; border: none; cursor: pointer;">
            Login with Github
        </button>
    </form>

    <br><br>

    <!-- Facebook Login Form -->
    <form method="post">
        <input type="hidden" name="fb_oauth_click" value="fb_oauth_click">
        <button type="submit" style="background-color: #1877f2; color: #ffffff; padding: 10px; border: none; cursor: pointer;" value="fb_oauth_click">
            Login with Facebook
        </button>
    </form>

    <br><br>

    <!-- Google Login Form -->
    <form method="post">
        <input type="hidden" name="google_oauth_click" value="google_oauth_click">
        <button type="submit" style="background-color: #4285f4; color: #ffffff; padding: 10px; border: none; cursor: pointer;">
            Login with Google
        </button>
    </form>

</body>
</html>

<?php
}
else{
	echo "minimal logout dulu lah";
}
?>

