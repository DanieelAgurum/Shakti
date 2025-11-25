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
    <title>Panel de administrador - Shakti</title>
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/admin/icono.php' ?>
</head>


<body class="sb-nav-fixed" style="background-color: var(--color-fondo-claro);">
    <?php
    include  $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/admin/navbar.php';
    ?>
    <div id="layoutSidenav">
        <?php
        include  $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/admin/lateral.php';
        ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4 text-center solid"><strong>Panel de control</strong></h1>
                    <ol class="breadcrumb mb-4">
                    </ol>
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="card text-dark mb-4">
                                <div class="card-body">Usuarios</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-dark stretched-link" href="usuarias">Ver detalles</a>
                                    <div class="small text-dark"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card text-dark mb-4">
                                <div class="card-body">Especialistas</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-dark stretched-link" href="especialistas">Ver detalles</a>
                                    <div class="small text-dark"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card text-dark mb-4">
                                <div class="card-body">Contenido</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-dark stretched-link" href="contenido">Ver detalles</a>
                                    <div class="small text-dark"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card text-dark mb-4">
                                <div class="card-body">Documentos legales</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-dark stretched-link" href="libre_segura">Ver detalles</a>
                                    <div class="small text-dark"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card text-dark mb-4">
                                <div class="card-body">Organizaciones</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-dark stretched-link" href="organizaciones">Ver detalles</a>
                                    <div class="small text-dark"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card text-dark mb-4">
                                <div class="card-body">Reportes</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-dark stretched-link" href="<?php echo $urlBase ?>Vista/admin/reportes">Ver detalles</a>
                                    <div class="small text-dark"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card text-dark mb-4">
                                <div class="card-body">Preguntas Frecuentes</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-dark stretched-link" href="preguntas_frecuentes">Ver detalles</a>
                                    <div class="small text-dark"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card text-dark mb-4">
                                <div class="card-body">Métricas</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-dark stretched-link" href="metricas">Ver detalles</a>
                                    <div class="small text-dark"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card text-dark mb-4">
                                <div class="card-body">Glosario</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-dark stretched-link" href="glosario">Ver detalles</a>
                                    <div class="small text-dark"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>