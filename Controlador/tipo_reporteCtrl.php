<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/Shakti/Modelo/tipo_reporteMdl.php";
$tipoRe = new TipoReporteMdl();
$tipoRe->conectarBD();
if (isset($_REQUEST['opcion'])) {
} else {
    $tipoRe->verTipos();
}
