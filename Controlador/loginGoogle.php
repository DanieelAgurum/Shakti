<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Modelo/configuracionG.php';
require_once __DIR__ . '/../Modelo/Usuarias.php';
require_once __DIR__ . '/../Modelo/confirmarCorreo.php'; // Este modelo solo envía confirmación

session_start();

$client = new Google\Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri("http://localhost/Shakti/Controlador/loginGoogle.php");

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

// 1️⃣ Validar si el correo ya existe
$stmt = $con->prepare("SELECT * FROM usuarias WHERE correo=? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();

if ($usuario) {
    // Usuario ya registrado → login directo
    $_SESSION['id_rol'] = $usuario['id_rol'];
    $_SESSION['id_usuaria'] = $usuario['id']; // ahora sí existe
    $_SESSION['correo'] = $usuario['correo'];
    $_SESSION['nombre'] = $usuario['nombre'];

    header("Location: ../Vista/usuaria/perfil.php");
    exit;
} else {
    // Usuario no registrado → registro provisional sin contraseña
    $rol = 1; // solo usuarias
    $fecha = date("Y-m-d");

    $insert = $con->prepare("INSERT INTO usuarias (nombre, apellidos, nickname, correo, contraseña, fecha_nac, id_rol) 
        VALUES (?, ?, ?, ?, '', ?, ?)");
    $insert->bind_param("sssssi", $nombre, $apellidos, $nickname, $email, $fecha, $rol);

    if ($insert->execute()) {
        $id_nueva = $insert->insert_id;

        // 2️⃣ Enviar correo de confirmación
        $confirmObj = new confirmarCorreo();
        $confirmObj->inicializar($email, getBaseUrl());
        $correoEnviado = $confirmObj->enviarCorreoConfirmacion();

        // 3️⃣ Guardar mensaje en sesión según resultado
        if ($correoEnviado['success']) {
            $_SESSION['alerta'] = "Hemos enviado un correo de confirmación. Revisa tu bandeja y completa el registro.";
        } else {
            $_SESSION['alerta'] = "Error al enviar correo de confirmación: " . $correoEnviado['error'];
        }

        // Redirigir al login
        header("Location: ../Vista/login.php");
        exit;
    } else {
        die("Error al registrar la usuaria: " . $con->error);
    }
}
