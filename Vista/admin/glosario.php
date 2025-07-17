<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/obtenerLink/obtenerLink.php';
$urlBase = getBaseUrl();

session_start();

if (empty($_SESSION['correo']) || $_SESSION['id_rol'] != 3) {
    header("Location: {$urlBase}");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tipo de Reportes - Shakti</title>
    <script src="js/tiposReportes.js"></script>
</head>

<body class="sb-nav-fixed">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/admin/navbar.php'; ?>

    <div id="layoutSidenav">
        <?php
        include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/admin/lateral.php';
        include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/Vista/admin/modales/tipo_reporte.php';
        ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4 mb-5">
                    <div class="container">
                        <h1 class="page-header text-center"><strong>Tipo de Reportes</strong></h1>
                        <div class="row">
                            <div class="col-sm-12">
                                <button type="button" style="margin-bottom: 8px;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    Nuevo <i class="fa-solid fa-circle-plus"></i>
                                </button>