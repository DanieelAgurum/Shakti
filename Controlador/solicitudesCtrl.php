<?php
header("Cache-Control: max-age=3600");

require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Modelo/solicitudesMdl.php';

$soli = new SolicitudesMdl();

if (isset($_GET['solicitudes'])) {
    $soli->obtenerSolicitudes();
    return;
}

if (isset($_GET['usuarios'])) {
    $soli->obtenerUsuarios();
    return;
}

if (isset($_GET['aceptarSolicitud'])) {
    $soli->inicializar($_REQUEST['nickname']);
    $soli->aceptarSolicitud();
    return;
}


if (isset($_GET['agregarAmigo'])) {
    $soli->inicializar($_REQUEST['nickname']);
    $soli->agregarAmigo();
    return;
}