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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="<?= $urlBase ?>css/estilos.css" />
    <link rel="stylesheet" href="<?= $urlBase ?>css/estiloscarrucel.css" />
    <link rel="stylesheet" href="<?= $urlBase ?>css/publicaciones.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?= $urlBase ?>peticiones(js)/mandarReporte.js"></script>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/usuaria/navbar.php'; ?>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/Vista/modales/reportarPostUsuarias.php'; ?>
    <script src="../../validacionRegistro/abrirComentarios.js"></script>
    <script src="../../peticiones(js)/likesContar.js"></script>
    <script src="../../validacionRegistro/respuestas.js"></script>
    <style>
        @media (max-width: 576px) {
            .animacion {
                animation: none !important;
            }
        }
    </style>
</head>

<body class="bg-white text-black">
    <!-- Buscador -->
    <h2 class="text-center w-100 mt-3">Publicaciones</h2>
    <div class="search-wrapper w-100">
        <div class="search-box">
            <form method="GET">
                <i class="bi bi-search search-icon"></i>
                <input type="text" name="buscador" class="form-control search-input" placeholder="Buscar ..." value="<?= $_GET['buscador'] ?? '' ?>">
            </form>
        </div>
    </div>

    <!-- Publicaciones recientes -->
    <section class="container mb-5 d-flex flex-wrap justify-content-center gap-4 animate__animated animate__fadeInLeft animacion">
        <?php
        require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/Controlador/buscadorForoCtrl.php';
        ?>
    </section>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/usuaria/footer.php'; ?>



    <!-- Scripts -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script
        src="<?= $urlBase ?>peticiones(js)/mandarMetricas.js.php?vista=<?= urlencode(basename($_SERVER['PHP_SELF'])) ?>">
    </script>
</body>

</html>