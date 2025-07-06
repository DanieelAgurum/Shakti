<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/Shakti/Modelo/tipo_reporteMdl.php";

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
                break;
    }
}
