<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Modelo/solicitudesMdl.php';

$soli = new SolicitudesMdl();


if (isset($_GET['especialistas'])) {
    $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
    $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
    $buscador = trim($_GET['buscador'] ?? '');
    $soli->obtenerEspecialistas($buscador, $limit, $offset);
}
