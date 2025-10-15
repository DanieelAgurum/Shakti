<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/obtenerLink/obtenerLink.php';
$urlBase = getBaseUrl();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['correo']) || $_SESSION['id_rol'] != 3) {
    header("Location: {$urlBase}");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Reportes - Shakti</title>
    <script src="js/aliminarReporte.js"></script>
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/admin/icono.php' ?>
</head>

<body class="sb-nav-fixed">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/admin/navbar.php'; ?>

    <div id="layoutSidenav">
        <?php
        include $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/admin/lateral.php';
        include $_SERVER['DOCUMENT_ROOT'] . '/shakti/Vista/admin/modales/reporte.php';
        ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4 mb-5">
                    <div class="container">
                        <h1 class="page-header text-center"><strong>Reportes</strong></h1>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php if (isset($_GET['estado'])): ?>
                                    <?php
                                    $mensajes = [
                                        'eliminadoPosts' => 'Publicación, comentarios y reportes eliminados correctamente.',
                                        'eliminadoContenido' => 'Contenido eliminado correctamente.',
                                        'error' => 'Ocurrió un error al eliminar el Contenido o Post. Por favor, inténtalo de nuevo.',
                                    ];

                                    $clases = [
                                        'eliminadoPosts' => 'danger',
                                        'eliminadoContenido' => 'danger',
                                        'error' => 'warning',
                                    ];

                                    $estado = $_GET['estado'];
                                    ?>
                                    <?php if (isset($mensajes[$estado]) && isset($clases[$estado])): ?>
                                        <div class="alert alert-<?php echo htmlspecialchars($clases[$estado]); ?> alert-dismissible fade show" role="alert">
                                            <?php echo htmlspecialchars($mensajes[$estado]); ?>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>

                            <table class="table table-bordered table-striped" id="MiAgenda" style="margin-top: 20px;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Usuario</th>
                                        <th>Contenido/Posts</th>
                                        <th>Tipo</th>
                                        <th>Reportes</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Controlador/reportesCtrl.php';
                                    $reporte = new reportesMdl();
                                    $reporte->conectarBD();
                                    $reporte->verReportes();
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#MiAgenda').DataTable({
                language: {
                    decimal: "",
                    emptyTable: "No hay información",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                    infoEmpty: "Mostrando 0 a 0 de 0 Entradas",
                    infoFiltered: "(Filtrado de _MAX_ total entradas)",
                    infoPostFix: "",
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
                    },
                },
            });
        });
    </script>

    <footer class="py-4 bg-light mt-auto">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center justify-content-between small">
                <div class="text-muted">Copyright &copy; TechnoLution 2023</div>
            </div>
        </div>
    </footer>
</body>

</html>