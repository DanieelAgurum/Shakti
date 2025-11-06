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
    <title>Documentos legales - NexoH</title>
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/admin/icono.php' ?>
</head>

<body class="sb-nav-fixed">
    <?php
    include  $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/admin/navbar.php';
    ?>
    <div id="layoutSidenav">
        <?php
        include  $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/admin/lateral.php';
        ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4 mb-5">
                    <h1 class="mt-4"></h1>
                    <div class="container">
                        <h1 class="page-header text-center"> <strong> Documentos legales </strong></h1>
                        <div class="row">
                            <div class="col-sm-12">
                                <button type="button" style="margin-bottom: 8px;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#agregarLegal">
                                    Nuevo <i class="fa-solid fa-circle-plus"></i>
                                </button>
                                <?php if (isset($_GET['status'])): ?>
                                    <?php
                                    $mensajes = [
                                        'eliminada' => 'El contenido fue eliminado correctamente.',
                                        'agregado' => 'Se agregó correctamente.',
                                        'legal_actualizado' => 'El registro fue actualizado correctamente.',
                                        'error_agregar' => 'Error al agregar el registro.',
                                        'error_eliminar' => 'Error al eliminar el contenido.',
                                        'error_legal' => 'Error al actualizar el registro.',
                                    ];
                                    $clase = in_array($_GET['status'], ['agregado', 'eliminada', 'legal_actualizado']) ? 'success' : 'danger';
                                    ?>
                                    <div class="alert alert-<?php echo $clase; ?> alert-dismissible fade show" role="alert">
                                        <?php echo $mensajes[$_GET['status']] ?? 'Ocurrió un error inesperado.'; ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <table class="table table-bordered table-striped" id="MiAgenda" style="margin-top:20px center;">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Titulo</th>
                                        <th>Descripcion</th>
                                        <th>Última actualización</th>
                                        <th>Documento</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    include_once '../../Modelo/conexion.php';
                                    $database = new ConectarDB();
                                    $db = $database->open();

                                    try {
                                        $sql = "SELECT * FROM legales";
                                        foreach ($db->query($sql) as $row) {
                                    ?>
                                            <tr>
                                                <td><?php echo $row['id_legal']; ?></td>
                                                <td><?php echo $row['titulo']; ?></td>
                                                <td><?php echo $row['descripcion']; ?></td>
                                                <td><?php echo $row['fecha']; ?></td>
                                                <td>
                                                    <a class="btn btn-sm btn-outline-primary" target="_blank" href="../../Modelo/ver_contenido.php?id_legal=<?php echo $row['id_legal']; ?>"><i class="bi bi-file-earmark-check-fill"></i> Documento</a>
                                                </td>
                                                <td>
                                                    <a href="#editarLegal_<?php echo $row['id_legal']; ?>" class="btn btn-success btn-sm d-block" data-bs-toggle="modal"><i class="fa-solid fa-pen"></i> Editar</a><br>
                                                    <a href="#eliminarL_<?php echo $row['id_legal']; ?>" class="btn btn-danger m-auto btn-sm d-block" data-bs-toggle="modal"><i class="fa-solid fa-eraser"></i> Eliminar</a>
                                                </td>
                                                <?php include 'modales/legales.php'; ?>
                                            </tr>
                                    <?php
                                        }
                                    } catch (PDOException $e) {
                                        echo 'Hay problemas con la conexión: ' . $e->getMessage();
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <?php include 'modales/legales.php'; ?>
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