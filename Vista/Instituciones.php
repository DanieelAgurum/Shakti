<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/obtenerLink/obtenerLink.php';
$urlBase = getBaseUrl();

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instituciones</title>
    <!-- Librerías adicionales en el head del navbar -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.theme.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= $urlBase ?>css/Instituciones.css">
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <!-- Scripts únicos -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/usuaria/navbar.php'; ?>
</head>

<body>
    <div class="recurso-pagina-principal container">
        <h1 class="recurso-titulo-principal text-center mt-4 mb-4">Instituciones</h1>

        <div class="recurso-tarjetas-fila row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-5">
        </div>
    </div>
    <?php
    include $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/usuaria/footer.php';
    ?>
    <script src="<?= $urlBase ?>peticiones(js)/cargarInstituciones.js"></script>
</body>

</html>