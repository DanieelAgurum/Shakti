<?php 
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Modelo/configuracionG.php';
require_once __DIR__ . '/../Modelo/Usuarias.php';

session_start();

$client = new Google\Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri("http://localhost/Shakti/Controlador/loginGoogle.php");

// Scopes básicos
$client->addScope('email');
$client->addScope('profile');

$u = new Usuarias();
$con = $u->conectarBD();

// Si no hay 'code', redirige a login Google
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

// Obtener datos del usuario de Google
$google_oauth = new Google\Service\Oauth2($client);
$userInfo = $google_oauth->userinfo->get();

$email = $userInfo->email;
$nombre = $userInfo->givenName;
$apellidos = $userInfo->familyName ?? "";
$nickname = explode("@", $email)[0]; // nickname por default
$fotoGoogle = "../img/undraw_chill-guy-avatar_tqsm.svg";


// Verificar si el usuario ya existe en la base de datos
$stmt = $con->prepare("SELECT * FROM usuarias WHERE correo=? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();

if ($usuario) {
    // Usuario ya registrado → login directo
    $_SESSION['id_usuaria'] = $usuario['id'];
    $_SESSION['id_rol'] = $usuario['id_rol'];
    $_SESSION['correo'] = $usuario['correo'];
    $_SESSION['nombre'] = $usuario['nombre'];
    $_SESSION['apellidos'] = $usuario['apellidos'] ?? $apellidos;
    $_SESSION['nickname'] = $usuario['nickname'] ?: explode("@", $usuario['correo'])[0];
    
    
    $_SESSION['foto'] = $usuario['foto'] ?? $fotoGoogle;

} else {
    $rol = 1; 
    $fecha = date("Y-m-d");

    $insert = $con->prepare("INSERT INTO usuarias (nombre, apellidos, nickname, correo, contraseña, fecha_nac, id_rol, foto)
        VALUES (?, ?, ?, ?, '', ?, ?, ?)");
    $insert->bind_param("sssssis", $nombre, $apellidos, $nickname, $email, $fecha, $rol, $fotoGoogle);

    if ($insert->execute()) {
        // Guardar sesión automáticamente
        $id_nueva = $insert->insert_id;
        $_SESSION['id_usuaria'] = $id_nueva;
        $_SESSION['id_rol'] = $rol;
        $_SESSION['correo'] = $email;
        $_SESSION['nombre'] = $nombre;
        $_SESSION['apellidos'] = $apellidos;
        $_SESSION['nickname'] = $nickname;
        $_SESSION['foto'] = $fotoGoogle;
    } else {
        die("Error al registrar la usuaria: " . $con->error);
    }
}

// Redirigir al perfil en cualquier caso
header("Location: ../Vista/usuaria/perfil");
exit;
