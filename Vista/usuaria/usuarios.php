<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/obtenerLink/obtenerLink.php';
$urlBase = getBaseUrl();

if (isset($_SESSION['id_rol']) && $_SESSION['id_rol'] == 3) {
    header("Location: shakti/Vista/admin");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Shakti</title>

    <!-- CSS crítico -->
    <link rel="stylesheet" href="<?= $urlBase ?>css/stylesChat.css">
    <link rel="stylesheet" href="<?= $urlBase ?>css/animacionCarga.css" />
    <!-- Librerías externas -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" defer>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" defer>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.css" defer>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.theme.min.css" defer>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" defer>

    <!-- Scripts JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="<?= $urlBase ?>/peticiones(js)/usuariosSearch.js"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/usuaria/navbar.php'; ?>
</head>


<body>
    <main class="layout">
        <!-- Sidebar de solicitudes -->
        <aside class="solicitud" id="solicitudSidebar">
        </aside>

        <!-- Sección principal -->
        <section class="contenido-principal">
            <div class="contenedor-buscador">
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
            <div id="usuariosGrid">
            </div>
        </section>
    </main>
</body>

</html>