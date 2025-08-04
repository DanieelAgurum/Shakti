<?php

include($_SERVER['DOCUMENT_ROOT'] . "/shakti/Modelo/loginMdl.php");

$u = new loginMdln();
$u -> conectarBD();

switch($_REQUEST['opcion']){
    case 1:
        $u->inicializar($_REQUEST['correo'], $_REQUEST['contraseña']);
        $u->iniciarsesion();
        break;
    case 2:
        $u->cerrarSesion();
        break;
    }

?>