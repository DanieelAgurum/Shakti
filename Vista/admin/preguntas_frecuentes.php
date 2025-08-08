<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/obtenerLink/obtenerLink.php';
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preguntas Frecuentes - Shakti</title>
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/admin/icono.php' ?>
    <script src="js/preguntasFrec.js"></script>
</head>

<body class="sb-nav-fixed">
    <?php
    include  $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/admin/navbar.php';
    ?>
    <div id="layoutSidenav">
        <?php
        include  $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/admin/lateral.php';
        include $_SERVER['DOCUMENT_ROOT'] . '/shakti/Vista/admin/modales/preguntas_frecuentes.php';
        ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4 mb-5">
                    <h1 class="mt-4"></h1>
                    <div class="container">
                        <h1 class="page-header text-center"> <strong>Preguntas Frecuentes</strong></h1>
                        <div class="row">
                            <div class="col-sm-12">
                                <button type="button" style="margin-bottom: 8px;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    Nuevo <i class="fa-solid fa-circle-plus"></i>
                                </button>
                                <?php if (isset($_GET['estado'])): ?>
                                    <?php
                                    $mensajes = [
                                        'agregado' => 'Se agregó la pregunta frecuente.',
                                        'modificado' => 'Se modifico la pregunta frecuente.',
                                        'eliminado' => 'Se elimino la pregunta frecuente.',
                                        'error' => 'Intentelo más tarde.',
                                    ];

                                    $clases = [
                                        'agregado' => 'success',
                                        'modificado' => 'primary',
                                        'eliminado' => 'danger',
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
                                        require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Controlador/preguntasFrecuentesCtrl.php';
                                        $tabla = new preguntasFrecuentesMdl();
                                        $tabla->conectarBD();
                                        $tabla->verTodos();
                                        ?>
                                    </tbody>
                            </div>
                        </div>
                    </div>
            </main>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#MiAgenda').DataTable();
        });
    </script>
    <script>
        var table = $('#MiAgenda').DataTable({
            language: {
                "decimal": "",
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                "infoEmpty": "Mostrando 0 a 0 de 0 Entradas",
                "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ Entradas",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "Ultimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
        });
    </script>
</body>

</html>