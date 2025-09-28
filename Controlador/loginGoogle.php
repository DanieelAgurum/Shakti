<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Modelo/configuracionG.php';
require_once __DIR__ . '/../Modelo/Usuarias.php';

session_start();

$client = new Google\Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);

// URL de redirección (debe coincidir exactamente con la de Google Console)
$client->setRedirectUri("http://localhost/Shakti/Controlador/loginGoogle.php");

$client->addScope('email');
$client->addScope('profile');

// Si no hay 'code', redirige al login de Google
if (!isset($_GET['code'])) {
    header("Location: " . $client->createAuthUrl());
    exit;
}

// Intercambiar 'code' por token
$token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
if (isset($token['error'])) {
    die("Error al obtener token: " . $token['error']);
}
$client->setAccessToken($token);

// Obtener información del usuario
$google_oauth = new Google\Service\Oauth2($client);
$userInfo = $google_oauth->userinfo->get();
$email = $userInfo->email;
$nombre = $userInfo->name;

// Buscar usuaria en la BD
$u = new Usuarias();
$con = $u->conectarBD();

$stmt = $con->prepare("SELECT * FROM usuarias WHERE correo=? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();

if (!$usuario) {
    // Usuario no registrado → guardar temporal y redirigir
    $_SESSION['correo_temp'] = $email;
    $_SESSION['nombre_temp'] = $nombre;
    $_SESSION['msg'] = "No estás registrado. Por favor, completa tu registro.";

    header("Location: ../Vista/index.php");
    exit;
}

// Usuario registrado → iniciar sesión normal
$_SESSION['id_rol'] = $usuario['id_rol'];
$_SESSION['id_usuario'] = $usuario['id_usuario'];
$_SESSION['correo'] = $email;
$_SESSION['nombre'] = $nombre;

header("Location: ../Vista/index.php");
exit;
