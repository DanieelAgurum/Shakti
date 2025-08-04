<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Modelo/preguntasFrecuentesMdl.php';

$preg = new preguntasFrecuentesMdl();
$preg->conectarBD();

if (isset($_REQUEST['opcion'])) {
    switch ($_REQUEST['opcion']) {
        case 1:
            $preg->inicializar($_REQUEST['pregunta'], $_REQUEST['respuesta']);
            $resultado = $preg->agregarPreguntaFrecuente();
            echo $resultado;
            break;

        case 2:
            $resultado = $preg->modificarPregunta($_REQUEST['id'], $_REQUEST['pregunta'], $_REQUEST['respuesta']);
            echo $resultado;
            break;
            case 3:
                $preg->eliminarPregunta($_REQUEST['id']);
                break;
        default:
            echo json_encode(['opcion' => 0, 'mensaje' => 'Opción no válida.']);
            break;
    }
} 