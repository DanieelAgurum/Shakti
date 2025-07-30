<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/modelo/buscadorForoMdl.php';

$buscar = new buscadorForoMdl();
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;

if (isset($_GET['opcion']) || (isset($_REQUEST['buscador']) && trim($_REQUEST['buscador']) !== '')) {
    $opcion = isset($_GET['opcion']) ? intval($_GET['opcion']) : 1;

    switch ($opcion) {
        case 1:
            if (isset($_REQUEST['buscador']) && trim($_REQUEST['buscador']) !== '') {
                $buscar->inicializar(trim($_REQUEST['buscador']));
                $buscar->buscardor($limit, $offset);
            } else {
                $buscar->todos($limit, $offset);
            }
            break;

        default:
            $buscar->todos($limit, $offset);
            break;
    }
} else {
    $buscar->todos($limit, $offset);
}
