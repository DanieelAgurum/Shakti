<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

if (isset($_GET['agregarAmigo'])) {
    $soli->inicializar($_REQUEST['nickname']);
    $soli->enviarSolicitud();
    return;
}

if (isset($_GET['cancelarSolicitud'])) {
    $soli->cancelarSolicitud($_REQUEST['nickname']);
    return;
}

if (isset($_GET['aceptarSolicitud'])) {
    $soli->aceptarSolicitud($_REQUEST['nickname']);
    return;
}

if (isset($_GET['rechazarAmigo'])) {
    $soli->rechazarSolicitud($_REQUEST['nickname']);
    return;
}


if (isset($_GET['eliminarAmigo'])) {
    $soli->eliminarAmigo($_REQUEST['nickname']);
    return;
}

if (isset($_GET['especialistas'])) {
    $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
    $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
    $buscador = trim($_GET['buscador'] ?? '');
    $soli->obtenerEspecialistas($buscador, $limit, $offset); // <- pasar limit y offset
}
