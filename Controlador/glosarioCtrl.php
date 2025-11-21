<?php
header('Content-Type: application/json');

require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Modelo/glosarioMdl.php';

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
        case 2:
            echo $glo->modificarGlosario(
                $_REQUEST['id_glosario'],
                $_REQUEST['iconoModificado'],
                $_REQUEST['tituloModificado'],
                $_REQUEST['descripcionModificado']
            );

            break;
        case 3:
            $glo->eliminarGlosario($_REQUEST['id_glosario']);
            break;
        case 4:
            $glo->mostrarGlosario();
            break;
        default:
            echo json_encode(['opcion' => 0, 'mensaje' => 'Opción no válida']);
            break;
    }
} else {
    echo json_encode(['opcion' => 0, 'mensaje' => 'No se especificó ninguna opción']);
}
