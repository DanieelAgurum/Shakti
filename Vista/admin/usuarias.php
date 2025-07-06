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

include 'modales/usuarias.php';

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarias - Shakti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="../../components/admin/styles.css">
    <script src="https://kit.fontawesome.com/3c934cb418.js" crossorigin="anonymous"></script>
    <script src="js/eliminarUsuario.js"></script>
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
                        <h1 class="page-header text-center"> <strong> Usuarias </strong></h1>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php
                                if (isset($_GET["addPro"])) {
                                    $addPro = $_GET["addPro"];
                                    echo '<div class="alert alert-dismissible alert-success" style="margin-top: 20px;">
                                    <button type="button" class="close" data-dismiss="alert">&times;
                                    </button>' . $addPro . '</div>';
                                }
                                ?>
                            </div>
                            <?php if (isset($_GET['eliminado'])) { ?>
                                <div class="alert alert-danger alert-dismissible" style="margin-top: 20px;" role="alert">
                                    <?php if (isset($_GET['eliminado'])) echo htmlspecialchars($_GET['eliminado']); ?>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">&times;</button>
                                </div>

                            <?php } ?>

                            <table class="table table-bordered table-striped" id="MiAgenda" style="margin-top:20px center;">
                                <thead>
                                    <th>Id</th>
                                    <th>Nombre</th>
                                    <th>Dirección</th>
                                    <th>Teléfono</th>
                                    <th>Opciones</th>
                                </thead>
                                <tbody>
                                    <?php
                                    include_once '../../Modelo/conexion.php';
                                    $database = new ConectarDB();
                                    $db = $database->open();
                                    try {
                                        $sql = 'SELECT* FROM usuarias WHERE id_rol = 1 ORDER BY id DESC';
                                        foreach ($db->query($sql) as $row) {
                                    ?>
                                            <tr>
                                                <td><?php echo $row['id']; ?></td>
                                                <td><?php echo $row['nombre'] . " " . $row['apellidos']; ?></td>
                                                <td><?php echo $row['direccion']; ?></td>
                                                <td><?php echo $row['telefono']; ?></td>
                                                <td>
                                                    <a href="#"
                                                        class="btn btn-danger btn-sm btnEliminar"
                                                        data-id="<?php echo $row['id']; ?>"
                                                        data-nombre="<?php echo $row['nombre'] . ' ' . $row['apellidos']; ?>"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#miModal">
                                                        <i class="fa-solid fa-eraser"></i> Eliminar
                                                    </a>

                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    } catch (PDOException $e) {
                                        echo 'Hay problemas con la conexión : ' . $e->getMessage();
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="../../components/admin/js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="../../components/admin/js/chart-area-demo.js"></script>
    <script src="../../components/admin/js/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="../../components/admin/js/datatables-simple-demo.js"></script>
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
    <footer class="py-4 bg-light mt-auto">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center justify-content-between small">
                <div class="text-muted">Copyright &copy; TechnoLution 2023</div>
            </div>
        </div>
    </footer>
</body>

</html>