<?php

include($_SERVER['DOCUMENT_ROOT'] . "/shakti/Modelo/loginMdl.php");

$login = new loginMdln();

switch ($_POST['opcion'] ?? '') {
    case 1:
        $correo = $_POST['correo'] ?? '';
        $contraseña = $_POST['contraseña'] ?? '';

        $login->inicializar($correo, $contraseña);
        $login->iniciarSesion();
        break;

    case 2:
        $login->cerrarSesion();
        break;

    default:
        header("Location: ../Vista/login");
        exit;
}
?>
