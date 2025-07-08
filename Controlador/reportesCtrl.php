<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/Shakti/Modelo/reportesMdl.php";
$rep = new reportesMdl();

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
            }
            break;
    }
}
