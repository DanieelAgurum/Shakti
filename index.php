<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/obtenerLink/obtenerLink.php';
$urlBase = getBaseUrl();

// Incluye el modelo para obtener publicaciones
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/modelo/PublicacionModelo.php';
$publicacionModelo = new PublicacionModelo();
$publicaciones = $publicacionModelo->obtenerTodasConNickname();
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Shakti</title>
  <!-- Tu CSS personalizado -->
  <link rel="stylesheet" href="<?= $urlBase ?>css/styles.css" />
  <?php
  include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/usuaria/navbar.php';
  ?>
</head>

<body class="bg-white text-black">

    <div class="swiper-container hero-carousel">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <img src="https://elcomercio.pe/resizer/v2/VCID6SJK6BCZ5FKGBYLNA7BJII.jpg?auth=13c827afa0bfc6b3130bfa35ae48a8c0f2de46f2a15612e0cd5179383a331d9f&width=1200&quality=90&smart=true" alt="Mujer practicando yoga">
                <div class="slide-overlay"></div>
                <div class="slide-content"></div>
            </div>
            <div class="swiper-slide">
                <img src="https://media.glamour.mx/photos/63effc578b641025c85eb38a/16:9/w_2560%2Cc_limit/marcha-8-de-marzo-cdmx-mexico-en-vivo.jpg" alt="Marcha de mujeres">
                <div class="slide-overlay"></div>
                <div class="slide-content"></div>
            </div>
            <div class="swiper-slide">
                <img src="http://www.fundipax.org/wp-content/uploads/2023/03/dia-de-la-mujer-8-de-marzo-fundipax.jpg" alt="Meditación y bienestar">
                <div class="slide-overlay"></div>
                <div class="slide-content"></div>
            </div>
            <div class="swiper-slide">
                <img src="https://museodelacuerdo.cultura.gob.ar/media/uploads/site-22/destacados/.thumbnails/mujeres-de-espaldas-unidas.jpg/mujeres-de-espaldas-unidas-600x0-no-upscale.jpg" alt="Mujeres unidas">
                <div class="slide-overlay"></div>
                <div class="slide-content"></div>
            </div>
            <div class="swiper-slide">
                <img src="https://img.freepik.com/vector-premium/mujeres-multiculturales-bandera-horizontal_255494-1226.jpg?w=1380" alt="Mujeres multiculturales">
                <div class="slide-overlay"></div>
                <div class="slide-content"></div>
            </div>
            <div class="swiper-slide">
                <img src="https://bonisimo.es/img/cms/BLOG/dia_internacional_de_la_mujer-cabecera.png" alt="Comunidad de mujeres">
                <div class="slide-overlay"></div>
                <div class="slide-content"></div>
            </div>
        </div>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>

  <main class="hero p-5 text-center">
    <?php if (isset($_SESSION['correo'])) {
      require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/Vista/usuaria/inicioUser.php'; ?>
    <?php } else { ?>
      <h1>Bienvenido a Nuestro Sitio Shakti</h1>
      <p class="lead">Tu bienestar es primero</p>
      <div class="hero-buttons mt-4">
        <a href="#" class="btn btn-primary me-2">Conocer más</a>
        <a href="#" class="btn btn-outline-secondary">Contáctanos</a>
      </div>
    <?php } ?>
  </main>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/usuaria/footer.php'; ?>

    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <script>
        var swiper = new Swiper('.hero-carousel', {
            // Opciones de configuración
            effect: 'fade', // Efecto de transición 'fade' (difuminado), muy elegante
            fadeEffect: {
                crossFade: true
            },
            loop: true, // Para que el carrusel sea infinito
            autoplay: {
                delay: 4000, // Se mueve cada 4 segundos
                disableOnInteraction: false, // El autoplay no se detiene si el usuario interactúa
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true, // Permite hacer clic en los puntos para navegar
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    </script>

</body>

</html>
