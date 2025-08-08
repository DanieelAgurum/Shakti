<?php
require_once $_SERVER['DOCUMENT_ROOT'] . 'shakti//Modelo/organizacionesModelo.php';

$preg = new organizacionesModelo();
$preg->conectarBD();

if (isset($_REQUEST['opcion'])) {
    switch ($_REQUEST['opcion']) {
        case 1:
            $preg->inicializar($_REQUEST['nombre'], $_REQUEST['descripcion'], $_REQUEST['numero'], $_FILES['imagen']);
            $resultado = $preg->agregarOrganizacion();
            echo $resultado;
            break;
        case 2:
            $resultado = $preg->modificarOrganizacion($_REQUEST['id'], $_REQUEST['nombre'], $_REQUEST['descripcion'], $_REQUEST['numero']);
            echo $resultado;
            break;
        case 3:
            $preg->eliminarOrganizacion($_REQUEST['id']);
            break;
        default:
            echo json_encode(['opcion' => 0, 'mensaje' => 'Opción no válida.']);
            break;
    }
}
