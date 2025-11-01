<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/Modelo/institucionesModelo.php';
header('Content-Type: application/json');


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
        case 4:
            $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 6;
            $preg->mostrarTodos($offset, $limit);
            break;
        default:
            echo json_encode(['opcion' => 0, 'mensaje' => 'Opción no válida.']);
            break;
    }
}
