<?php
include($_SERVER['DOCUMENT_ROOT'] . "/shakti/Modelo/loginMdl.php");

$u = new loginMdln();

switch($_POST['opcion'] ?? ''){
    case 1:
        $u->inicializar($_POST['correo'], $_POST['contraseña']);
        $u->iniciarSesion(); // ojo: inicia sesión y redirige
        break;
    case 2:
        $u->cerrarSesion();
        break;
    default:
        header("Location: ../Vista/login.php");
        exit;
}
?>
