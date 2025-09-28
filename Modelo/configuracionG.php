<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/vendor/autoload.php';


$clientID     = "149987150313-s4k1pr3ggbi12phgsfspsbf6mnukocc8.apps.googleusercontent.com";
$clientSecret = "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";
$redirectUri  = "http://localhost/Shakti/Vista/login.php";
$redirectUri  = "http://localhost/Shakti/Vista/loginGoogle.php";  

$client = new Google_Client();
$client->setClientId($clientID);        
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

$loginUrl = $client->createAuthUrl();
