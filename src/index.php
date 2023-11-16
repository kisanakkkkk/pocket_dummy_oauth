<?php

//initialize facebook sdk
//https://www.cloudways.com/blog/add-facebook-login-in-php/

require 'vendor/autoload.php';
session_start();




if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fb_oauth_click'])) {
    fb_oauth_process();
}elseif($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['github_oauth_click'])){
	github_oauth_process();
}

function fb_oauth_process(){
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

	$loginUrl = $helper->getLoginUrl($config['fb_redirect_uri'], $permissions);
	header("Location: $loginUrl");
	
}

function github_oauth_process(){
	$configFile = 'config.conf';
	$configContent = file_get_contents($configFile);

	// Decode the JSON content into an associative array
	$config = json_decode($configContent, true);

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
            Login with GitHub
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

    <!-- Add forms for other providers as needed -->

</body>
</html>

<?php
}
else{
	echo "minimal logout dulu lah";
}
?>

