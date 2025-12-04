<?php
include_once '../Modelo/cambiarContraCorreo.php';
include_once '../obtenerLink/obtenerLink.php';

$token = new cambiarContraCorreo();
$urlBase = getBaseUrl();

$opcion = isset($_GET['opcion']) ? intval($_GET['opcion']) : 0;

switch ($opcion) {
    case 1:
        if (isset($_REQUEST['correo']) && filter_var($_REQUEST['correo'], FILTER_VALIDATE_EMAIL)) {
            $correo = $_REQUEST['correo'];
            $token->inicializar($correo, $urlBase);
            $token->enviarToken();
        } else {
            header("Location: {$urlBase}/index.php?error=correo_invalido");
            exit;
        }
        break;

    case 2:
        if (isset($_REQUEST['token'], $_REQUEST['contraseña'])) {
            $tokenVal = filter_var($_REQUEST['token'], FILTER_SANITIZE_STRING);
            $pass = filter_var($_REQUEST['contraseña'], FILTER_SANITIZE_STRING);
            $token->obtenerRuta($urlBase);
            $token->cambiarContra($tokenVal, $pass);
        } else {
            header("Location: {$urlBase}/Vista/login.php?status=error&message=" . urlencode("Faltan datos"));
            exit;
        }
        break;

    default:
        header("Location: {$urlBase}/index.php");
        exit;
}
