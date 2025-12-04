<?php
$clientID     = "149987150313-s4k1pr3ggbi12phgsfspsbf6mnukocc8.apps.googleusercontent.com";
$clientSecret = "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";

$host = $_SERVER['HTTP_HOST'];
if (strpos($host, 'localhost') !== false) {
    $redirectUri = "http://localhost/Shakti/Vista/loginGoogle.php"; 
} else {
    $redirectUri = "https://shaktiapp.site/Vista/loginGoogle.php"; 
}

$googleClient = new Google_Client();
$googleClient->setClientId($clientID);
$googleClient->setClientSecret($clientSecret);
$googleClient->setRedirectUri($redirectUri);
$googleClient->addScope("email");
$googleClient->addScope("profile");

$loginUrl = $googleClient->createAuthUrl();
