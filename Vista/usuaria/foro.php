<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/obtenerLink/obtenerLink.php';
$urlBase = getBaseUrl();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Foro - Shakti</title>

    <!-- Estilos CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="<?= $urlBase ?>css/estilos.css" />
    <link rel="stylesheet" href="<?= $urlBase ?>css/estiloscarrucel.css" />

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?= $urlBase ?>peticiones(js)/mandarReporte.js"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="<?= $urlBase ?>validacionRegistro/respuestas.js"></script>
    <script src="<?= $urlBase ?>peticiones(js)/mandarMetricas.js.php?vista=<?= urlencode(basename($_SERVER['PHP_SELF'])) ?>"></script>
    <script src="<?= $urlBase ?>peticiones(js)/likesContar.js"></script>
    <script src="<?= $urlBase ?>validacionRegistro/abrirComentarios.js"></script>
    <?php
    include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/usuaria/navbar.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/Vista/modales/reportarPostUsuarias.php';
    ?>
</head>

<body class="bg-white text-black">
    <div class="search-wrapper w-100">
        <div class="search-box w-100">
            <form method="GET">
                <i class="bi bi-search search-icon"></i>
                <input type="text" name="buscador" class="form-control search-input" placeholder="Buscar ..."
                    value="<?= htmlspecialchars($_GET['buscador'] ?? '') ?>">
            </form>
        </div>
    </div>

    <!-- Loader inicial (solo se muestra una vez después del buscador) -->
    <div id="loaderInicio" class="text-center my-4">
        <div class="spinner-border text-danger" role="status" style="width: 2.5rem; height: 2.5rem;">
            <span class="visually-hidden">Cargando publicaciones...</span>
        </div>
    </div>

    <section id="contenedorPublicaciones" class="container mb-5 d-flex flex-wrap justify-content-center gap-4">
    </section>

    <div id="scrollLoader" class="text-center my-4 d-none">
        <div class="spinner-border text-danger" role="status">
            <span class="visually-hidden">Cargando más publicaciones...</span>
        </div>
    </div>
    <script src="<?= $urlBase ?>peticiones(js)/scroll_infinito.js"></script>
</body>

</html>