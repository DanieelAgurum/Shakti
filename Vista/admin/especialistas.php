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
    <title>Especialistas - Shakti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="../../components/admin/styles.css">
    <script src="https://kit.fontawesome.com/3c934cb418.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fuggles&family=Lato&family=Mooli&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../components/admin/custom.css">
    <link rel="stylesheet" href="../../components/admin/datatables.min.css">
    <link rel="stylesheet" href="../../components/admin/bootstrap.min.css">
</head>

<body class="sb-nav-fixed">
    <?php
    include  $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/admin/navbar.php';
    ?>
    <div id="layoutSidenav">
        <?php
        include  $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/admin/lateral.php';
        ?>
        <div id="layoutSidenav_content">
            <main>
                <div style="margin-top: -100px">
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
                                        <th>Foto</th>
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
                                        $sql = "SELECT u.id, u.foto, u.nombre, u.apellidos, u.nickname, u.correo, u.telefono, u.direccion, u.estatus,
                                        d.id_documento, d.id_oficial, d.documento1, d.documento2, d.documento3, d.documento4 
                                        FROM usuarias u INNER JOIN documentos d ON u.id = d.id_usuaria WHERE u.id_rol = 2";
                                        foreach ($db->query($sql) as $row) {
                                    ?>
                                            <tr>
                                                <td><?php echo $row['id']; ?></td>
                                                <td>
                                                    <?php if (!empty($row['foto'])): ?>
                                                        <img src="data:image/*;base64,<?php echo base64_encode($row['foto']); ?>" width="60px" height="60px" alt="">
                                                    <?php else: ?>
                                                        <img src="https://cdn1.iconfinder.com/data/icons/avatar-3/512/Secretary-512.png" width="60px" height="60px" alt="">
                                                    <?php endif; ?>
                                                </td>
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
                                                    <a href="#cambiarEstado_<?php echo $row['id']; ?>" class="btn btn-sm <?php echo $row['estatus'] == 1 ? 'btn-danger' : 'btn-success'; ?> d-block" data-toggle="modal">
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
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="../../components/admin/js/bootstrap.min.js"></script>
    <script src="../../components/admin/js/datatables.min.js"></script>
    <script type="text/javascript" src="../../components/admin/js/datatables.min.js"></script>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="../../components/admin/js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="../../components/admin/js/chart-area-demo.js"></script>
    <script src="../../components/admin/js/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="../../components/admin/js/datatables-simple-demo.js"></script>
</body>

</html>