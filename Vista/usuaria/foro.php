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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Foro - Shakti</title>

    <!-- Estilos CSS -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="<?= $urlBase ?>css/animacionCarga.css" />
    <link rel="stylesheet" href="<?= $urlBase ?>css/foro.css" />
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?= $urlBase ?>peticiones(js)/mandarReporte.js"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="<?= $urlBase ?>validacionRegistro/respuestas.js"></script>
    <script src="<?= $urlBase ?>peticiones(js)/mandarMetricas.js.php?vista=<?= urlencode(basename($_SERVER['PHP_SELF'])) ?>"></script>
    <script src="<?= $urlBase ?>peticiones(js)/likesContar.js"></script>
    <script src="<?= $urlBase ?>validacionRegistro/abrirComentarios.js"></script>
    <script src="<?= $urlBase ?>peticiones(js)/compartirPost.js"></script>
    <meta name="google-adsense-account" content="ca-pub-6265821190577353">
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-6265821190577353" crossorigin="anonymous"></script>
    <?php
    include $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/usuaria/navbar.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/shakti/Vista/modales/reportarPostUsuarias.php';
    ?>
</head>

<body class="foro_main text-black">
    <div class="contenedor-buscador">
        <div class="search-wrapper buscador-fijo mx-auto">
            <div class="search-box w-100">
                <form method="GET" class="w-100">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" name="buscador" class="form-control search-input" placeholder="Buscar ..."
                        value="<?= htmlspecialchars($_GET['buscador'] ?? '') ?>">
                </form>
            </div>
        </div>
    </div>
    <div class="foro">
        <div id="loaderInicio" class="loader-container d-none">
            <div class="orbit">
                <div class="heart">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 
                   2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09
                   C13.09 3.81 14.76 3 16.5 3 
                   19.58 3 22 5.42 22 8.5
                   c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <section id="contenedorPublicaciones" class="container mb-5 d-flex flex-wrap justify-content-center gap-4">
    </section>

    <div class="foro">
        <div id="scrollLoader" class="loader-container">
            <div class="orbit">
                <div class="heart">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 
                   2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09
                   C13.09 3.81 14.76 3 16.5 3 
                   19.58 3 22 5.42 22 8.5
                   c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.btn-toggle-comments', function() {
                const id = $(this).data('id');
                const $commentsSection = $('#comments-' + id);

                if ($commentsSection.is(':visible')) {
                    $commentsSection.slideUp(200, function() {
                        $commentsSection.addClass('d-none');
                    });
                } else {
                    $commentsSection.removeClass('d-none').hide().slideDown(200);
                }
            });
        });
    </script>
    <script src="<?= $urlBase ?>peticiones(js)/return.js"></script>
    <script src="<?= $urlBase ?>peticiones(js)/scroll_infinito.js"></script>
</body>

</html>