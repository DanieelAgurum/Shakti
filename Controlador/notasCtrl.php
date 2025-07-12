<?php
session_start();
date_default_timezone_set('America/Mexico_City');
include("../modelo/notasModelo.php");
$n = new Notas();
$n->conectarBD();

switch ($_REQUEST['opcion']) {
    case 1:
        $n->inicializar(
            $_POST['titulo'],
            $_POST['nota'],
            $_SESSION['id_usuaria']
        );
        $n->insertarNota();
        break;
    case 2:
        $n->actualizarNota(
            $_REQUEST['id'],
            $_REQUEST['titulo'],
            $_REQUEST['nota'],
        );
        break;
    case 3:
        $n->eliminarNota(
            $_REQUEST['id']
        );
        break;
}
