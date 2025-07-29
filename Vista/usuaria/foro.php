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
    <?php
    include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/usuaria/navbar.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/Vista/modales/reportarPostUsuarias.php';
    ?>
    <script>
        $(document).on('click', '.btn-toggle-comments', function() {
            const id = $(this).data('id');
            $('#comments-' + id).toggleClass('d-none');
        });
    </script>
</head>
<script>
    let idPublicacionCompartir = null;

    function setIdCompartir(id) {
        idPublicacionCompartir = id;
    }

    async function generarLinkSeguro(idPublicacion) {
        const datos = `${idPublicacion}`;
        const hash = await sha256(datos);
        return `${window.location.origin}/Shakti/Vista/usuaria/foro.php?publicacion=${hash}`;
    }

    async function sha256(mensaje) {
        const encoder = new TextEncoder();
        const data = encoder.encode(mensaje);
        const hashBuffer = await crypto.subtle.digest('SHA-256', data);
        const hashArray = Array.from(new Uint8Array(hashBuffer));
        return hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
    }

    async function compartirWhatsapp() {
        if (!idPublicacionCompartir) return;
        const url = await generarLinkSeguro(idPublicacionCompartir);
        const texto = encodeURIComponent("¡Mira esta publicación! 👉 " + url);
        window.open(`https://wa.me/?text=${texto}`, '_blank');
    }

    async function compartirFacebook() {
        if (!idPublicacionCompartir) return;
        const url = await generarLinkSeguro(idPublicacionCompartir);
        window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`, '_blank');
    }

    async function compartirTwitter() {
        if (!idPublicacionCompartir) return;
        const url = await generarLinkSeguro(idPublicacionCompartir);
        const texto = encodeURIComponent("Revisa esta publicación que encontré en Shakti:");
        window.open(`https://twitter.com/intent/tweet?url=${encodeURIComponent(url)}&text=${texto}`, '_blank');
    }
</script>




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

    <script>
        function compartirPublicacion(idPublicacion) {
            const enlace = `${window.location.origin}/Shakti/Vista/usuaria/foro.php?id=${idPublicacion}`;

            navigator.clipboard.writeText(enlace).then(() => {
                alert("¡Enlace copiado al portapapeles!");
            }).catch(err => {
                console.error("Error al copiar el enlace:", err);
                alert("Hubo un error al copiar el enlace.");
            });
        }
    </script>

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

    <script src="<?= $urlBase ?>peticiones(js)/scroll_infinito.js"></script>
</body>

</html>