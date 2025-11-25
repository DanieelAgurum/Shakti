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
    <title>Organizaciones - NexoH</title>
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/admin/icono.php' ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet"
        href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/organizaciones.js"></script>
</head>

<body class="sb-nav-fixed">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/admin/navbar.php'; ?>
    <div id="layoutSidenav">
        <?php
        include $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/admin/lateral.php';
        include $_SERVER['DOCUMENT_ROOT'] . '/shakti/Vista/admin/modales/organizaciones.php';
        ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4 mb-5">
                    <h1 class="page-header text-center mt-4"><strong>Organizaciones</strong></h1>

                    <div class="col-sm-12">
                        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                            data-bs-target="#exampleModal">
                            Nuevo <i class="fa-solid fa-circle-plus"></i>
                        </button>

                        <?php if (isset($_GET['estado'])):
                            $mensajes = [
                                'agregado' => 'La organización fue agregada correctamente.',
                                'modificado' => 'La organización fue modificada correctamente.',
                                'eliminado' => 'La organización fue eliminada correctamente.',
                                'error' => 'Intentelo más tarde.'
                            ];
                            $clases = [
                                'agregado' => 'success',
                                'modificado' => 'primary',
                                'eliminado' => 'danger',
                                'error' => 'warning'
                            ];
                            $estado = $_GET['estado'];
                            if (isset($mensajes[$estado])): ?>
                                <div class="alert alert-<?php echo htmlspecialchars($clases[$estado]); ?> alert-dismissible fade show"
                                    role="alert">
                                    <?php echo htmlspecialchars($mensajes[$estado]); ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                                </div>
                        <?php endif;
                        endif; ?>

                        <table id="MiAgenda" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Teléfono</th>
                                    <th>Domicilio</th>
                                    <th>Imagen</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody class="tablaOrganizaciones"></tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <div id="modalesContainer"></div>

</body>

</html>