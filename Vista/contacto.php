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
    <title>Contacto - Shakti</title>

    <!-- Estilos -->
    <link rel="stylesheet" href="../css/contacto.css">
    <link rel="stylesheet" href="<?= $urlBase ?>css/estilos.css" />
    <link rel="stylesheet" href="<?= $urlBase ?>css/animacionCarga.css" />
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>


</head>



<body>
    <?php
    include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/usuaria/navbar.php';
    ?>

    <div class="container mt-3 mb-3 animate__animated animate__delay-0.5s animate__fadeInDown">
        <div class="form m-auto">
            <div class="contact-info">
                <h3 class="title">Comunícate con nosotros</h3>
                <p class="text">Si tienes alguna duda, comentario o sugerencia, no dudes en contactarnos.</p>
                <div class="info">
                    <div class="social-information">
                        <i class="bi bi-geo-alt"></i>
                        <p>Nezahualcóyotl, Estado de México</p>
                    </div>
                    <div class="social-information">
                        <i class="bi bi-envelope-at"></i>
                        <p>shakti@gmail.com</p>
                    </div>
                    <div class="social-information">
                        <i class="bi bi-telephone"></i>
                        <p>+52 5678012353</p>
                    </div>
                </div>
                <div class="social-media">
                    <p>Conecta con nosotros :</p>
                    <div class="social-icons">
                        <a href="#"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-instagram"></i></a>
                        <a href="#"><i class="bi bi-tiktok"></i></a>
                    </div>
                </div>
            </div>

            <div class="contact-info-form">
                <span class="circle one"></span>
                <span class="circle two"></span>

                <form id="contactForm" class="colortext">
                    <div class="social-input-containers">
                        <input type="email" name="correo" class="input" placeholder="Correo electrónico"
                            value="<?php echo isset($_SESSION['correo']) ? strtolower($_SESSION['correo']) : ''; ?>">
                    </div>
                    <div class="social-input-containers textarea">
                        <textarea name="comentario" class="input" placeholder="Déjanos tu mensaje"></textarea>
                    </div>
                    <div class="float-end">
                        <button id="btnEnviar" class="btn btn-outline-dark" type="submit">
                            <i class="bi bi-send-fill" id="icono" ></i>
                            <div id="loaderInicioBtn" class="heart-spinner d-none" role="status"
                                aria-label="Cargando..."></div>
                            <span>Enviar</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include '../components/usuaria/footer.php'; ?>

    <!-- Scripts adicionales -->
    <script src="<?= $urlBase ?>peticiones(js)/mandarFormContact.js"></script>
    <script src="<?= $urlBase ?>peticiones(js)/mandarMetricas.js.php?vista=<?= urlencode(basename($_SERVER['PHP_SELF'])) ?>"></script>
</body>

</html>