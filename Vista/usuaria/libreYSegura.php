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
    <title>Libre y Segura</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?= $urlBase ?>peticiones(js)/mandarReporte.js"></script>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/usuaria/navbar.php'; ?>
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/Vista/modales/reportarLibreySegura.php'; ?>
    <script src="<?= $urlBase ?>peticiones(js)/mandarReporte.js"></script>
</head>

<body class="bg-white text-black">

    <div class="container mb-5">
        <h2 class="text-center w-100 mt-3">Libre y Segura</h2>
        <div class="search-wrapper w-100">
            <div class="search-box">
                <form method="GET">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" name="buscador" class="form-control search-input" placeholder="Buscar ..." value="<?= $_GET['buscador'] ?? '' ?>">
                </form>
            </div>
        </div>
        <div class="text-center text-muted fs-5 mb-4">
            <i class="bi bi-shield-check text-primary me-2"></i>
            Porque conocer la ley también es una forma de cuidarte. Aquí puedes consultar tus derechos, recursos legales y orientaciones para vivir con dignidad, libertad y seguridad.
        </div>

        <div class="row g-4">
            <div class="col-12 col-md-4">
                <div class="card card-custom animate__animated animate__fadeInLeft animate__slow animacion text-white">
                    <img src="https://tse1.mm.bing.net/th/id/OIP.4tfg8I67q3CueL5oCkv8KAHaE8?r=0&cb=thvnext&rs=1&pid=ImgDetMain&o=7&rm=3" class="card-img" alt="Imagen 1" />
                    <div class="card-img-overlay">
                        <h5 class="card-title title-content">Moda Urbana 2025</h5>
                        <p class="card-text text-content">Descubre las últimas tendencias en ropa casual con un toque fresco y moderno para esta temporada.</p>
                        <div class="card-date">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-2 .89-2 2v12a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6c0-1.11-.89-2-2-2zM5 20V9h14v11H5z" />
                            </svg>
                            Última actualización hace 3 min
                        </div>
                        <a href="#" class="btn btn-outline-light mt-3 read-more-btn">Leer más</a>
                    </div>
                </div>
            </div>

            <!-- Repite para las otras dos tarjetas -->
            <div class="col-12 col-md-4">
                <div class="card card-custom animate__animated animate__fadeInLeft animate__slow animacion text-white">
                    <img src="https://tse1.mm.bing.net/th/id/OIP.4tfg8I67q3CueL5oCkv8KAHaE8?r=0&cb=thvnext&rs=1&pid=ImgDetMain&o=7&rm=3" class="card-img" alt="Imagen 2" />
                    <div class="card-img-overlay">
                        <h5 class="card-title title-content">Tecnología y Estilo</h5>
                        <p class="card-text text-content">Cómo la tecnología está influyendo en el diseño de prendas con materiales inteligentes y sostenibles.</p>
                        <div class="card-date">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-2 .89-2 2v12a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6c0-1.11-.89-2-2-2zM5 20V9h14v11H5z" />
                            </svg>
                            Última actualización hace 5 min
                        </div>
                        <a href="#" class="btn btn-outline-light mt-3 read-more-btn">Leer más</a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card card-custom animate__animated animate__fadeInLeft animate__slow animacion text-white">
                    <img src="https://tse1.mm.bing.net/th/id/OIP.4tfg8I67q3CueL5oCkv8KAHaE8?r=0&cb=thvnext&rs=1&pid=ImgDetMain&o=7&rm=3" class="card-img" alt="Imagen 3" />
                    <div class="card-img-overlay">
                        <h5 class="card-title title-content">Consejos de Estilo</h5>
                        <p class="card-text text-content">Ideas para combinar colores y accesorios que realcen tu personalidad y estilo único.</p>
                        <div class="card-date">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-2 .89-2 2v12a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6c0-1.11-.89-2-2-2zM5 20V9h14v11H5z" />
                            </svg>
                            Última actualización hace 10 min
                        </div>
                        <a href="#" class="btn btn-outline-light mt-3 read-more-btn">Leer más</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/usuaria/footer.php'; ?>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="<?= $urlBase ?>peticiones(js)/mandarMetricas.js.php?vista=<?= urlencode(basename($_SERVER['PHP_SELF'])) ?>"></script>
</body>

</html>