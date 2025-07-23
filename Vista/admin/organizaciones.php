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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organizaciones - Shakti</title>
</head>

<body class="sb-nav-fixed">
    <?php
    include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/admin/navbar.php';
    ?>
    <div id="layoutSidenav">
        <?php
        include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/admin/lateral.php';
        ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4 mb-5">
                    <h1 class="mt-4"></h1>
                    <div class="container">
                        <h1 class="page-header text-center"> <strong> Organizaciones </strong></h1>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php if (isset($_GET['status'])): ?>
                                    <?php
                                    $mensajes = [
                                        'eliminada' => 'La organización fue eliminada correctamente.',
                                        'estatus_actualizado' => 'El estado de la organización fue actualizado correctamente.',
                                        'error_activar' => 'Error al activar la organización.',
                                        'error_eliminar' => 'Error al eliminar la organización.',
                                        'error_estatus' => 'Error al cambiar el estado de la organización.',
                                    ];
                                    $clase = in_array($_GET['status'], ['activada', 'eliminada']) ? 'success' : 'danger';
                                    ?>
                                    <div class="alert alert-<?php echo $clase; ?> alert-dismissible fade show" role="alert">
                                        <?php echo $mensajes[$_GET['status']]; ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <table class="table table-bordered table-striped" id="MiAgenda" style="margin-top:20px center;">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Imagen</th>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    include_once '../../Modelo/conexion.php';
                                    $database = new ConectarDB();
                                    $db = $database->open();

                                    try {
                                        $sql = "SELECT id, nombre, descripcion, imagen, estatus FROM organizaciones";
                                        foreach ($db->query($sql) as $row) {
                                    ?>
                                            <tr>
                                                <td><?php echo $row['id']; ?></td>
                                                <td>
                                                    <?php if (!empty($row['imagen'])): ?>
                                                        <img src="data:image/*;base64,<?php echo base64_encode($row['imagen']); ?>" width="60px" height="60px" alt="">
                                                    <?php else: ?>
                                                        <img src="https://cdn1.iconfinder.com/data/icons/business-1218/512/business_organization-512.png" width="60px" height="60px" alt="">
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo $row['nombre']; ?></td>
                                                <td><?php echo $row['descripcion']; ?></td>
                                                <?php include '../modales/perfil_organizacion.php'; ?>
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