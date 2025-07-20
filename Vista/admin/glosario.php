<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/obtenerLink/obtenerLink.php';
$urlBase = getBaseUrl();

session_start();

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
    <title>Glosario - Shakti</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-pbciWgAl4KuOkZ5vGQZzELtiQ+3I6Mxn9c8E9p8ywRrbKwHmeEpeL6PH20FqSb70vPjJOeTEJjFZ32Vb9I55Xg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://kit.fontawesome.com/eacdd605ec.js" crossorigin="anonymous"></script>
    <script src="https://cdn.tiny.cloud/1/bnw4wazqztadr4il1bfgdflo063pyw0wvki8x1q8d9xx0akz/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="js/tinyMC.js"></script>
    <script src="js/glosario.js"></script>
</head>

<body class="sb-nav-fixed">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/admin/navbar.php'; ?>

    <div id="layoutSidenav">
        <?php
        include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/admin/lateral.php';
        include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/Vista/admin/modales/glosario.php';
        ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4 mb-5">
                    <div class="container">
                        <h1 class="page-header text-center"><strong>Glosario</strong></h1>
                        <button
                            type="button"
                            style="margin-bottom: 8px;"
                            class="btn btn-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#exampleModal">
                            Nuevo <i class="fa-solid fa-circle-plus"></i>
                        </button>
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered table-striped" id="MiAgenda" style="margin-top:20px center;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Icono</th>
                                            <th>Titulo</th>
                                            <th>Descripción</th>
                                            <th>Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/Modelo/GlosarioMdl.php';
                                        $glo = new GlosarioMdl();
                                        $glo->verGlosario();
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

<script src="js/tabla.js"></script>

</html>