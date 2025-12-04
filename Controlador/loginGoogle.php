<?php
date_default_timezone_set('America/Mexico_City');
require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Modelo/configuracionG.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Modelo/conexion.php';
session_start();

// Conectar BD
$db = new ConectarDB();
$con = $db->open();
if (!$con) die("Error al conectar a la base de datos.");

// Inicializar Google Client
$client = new Google\Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri("https://shaktiapp.site/Controlador/loginGoogle.php");
//$client->setRedirectUri("http://localhost/Shakti/Controlador/loginGoogle.php");
$client->addScope('email');
$client->addScope('profile');

// Si no hay código → redirigir a Google
if (!isset($_GET['code'])) {
    header("Location: " . $client->createAuthUrl());
    exit;
}

// Intercambiar CODE por TOKEN
$token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
if (isset($token['error'])) die("Error token: " . $token['error']);

$client->setAccessToken($token);

// Servicio OAuth2 correcto
$googleService = new Google\Service\Oauth2($client);
$userInfo = $googleService->userinfo->get();

// Datos del usuario
$email = $userInfo->email;
$nombre = $userInfo->givenName;
$apellidos = $userInfo->familyName ?? "";
$nickname = explode("@", $email)[0];

// Foto predeterminada del sistema
$fotoPredeterminada = file_get_contents($ $urlBase . 'img/undraw_chill-guy-avatar_tqsm.svg');

// Función para descargar foto de Google
function obtenerFoto($url) {
    if (!empty($url)) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $result = curl_exec($ch);
        curl_close($ch);
        if ($result) return $result;
    }
    return null;
}

// Descargar foto de Google (solo si se usará)
$fotoGoogleBin = obtenerFoto($userInfo->picture ?? '');

// -------------------- VALIDAR SI YA EXISTE --------------------
$stmt = $con->prepare("SELECT * FROM usuarias WHERE correo = :correo LIMIT 1");
$stmt->execute([':correo' => $email]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if ($usuario) {

    // Caso: registrada manual → bloquear
    if (!empty($usuario['contraseña'])) {
        header("Location: ../Vista/login?status=error&message=" . urlencode("Este correo ya está en uso. Usa otro o inicia sesión con contraseña."));
        exit;
    }

    // Iniciar sesión (datos base)
    $_SESSION['id'] = $usuario['id'];
    $_SESSION['id_usuaria'] = $usuario['id'];
    $_SESSION['id_rol'] = $usuario['id_rol'];
    $_SESSION['correo'] = $usuario['correo'];
    $_SESSION['nombre'] = $usuario['nombre'];
    $_SESSION['apellidos'] = $usuario['apellidos'] ?? $apellidos;
    $_SESSION['nickname'] = $usuario['nickname'] ?: explode("@", $usuario['correo'])[0];

    /**
     * CONTROL CORRECTO DE FOTO:
     * - Si está NULL → el usuario la borró → usar predeterminada
     * - Si tiene foto → usar la guardada
     */
    if (is_null($usuario['foto'])) {
        $_SESSION['foto'] = $fotoPredeterminada;
    } else {
        $_SESSION['foto'] = $usuario['foto'];
    }

} else {

    // Registrar nueva usuaria
    $rol = 1;
    $fecha = date("Y-m-d");

    // Si Google no dio foto, usar predeterminada
    $fotoFinal = $fotoGoogleBin ?: $fotoPredeterminada;

    $insert = $con->prepare("
        INSERT INTO usuarias (nombre, apellidos, nickname, correo, contraseña, fecha_nac, id_rol, foto)
        VALUES (:nombre, :apellidos, :nickname, :correo, '', :fecha, :rol, :foto)
    ");

    $insert->bindValue(':nombre', $nombre);
    $insert->bindValue(':apellidos', $apellidos);
    $insert->bindValue(':nickname', $nickname);
    $insert->bindValue(':correo', $email);
    $insert->bindValue(':fecha', $fecha);
    $insert->bindValue(':rol', $rol);
    $insert->bindValue(':foto', $fotoFinal, PDO::PARAM_LOB);
    $insert->execute();

    $idNueva = $con->lastInsertId();

    $_SESSION['id'] = $idNueva;
    $_SESSION['id_usuaria'] = $idNueva;
    $_SESSION['id_rol'] = $rol;
    $_SESSION['correo'] = $email;
    $_SESSION['nombre'] = $nombre;
    $_SESSION['apellidos'] = $apellidos;
    $_SESSION['nickname'] = $nickname;
    $_SESSION['foto'] = $fotoFinal;
}

header("Location: ../Vista/usuaria/perfil");
exit;
