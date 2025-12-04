<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/shakti/Modelo/tipo_reporteMdl.php";

$tipoRe = new TipoReporteMdl();
$tipoRe->conectarBD();

if (isset($_REQUEST['opcion'])) {
    $opcion = intval($_REQUEST['opcion']);
    switch ($opcion) {
        case 1:
            if (isset($_REQUEST['nombre']) && isset($_REQUEST['tipo'])) {
                $nombre = trim($_REQUEST['nombre']);
                $tipo = trim($_REQUEST['tipo']);
                $tipoRe->inicializar($nombre, $tipo);
                echo $tipoRe->agregar();
            } else {
                echo json_encode([
                    "opcion" => 0,
                    "mensaje" => "Faltan parámetros: nombre o tipo"
                ]);
            }
            break;
        case 2:
            if (isset($_REQUEST['id_tipo_reporte'], $_REQUEST['nombreModificado'], $_REQUEST['tipoModificado'])) {
                $id = $_REQUEST['id_tipo_reporte'];
                $nombre = trim($_REQUEST['nombreModificado']);
                $tipo = trim($_REQUEST['tipoModificado']);
                echo $tipoRe->modificarDatos($id, $nombre, $tipo);  // <-- ¡Aquí el echo es clave!
            } else {
                echo json_encode([
                    "opcion" => 0,
                    "mensaje" => "Faltan parámetros para modificar"
                ]);
            }
            break;

        case 3:
            $tipoRe->eliminarTipo($_REQUEST['id']);
            break;
    }
}
