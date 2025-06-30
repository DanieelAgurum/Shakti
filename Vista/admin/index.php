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
    <title>Panel de administrador - Shakti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="../../components/admin/styles.css">
    <script src="https://kit.fontawesome.com/3c934cb418.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fuggles&family=Lato&family=Mooli&display=swap" rel="stylesheet">
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
            <nav class="sb-sidenav accordion" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <a class="nav-link text-dark" href="panel.php">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-house text-dark"></i></div>
                            Inicio
                        </a>
                        <a class="nav-link collapsed text-dark" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-bars text-dark"></i></div>
                            Opciones
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down text-dark"></i></div>
                        </a>
                        <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                                <a class="nav-link collapsed text-dark" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseError" aria-expanded="false" aria-controls="pagesCollapseError">
                                    <div class="sb-nav-link-icon"><i class="fa-solid fa-table text-dark"></i></div>
                                    Tablas
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down text-dark"></i></div>
                                </a>
                                <div class="collapse" id="pagesCollapseError" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link text-dark" href="#">Usuarias</a>
                                        <a class="nav-link text-dark" href="#">Especialistas</a>
                                        <a class="nav-link text-dark" href="#">Publicaciones</a>
                                        <a class="nav-link text-dark" href="#">Contenido</a>
                                        <a class="nav-link text-dark" href="#">Documentos</a>
                                        <a class="nav-link text-dark" href="#">Organizaciones</a>
                                        <a class="nav-link text-dark" href="#">Reportes</a>
                                        <a class="nav-link text-dark" href="#">Comentarios</a>
                                    </nav>
                                </div>
                            </nav>
                        </div>
                    </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4 text-center solid"><strong>Panel de control</strong></h1>
                    <ol class="breadcrumb mb-4">
                    </ol>
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="card text-dark mb-4">
                                <div class="card-body">Usuarias</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-dark stretched-link" href="Usuarias.php">Ver detalles</a>
                                    <div class="small text-dark"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card text-dark mb-4">
                                <div class="card-body">Especialistas</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-dark stretched-link" href="Especialistas.php">Ver detalles</a>
                                    <div class="small text-dark"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card text-dark mb-4">
                                <div class="card-body">Publicaciones</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-dark stretched-link" href="">Ver detalles</a>
                                    <div class="small text-dark"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card text-dark mb-4">
                                <div class="card-body">Contenido</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-dark stretched-link" href="">Ver detalles</a>
                                    <div class="small text-dark"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card text-dark mb-4">
                                <div class="card-body">Documentos</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-dark stretched-link" href="">Ver detalles</a>
                                    <div class="small text-dark"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card text-dark mb-4">
                                <div class="card-body">Organizaciones</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-dark stretched-link" href="">Ver detalles</a>
                                    <div class="small text-dark"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card text-dark mb-4">
                                <div class="card-body">Reportes</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-dark stretched-link" href="">Ver detalles</a>
                                    <div class="small text-dark"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card text-dark mb-4">
                                <div class="card-body">Comentarios</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-dark stretched-link" href="">Ver detalles</a>
                                    <div class="small text-dark"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <footer class="mt-auto text-dark">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center justify-content-between small">
                <div class="text-mute text-dark">Copyright &copy; TechnoLution 2023</div>
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