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

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fuggles&family=Lato&family=Mooli&display=swap">
    <link rel="stylesheet" href="../../components/admin/bootstrap.min.css">
    <link rel="stylesheet" href="../../components/admin/datatables.min.css">
    <link rel="stylesheet" href="../../components/admin/styles.css">
    <link rel="stylesheet" href="../../components/admin/custom.css">

    <!-- JS -->
    <script src="https://kit.fontawesome.com/3c934cb418.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>

    <script src="../../components/admin/js/bootstrap.min.js"></script>
    <script src="../../components/admin/js/datatables.min.js"></script>
    <script src="../../components/admin/js/scripts.js"></script>
    <script src="../../components/admin/js/chart-area-demo.js"></script>
    <script src="../../components/admin/js/chart-bar-demo.js"></script>
    <script src="../../components/admin/js/datatables-simple-demo.js"></script>
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
                <div style="margin-top: -100px">
                    <div class="container">
                        <h1 class="page-header text-center"><strong>Tipo de Reportes</strong></h1>
                        <div class="row">
                            <div class="col-sm-12">
                                <button type="button" style="margin-bottom: 8px;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    Nuevo <i class="fa-solid fa-circle-plus"></i>
                                </button>

                                <?php if (isset($_GET['status'])): ?>
                                    <?php
                                    $mensajes = [
                                        'eliminada' => 'La cuenta fue eliminada correctamente.',
                                        'estatus_actualizado' => 'El estado de la cuenta fue actualizado correctamente.',
                                        'error_activar' => 'Error al activar la cuenta.',
                                        'error_eliminar' => 'Error al eliminar la cuenta.',
                                        'error_estatus' => 'Error al cambiar el estado de la cuenta.',
                                    ];
                                    $clase = in_array($_GET['status'], ['eliminada', 'estatus_actualizado']) ? 'success' : 'danger';
                                    $mensaje = $mensajes[$_GET['status']] ?? 'Acción desconocida.';
                                    ?>
                                    <div class="alert alert-<?= $clase; ?> alert-dismissible fade show" role="alert">
                                        <?= htmlspecialchars($mensaje); ?>
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
                                    require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/Controlador/tipo_reporteCtrl.php';
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
        $(document).ready(function () {
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
