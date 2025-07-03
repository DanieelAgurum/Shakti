<?php
include_once '../Modelo/cambiarContraCorreo.php';
include_once '../obtenerLink/obtenerLink.php';

$token = new cambiarContraCorreo();
$urlBase = getBaseUrl();

if (isset($_GET['opcion'])) {
    $opcion = $_GET['opcion'];

    switch ($opcion) {
        case 1:
            if (isset($_REQUEST['correo'])) {
                $token->inicializar($_REQUEST['correo'], $urlBase);
                $token->enviarToken();
            } else {
                header("Location: {$urlBase}/index.php");
                exit;
            }
            break;
        case 2:
            $token->obtenerRuta($urlBase);
            $token->cambiarContra($_REQUEST['token'], $_REQUESt['contraseña']);
            break;
        default:
            header("Location: {$urlBase}/index.php");
            exit;
    }
}
