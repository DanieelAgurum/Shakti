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
    <title>Especialistas - Shakti</title>
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
                        <h1 class="page-header text-center"> <strong> Especialistas </strong></h1>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php if (isset($_GET['status'])): ?>
                                    <?php
                                    $mensajes = [
                                        'eliminada' => 'La cuenta fue eliminada correctamente.',
                                        'estatus_actualizado' => 'El estado de la cuenta fue actualizado correctamente.',
                                        'error_activar' => 'Error al activar la cuenta.',
                                        'error_eliminar' => 'Error al eliminar la cuenta.',
                                        'error_estatus' => 'Error al cambiar el estado de la cuenta.',
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
                                        <th>Nombre completo</th>
                                        <th>Correo</th>
                                        <th>Teléfono</th>
                                        <th>Dirección</th>
                                        <th>Estatus</th>
                                        <th>Documentos</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    include_once '../../Modelo/conexion.php';
                                    $database = new ConectarDB();
                                    $db = $database->open();

                                    try {
                                        $sql = "SELECT u.id, u.nombre, u.apellidos, u.nickname, u.correo, u.telefono, u.direccion, u.estatus,
                                        d.id_documento, d.id_oficial, d.documento1, d.documento2, d.documento3, d.documento4 
                                        FROM usuarias u INNER JOIN documentos d ON u.id = d.id_usuaria WHERE u.id_rol = 2";
                                        foreach ($db->query($sql) as $row) {
                                    ?>
                                            <tr>
                                                <td><?php echo $row['id']; ?></td>
                                                <td><?php echo $row['nombre'] . ' ' . $row['apellidos']; ?></td>
                                                <td><?php echo $row['correo']; ?></td>
                                                <td><?php echo $row['telefono']; ?></td>
                                                <td><?php echo $row['direccion']; ?></td>
                                                <td>
                                                    <?php if ($row['estatus'] == 1): ?>
                                                        <span class="badge bg-success">Activa</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">Desactivada</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a class="btn btn-sm btn-outline-primary" target="_blank" href="../../Modelo/ver_documento.php?id=<?php echo $row['id']; ?>&doc=0"><i class="fa-solid fa-id-card"></i> ID Oficial</a><br>
                                                    <?php for ($i = 1; $i <= 4; $i++): ?>
                                                        <?php if (!empty($row["documento$i"])): ?>
                                                            <a class="btn btn-sm btn-outline-secondary mt-1" target="_blank" href="../../Modelo/ver_documento.php?id=<?php echo $row['id']; ?>&doc=<?php echo $i; ?>"><i class="fa-solid fa-file-pdf"></i> Documento <?php echo $i; ?></a><br>
                                                        <?php endif; ?>
                                                    <?php endfor; ?>
                                                </td>
                                                <td>
                                                    <a href="#cambiarEstado_<?php echo $row['id']; ?>" class="btn btn-sm <?php echo $row['estatus'] == 1 ? 'btn-warning' : 'btn-success'; ?> d-block" data-toggle="modal">
                                                        <i class="fa-sharp fa-solid fa-pen-to-square"></i>
                                                        <?php echo $row['estatus'] == 1 ? 'Desactivar' : 'Activar'; ?>
                                                    </a><br>
                                                    <a href="#eliminarE_<?php echo $row['id']; ?>" class="btn btn-danger m-auto btn-sm d-block" data-toggle="modal"><i class="fa-solid fa-eraser"></i> Eliminar</a>
                                                </td>
                                                <?php include '../modales/perfil.php'; ?>
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