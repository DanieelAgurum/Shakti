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
    <link rel="stylesheet" href="<?= $urlBase ?>css/publicaciones.css" />
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?= $urlBase ?>peticiones(js)/mandarReporte.js"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="<?= $urlBase ?>peticiones(js)/likesContar.js"></script>
    <script src="<?= $urlBase ?>validacionRegistro/abrirComentarios.js"></script>
    <script src="<?= $urlBase ?>validacionRegistro/respuestas.js"></script>
    <script src="<?= $urlBase ?>peticiones(js)/mandarMetricas.js.php?vista=<?= urlencode(basename($_SERVER['PHP_SELF'])) ?>"></script>

    <?php
    include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/usuaria/navbar.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/Vista/modales/reportarPostUsuarias.php';
    ?>

</head>

<body class="bg-white text-black">

    <h2 class="text-center w-100 mt-3">Publicaciones</h2>
    <div class="search-wrapper w-100">
        <div class="search-box">
            <form method="GET">
                <i class="bi bi-search search-icon"></i>
                <input type="text" name="buscador" class="form-control search-input" placeholder="Buscar ..." value="<?= htmlspecialchars($_GET['buscador'] ?? '') ?>">
            </form>
        </div>
    </div>

    <section id="contenedorPublicaciones" class="container mb-5 d-flex flex-wrap justify-content-center gap-4">
        <?php
        require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/Controlador/buscadorForoCtrl.php';
        ?>
    </section>

    <!-- Script para scroll infinito -->
    <script>
        const limite = 10;
        let paginaActual = 1;
        let cargando = false;
        let noHayMas = false;

        function debounce(fn, delay) {
            let timer;
            return function(...args) {
                clearTimeout(timer);
                timer = setTimeout(() => fn.apply(this, args), delay);
            };
        }

        async function cargarMasPublicaciones(pagina) {
            if (cargando || noHayMas) return;
            cargando = true;
            const offset = (pagina - 1) * limite;

            try {
                const res = await fetch(`/shakti/controlador/buscadorForoCtrl.php?limit=${limite}&offset=${offset}`);
                if (!res.ok) throw new Error('Error en la petición');

                const data = await res.text();

                if (data.trim().length === 0) {
                    noHayMas = true;
                    window.removeEventListener('scroll', onScrollDebounced);
                    return;
                }

                document.querySelector('#contenedorPublicaciones').insertAdjacentHTML('beforeend', data);

                paginaActual++;
            } catch (error) {
                console.error('Error al cargar más publicaciones:', error);
            } finally {
                cargando = false;
            }
        }

        function onScroll() {
            const distanciaAlFondo = document.body.offsetHeight - (window.innerHeight + window.scrollY);
            if (distanciaAlFondo < 150) {
                cargarMasPublicaciones(paginaActual + 1);
            }
        }

        const onScrollDebounced = debounce(onScroll, 200);
        window.addEventListener('scroll', onScrollDebounced);
    </script>

</body>

</html>