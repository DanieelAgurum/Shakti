<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/Modelo/contenidoMdl.php';

$contenido = new contenidoMdl();
$contenido->conectarBD();

switch ($_REQUEST['opcion']) {
    case 1: 
        $titulo = $_REQUEST['titulo'] ?? '';
        $descripcion = $_REQUEST['descripcion'] ?? '';
        $url = $_REQUEST['url'] ?? '';
        $imagen = !empty($_FILES['imagen']['tmp_name']) ? file_get_contents($_FILES['imagen']['tmp_name']) : null;
        $contenido->inicializar($titulo, $descripcion, $url, $imagen, '');
        
        $resultado = $contenido->agregarContenido();
        echo $resultado;
        break;

    default:
        echo json_encode(['opcion' => 0, 'mensaje' => 'Opción no válida']);
        break;
}
