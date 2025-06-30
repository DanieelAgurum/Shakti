<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/obtenerLink/obtenerLink.php';
$urlBase = getBaseUrl();

// Inicia la sesión si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Shakti</title>

    <!-- Estilos -->
    <link rel="stylesheet" href="<?= $urlBase ?>css/estilos.css" />
    <link rel="stylesheet" href="<?= $urlBase ?>css/estiloscarrucel.css" />
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

    <?php
    include require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/usuaria/navbar.php';
    ?>
</head>

<body class="bg-white text-black">

    <!-- Carrusel principal -->
    <div class="swiper-container hero-carousel">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <img src="https://elcomercio.pe/resizer/v2/VCID6SJK6BCZ5FKGBYLNA7BJII.jpg" alt="Mujer practicando yoga">
                <div class="slide-overlay"></div>
            </div>
            <div class="swiper-slide">
                <img src="https://media.glamour.mx/photos/63effc578b641025c85eb38a/master/pass/marcha.jpg" alt="Marcha de mujeres">
                <div class="slide-overlay"></div>
            </div>
            <div class="swiper-slide">
                <img src="http://www.fundipax.org/wp-content/uploads/2023/03/meditacion.jpg" alt="Meditación y bienestar">
                <div class="slide-overlay"></div>
            </div>
            <div class="swiper-slide">
                <img src="https://museodelacuerdo.cultura.gob.ar/media/uploads/site-22/mujeres-unidas.jpg" alt="Mujeres unidas">
                <div class="slide-overlay"></div>
            </div>
            <div class="swiper-slide">
                <img src="https://img.freepik.com/vector-premium/mujeres-multiculturales.jpg" alt="Mujeres multiculturales">
                <div class="slide-overlay"></div>
            </div>
            <div class="swiper-slide">
                <img src="https://bonisimo.es/img/cms/BLOG/dia_internacional_de_la_mujer.jpg" alt="Comunidad de mujeres">
                <div class="slide-overlay"></div>
            </div>
        </div>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>

    <!-- Contenido principal -->
    <main class="hero p-5 text-center">
        <?php if (isset($_SESSION['correo'])): ?>
            <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/Vista/usuaria/inicioUser.php'; ?>
        <?php else: ?>
            <h1>Bienvenido a Nuestro Sitio Shakti</h1>
            <p class="lead">Tu bienestar es primero</p>
            <div class="hero-buttons mt-4">
                <a href="#" class="btn btn-primary me-2">Conocer más</a>
                <a href="#" class="btn btn-outline-secondary">Contáctanos</a>
            </div>
        <?php endif; ?>
    </main>

    <!-- Publicación destacada -->
    <div class="shakti-post">
        <div class="post-header">
            <div class="profile-info">
                <img src="<?= $urlBase ?>img/usuario.jpg" alt="Foto de perfil" class="profile-pic">
                <div class="profile-details">
                    <span class="username">Roxana21</span>
                    <span class="follow-text"> • Seguir</span>
                </div>
            </div>
            <i class="fas fa-ellipsis-h dots-icon"></i>
        </div>

        <div class="post-content">
            <img src="<?= $urlBase ?>img/violentometro.png" alt="Violentómetro" class="violentometro-image">
        </div>

        <div class="post-actions">
            <div class="icons-left">
                <i class="far fa-heart icon"></i>
                <i class="far fa-comment icon"></i>
                <i class="far fa-paper-plane icon"></i>
            </div>
            <div class="icons-right">
                <i class="far fa-bookmark icon"></i>
            </div>
        </div>

        <div class="post-likes">
            <p><strong>2 Me gusta</strong></p>
        </div>

        <div class="post-date">
            <p>30 de Junio</p>
        </div>

        <div class="add-comment">
            <img src="https://via.placeholder.com/24" alt="Tu foto de perfil" class="comment-profile-pic">
            <input type="text" placeholder="Añade un comentario...">
            <button class="comment-emoji"><i class="far fa-grin"></i></button>
        </div>
    </div>

    <!-- Pie de página -->
    <?php
    include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/usuaria/footer.php';
    ?>

    <!-- Scripts -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        var swiper = new Swiper('.hero-carousel', {
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },
            loop: true,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    </script>
</body>

</html>