<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/obtenerLink/obtenerLink.php';
$urlBase = getBaseUrl();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Shakti</title>

    <link rel="stylesheet" href="<?= $urlBase ?>css/planes.css" />
    <link rel="stylesheet" href="<?= $urlBase ?>css/estiloscarrucel.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/usuaria/navbar.php'; ?>
</head>

<body>
    <main>
        <section class="container my-5">
            <h2 class="text-center mb-4 fw-bold text-purple">Glosario de Términos</h2>

            <div class="row row-cols-1 row-cols-md-2 g-4">
                <!-- Tarjeta 1 -->
                <div class="col">
                    <div class="card border-0 shadow-sm h-100 animate__animated animate__fadeInLeft">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-exclamation-diamond-fill text-danger me-2"></i>
                                Violencia de género
                            </h5>
                            <p class="card-text text-muted">
                                Es toda acción o conducta basada en el género que cause daño físico, sexual o psicológico a una persona, especialmente hacia las mujeres, por el hecho de serlo.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta 2 -->
                <div class="col">
                    <div class="card border-0 shadow-sm h-100 animate__animated animate__fadeInLeft">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-people-fill text-purple me-2"></i>
                                Sororidad
                            </h5>
                            <p class="card-text text-muted">
                                Solidaridad entre mujeres, basada en el reconocimiento de experiencias compartidas y el apoyo mutuo frente a las desigualdades de género.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Agrega más tarjetas aquí si es necesario -->
            </div>
        </section>
    </main>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/usuaria/footer.php'; ?>
</body>

</html>
