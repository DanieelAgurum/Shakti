<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/obtenerLink/obtenerLink.php';
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
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/admin/icono.php' ?>
    <script src="js/tiposReportes.js"></script>
</head>

<body class="sb-nav-fixed">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/admin/navbar.php'; ?>

    <div id="layoutSidenav">
        <?php
        include $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/admin/lateral.php';
        include $_SERVER['DOCUMENT_ROOT'] . '/shakti/Vista/admin/modales/tipo_reporte.php';
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
                                <?php if (isset($_GET['estado'])): ?>
                                    <?php
                                    $mensajes = [
                                        'eliminado' => 'Se eliminó correctamente.',
                                        'agregado' => 'Se agregó correctamente.',
                                        'modificado' => 'Se modificó correctamente.',
                                        'hay_reportes' => 'Hay reportes asociados a este tipo, no se puede eliminar.',
                                    ];

                                    $clases = [
                                        'agregado' => 'success',   // verde
                                        'modificado' => 'primary', // azul
                                        'eliminado' => 'danger',   // rojo
                                        'hay_reportes' => 'warning', // amarillo
                                    ];

                                    $estado = $_GET['estado'];
                                    $mensaje = $mensajes[$estado] ?? 'Acción desconocida.';
                                    $clase = $clases[$estado] ?? 'secondary';
                                    // $boton = '<button type="button" class="btn-sm btn-danger"><i class="fa-solid fa-eraser"></i> Eliminar</button>';

                                    ?>
                                    <div class="alert alert-<?= $clase; ?> alert-dismissible fade show" role="alert">
                                        <?= htmlspecialchars($mensaje); ?>
                                        <?= $_GET['estado'] === 'hay_reportes' ? $boton : ''; ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <table class="table table-bordered table-striped" id="MiAgenda" style="margin-top:20px;">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre del reporte</th>
                                        <th>Tipo de Reporte</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Controlador/tipo_reporteCtrl.php';
                                    $tabla = new TipoReporteMdl();
                                    $tabla->conectarBD();
                                    $tabla->verTipos();
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Script de inicialización de DataTable -->
    <script>
        $(document).ready(function() {
            $('#MiAgenda').DataTable({
                language: {
                    decimal: "",
                    emptyTable: "No hay información",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                    infoEmpty: "Mostrando 0 a 0 de 0 Entradas",
                    infoFiltered: "(Filtrado de _MAX_ total entradas)",
                    thousands: ",",
                    lengthMenu: "Mostrar _MENU_ Entradas",
                    loadingRecords: "Cargando...",
                    processing: "Procesando...",
                    search: "Buscar:",
                    zeroRecords: "Sin resultados encontrados",
                    paginate: {
                        first: "Primero",
                        last: "Último",
                        next: "Siguiente",
                        previous: "Anterior"
                    }
                }
            });
        });
    </script>

    <footer class="py-4 bg-light mt-auto">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center justify-content-between small">
                <div class="text-muted">&copy; TechnoLution 2023</div>
            </div>
        </div>
    </footer>
</body>

</html>