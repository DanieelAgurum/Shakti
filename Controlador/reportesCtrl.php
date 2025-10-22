<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/shakti/Modelo/reportesMdl.php";
header('Content-Type: application/json');

$rep = new reportesMdl();
$rep->conectarBD();

if (isset($_REQUEST['opcion'])) {
    switch ($_REQUEST['opcion']) {

        // 🔹 Cargar reportes paginados (10 en 10)
        case 1:
            $offset = $_GET['offset'] ?? 0;
            $limit = 10;
            echo $rep->verReportes($offset, $limit);
            break;

        // 🔹 Registrar un nuevo reporte
        case 2:
            session_start();
            if (
                isset($_REQUEST['nickname']) &&
                isset($_REQUEST['publicacion']) &&
                isset($_REQUEST['tipoReporte']) &&
                isset($_REQUEST['id_usuaria'])
            ) {
                $nick = $_REQUEST['nickname'];
                $publi = $_REQUEST['publicacion'];
                $tipoRep = $_REQUEST['tipo_de_reporte'];
                $tipo = $_REQUEST['tipoReporte'];
                $id_reporto = $_REQUEST['id_usuaria'];
                $rep->inicializar($nick, $publi, $tipo, $id_reporto, $tipoRep);
                echo $rep->agregarReporte();
            } else {
                echo json_encode([
                    'error' => true,
                    'mensaje' => 'Parámetros faltantes.'
                ]);
            }
            break;

        // 🔹 Eliminar reporte
        case 3:
            if (isset($_REQUEST['id']) && isset($_REQUEST['tipo'])) {
                echo $rep->eliminarReporte($_REQUEST['id'], $_REQUEST['tipo']);
            } else {
                echo json_encode(['error' => true, 'mensaje' => 'Faltan datos']);
            }
            break;

        default:
            echo json_encode(['error' => true, 'mensaje' => 'Opción inválida']);
            break;
    }
} else {
    echo json_encode(['error' => true, 'mensaje' => 'No se recibió opción']);
}
