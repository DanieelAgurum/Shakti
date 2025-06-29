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
    <nav class="sb-topnav navbar navbar-expand">
        <a class="navbar-brand ps-3" href="index.php"></a>
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <div class="input-group"></div>
        </form>
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-dark" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user fa-fw text-dark"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="<?php echo $urlBase ?>/Controlador/loginCtrl.php?opcion=2">Cerrar Sesión</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <a class="nav-link text-dark" href="panel.php">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-house text-dark"></i></div>
                            <strong> Inicio </strong>
                        </a>
                        <a class="nav-link collapsed text-dark" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-bars text-dark"></i></div>
                            <strong> Opciones </strong>
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down text-dark"></i></div>
                        </a>
                        <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                                <a class="nav-link collapsed text-dark" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseError" aria-expanded="false" aria-controls="pagesCollapseError">
                                    <div class="sb-nav-link-icon"><i class="fa-solid fa-table text-dark"></i></div>
                                    <strong> Tablas</strong>
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down text-dark"></i></div>
                                </a>
                                <div class="collapse" id="pagesCollapseError" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link text-dark" href="#">Usuarios</a>
                                        <a class="nav-link text-dark" href="#">Productos</a>
                                        <a class="nav-link text-dark" href="#">Categorías</a>
                                        <a class="nav-link text-dark" href="#">Carrito</a>
                                        <a class="nav-link text-dark" href="#">Citas</a>
                                        <a class="nav-link text-dark" href="#">Comentarios</a>
                                        <a class="nav-link text-dark" href="#">Direcciones</a>
                                        <a class="nav-link text-dark" href="#">Compra</a>
                                    </nav>
                                </div>
                            </nav>
                        </div>
                    </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div style="margin-top: -100px">
                    <h1 class="mt-4"></h1>

                    <div class="container">
                        <h1 class="page-header text-center"> <strong> Productos </strong></h1>
                        <div class="row">
                            <div class="col-sm-12">
                                <a href="#addNew" class="btn btn-primary" data-toggle="modal" style="margin-bottom: 8px;"><i class="fa-solid fa-circle-plus"></i> Nuevo</a>
                                <?php
                                if (isset($_GET["addPro"])) {
                                    $addPro = $_GET["addPro"];
                                    echo '<div class="alert alert-dismissible alert-success" style="margin-top: 20px;">
                                    <button type="button" class="close" data-dismiss="alert">&times;
                                    </button>' . $addPro . '</div>';
                                }
                                ?>
                            </div>
                            <table class="table table-bordered table-striped" id="MiAgenda" style="margin-top:20px center;">
                                <thead>
                                    <th>ID</th>
                                    <th>Imagen</th>
                                    <th>Nombre</th>
                                    <th>Categoría</th>
                                    <th>Descripción</th>
                                    <th>Precio</th>
                                    <th>Stock</th>
                                    <th>Opciones</th>
                                </thead>
                                <tbody>
                                    <?php
                                    include_once('../../modelo/conexionBD.php');
                                    $database = new ConectarDB();
                                    $db = $database->open();
                                    try {
                                        $sql = 'SELECT* FROM productos p INNER JOIN categorias c ON p.categorias_id_categoria = c.id_categoria';
                                        foreach ($db->query($sql) as $row) {
                                    ?>
                                            <tr>
                                                <td><?php echo $row['id_producto']; ?></td>
                                                <td>
                                                    <img src="data:image/*;base64,<?php echo base64_encode($row['imagen']); ?>" width="60px" height="60px" alt="">
                                                </td>
                                                <td><?php echo $row['nombre_producto']; ?></td>
                                                <td><?php echo $row['nombre_categoria']; ?></td>
                                                <td><?php echo $row['descripcion']; ?></td>
                                                <td><?php echo $row['precio']; ?></td>
                                                <td><?php echo $row['stock']; ?></td>
                                                <td class="">
                                                    <a href="#editP_<?php echo $row['id_producto']; ?>" class="btn btn-success  m-auto btn-sm d-block" data-toggle="modal"><i class="fa-sharp fa-solid fa-pen-to-square"></i> Editar</a><br>
                                                    <a href="#deleteP_<?php echo $row['id_producto']; ?>" class="btn btn-danger m-auto btn-sm d-block" data-toggle="modal"><i class="fa-solid fa-eraser"></i> Eliminar</a>
                                                </td>
                                                <?php
                                                include '../modulos/modales.php';
                                                ?>
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
    <script src="<?php echo $ruta . "/" ?>js/bootstrap.min.js"></script>
    <script src="<?php echo $ruta . "/" ?>js/datatables.min.js"></script>
    <script type="text/javascript" src="<?php echo $ruta . "/" ?>js/datatables.min.js"></script>
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


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="../../components/admin/js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="../../components/admin/js/chart-area-demo.js"></script>
    <script src="../../components/admin/js/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="../../components/admin/js/datatables-simple-demo.js"></script>
</body>

</html>