<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Modelo/conexion.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/obtenerLink/obtenerLink.php';
$urlBase = getBaseUrl();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['correo']) || $_SESSION['id_rol'] == 2) {
    header("Location: {$urlBase}Vista/especialista/perfil.php");
    exit;
} else if (empty($_SESSION['correo']) || $_SESSION['id_rol'] == 3) {
    header("Location: {$urlBase}Vista/admin");
    exit;
} else if (empty($_SESSION['correo']) || $_SESSION['id_rol'] != 1) {
    header("Location: {$urlBase}index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Especialistas - NexoH</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="<?= $urlBase ?>css/animacionCarga.css" />
    <script src="https://kit.fontawesome.com/fbc3385146.js" crossorigin="anonymous"></script>
    <script src="<?= $urlBase ?>peticiones(js)/mandarReporte.js"></script>
    <script src="<?= $urlBase ?>peticiones(js)/usuariosSearch.js"></script>
    <?php
    include $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/usuaria/navbar.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/shakti/Vista/modales/reportarEspecialista.php';
    ?>
</head>

<body>
    <main class="">
        <!-- Sidebar de solicitudes -->
        <!-- <aside class="solicitud" id="solicitudSidebar">
        </aside> -->

        <!-- Sección principal -->
        <section class="contenido-principal">
            <div class="contenedor-buscador mt-5">
                <div class="search-foro buscador-fijo mx-auto">
                    <div class="search-box w-100">
                        <form class="w-100" onsubmit="return false;">
                            <i class="bi bi-search search-icon"></i>
                            <input type="text" name="buscador" class="form-control search-input" placeholder="Buscar ...">
                        </form>
                    </div>
                </div>
            </div>

            <!-- Cards de usuarios -->
            <div class="container mt-4">
                <div id="usuariosGrid"></div>
            </div>
        </section>
    </main>
</body>

</html>