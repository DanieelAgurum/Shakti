<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/modelo/glosarioMdl.php';

$glo = new GlosarioMdl();

if (isset($_REQUEST['opcion'])) {
    switch ($_REQUEST['opcion']) {
        case 1:
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $icono = $_POST['icono'] ?? '';
                $titulo = $_POST['titulo'] ?? '';
                $descripcion = $_POST['descripcion'] ?? '';

                $glo->inicializar($icono, $titulo, $descripcion);
                echo $glo->agregarGlosario();
            } else {
                echo json_encode([
                    'opcion' => 0,
                    'mensaje' => 'Método no permitido.'
                ]);
            }
            break;

        default:
            echo json_encode(['opcion' => 0, 'mensaje' => 'Opción no válida']);
            break;
    }
} else {
    echo json_encode(['opcion' => 0, 'mensaje' => 'No se especificó ninguna opción']);
}