<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/modelo/buscadorForoMdl.php';

$buscar = new buscadorForoMdl();
$buscar->conectarBD();

if (isset($_GET['opcion']) || (isset($_REQUEST['buscador']) && trim($_REQUEST['buscador']) !== '')) {
    switch ($_GET['opcion'] ?? 1) {
        case 1:
            if (isset($_REQUEST['buscador']) && trim($_REQUEST['buscador']) !== '') {
                $buscar->inicializar($_REQUEST['buscador']);
                $buscar->buscar();
            } else {
                $buscar->todos();
            }
            break;
        default:
            $buscar->todos();
            break;
    }
} else {
    $buscar->todos();
}
