<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/vendor/autoload.php';
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

// Redirige a Google si no hay código
if (!isset($_GET['code'])) {
    header("Location: " . $client->createAuthUrl());
    exit;
}

// Intercambiar code por token
$token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
if (isset($token['error'])) {
    die("Error al obtener token: " . $token['error']);
}
$client->setAccessToken($token);

// Obtener datos del usuario
$google_oauth = new Google\Service\Oauth2($client);
$userInfo = $google_oauth->userinfo->get();

$email = $userInfo->email;
$nombre = $userInfo->givenName;
$apellidos = $userInfo->familyName ?? "";
$nickname = explode("@", $email)[0];

// Obtener o generar la foto
if (!empty($userInfo->picture)) {
    $fotoGoogleBin = @file_get_contents($userInfo->picture);
    if ($fotoGoogleBin === false) {
        $fotoGoogleBin = file_get_contents(__DIR__ . '/../img/undraw_chill-guy-avatar_tqsm.svg');
    }
} else {
    $fotoGoogleBin = file_get_contents(__DIR__ . '/../img/undraw_chill-guy-avatar_tqsm.svg');
}

// Buscar usuaria existente
$stmt = $con->prepare("SELECT * FROM usuarias WHERE correo=? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();

if ($usuario) {
    // ✅ USUARIA EXISTENTE
    $_SESSION['id'] = $usuario['id'];
    $_SESSION['id_usuaria'] = $usuario['id'];
    $_SESSION['id_rol'] = $usuario['id_rol'];
    $_SESSION['correo'] = $usuario['correo'];
    $_SESSION['nombre'] = $usuario['nombre'];
    $_SESSION['apellidos'] = $usuario['apellidos'] ?? $apellidos;
    $_SESSION['nickname'] = $usuario['nickname'] ?: explode("@", $usuario['correo'])[0];

    if (!empty($usuario['foto'])) {
        $_SESSION['foto'] = $usuario['foto'];
    } else {
        $fotoEscaped = mysqli_real_escape_string($con, $fotoGoogleBin);
        $update = $con->prepare("UPDATE usuarias SET foto=? WHERE id=?");
        $update->bind_param("si", $fotoEscaped, $usuario['id']);
        $update->execute();
        $_SESSION['foto'] = $fotoGoogleBin;
    }

} else {
    // ✅ USUARIA NUEVA
    $rol = 1;
    $fecha = date("Y-m-d");

    $fotoEscaped = mysqli_real_escape_string($con, $fotoGoogleBin);
    $insert = $con->prepare("INSERT INTO usuarias (nombre, apellidos, nickname, correo, contraseña, fecha_nac, id_rol, foto)
        VALUES (?, ?, ?, ?, '', ?, ?, ?)");
    $insert->bind_param("sssssis", $nombre, $apellidos, $nickname, $email, $fecha, $rol, $fotoEscaped);

    if ($insert->execute()) {
        $id_nueva = $insert->insert_id;

        $_SESSION['id'] = $id_nueva;            // ✅ ambas variables con el mismo valor
        $_SESSION['id_usuaria'] = $id_nueva;    // ✅
        $_SESSION['id_rol'] = $rol;
        $_SESSION['correo'] = $email;
        $_SESSION['nombre'] = $nombre;
        $_SESSION['apellidos'] = $apellidos;
        $_SESSION['nickname'] = $nickname;
        $_SESSION['foto'] = $fotoGoogleBin;
    } else {
        die("Error al registrar la usuaria: " . $con->error);
    }
}

header("Location: ../Vista/usuaria/perfil");
exit;
