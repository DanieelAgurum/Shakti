<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/Shakti/Modelo/reportesMdl.php";
$rep = new reportesMdl();
$rep->conectarBD();

if (isset($_REQUEST['opcion'])) {
    switch ($_REQUEST['opcion']) {
        case 1:
            session_start();
            if (isset($_REQUEST['nickname']) && isset($_REQUEST['publicacion']) && isset($_REQUEST['tipoReporte']) && isset($_REQUEST['id_usuaria'])) {
                $nick = $_REQUEST['nickname'];
                $publi = $_REQUEST['publicacion'];
                $tipo = $_REQUEST['tipoReporte'];
                $id_reporto = $_REQUEST['id_usuaria'];
                $rep->inicializar($nick, $publi, $tipo, $id_reporto);
                echo $rep->agregarReporte();
            } else {
                echo json_encode([
                    'opcion' => 1,
                    'mensaje' => 'Seleccione una opción válida.'
                ]);
            }
            break;
        case 2:
            if (isset($_REQUEST['id'])) {
                $rep->eliminarReporte($_REQUEST['id'], $_REQUEST['tipo']);
            };
            break;
    }
}
