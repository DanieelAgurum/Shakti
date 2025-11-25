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
    <title>Foro - NexoH</title>

    <!-- Estilos CSS -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="<?= $urlBase ?>css/animacionCarga.css" />
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
    <?php
    include $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/usuaria/navbar.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/shakti/Vista/modales/reportarPostUsuarias.php';
    ?>
    <link rel="stylesheet" href="<?= $urlBase ?>css/foro.css" />
    <script src="<?= $urlBase ?>peticiones(js)/abrirNotificacion.js"></script>
</head>

<body class="foro_main text-black">
    <div class="contenedor-buscador mt-3">
        <div class="search-foro buscador-fijo mx-auto">
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
            </div>
        </div>
    </div>

    <section id="contenedorPublicaciones" class="container mt-3 d-flex flex-wrap justify-content-center gap-4">
    </section>

    <div class="foro">
        <div id="scrollLoader" class="loader-container">
            <div class="orbit"></div>
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