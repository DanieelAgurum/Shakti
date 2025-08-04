<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/obtenerLink/obtenerLink.php';
$urlBase = getBaseUrl();
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

    <link rel="stylesheet" href="<?= $urlBase ?>css/planes.css" />
    <link rel="stylesheet" href="<?= $urlBase ?>css/estiloscarrucel.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/usuaria/navbar.php'; ?>
</head>

<body>
    <main>
        <section class="container my-5">
            <h2 class="text-center mb-4 fw-bold">Glosario de Términos</h2>
            <div class="row row-cols-1 row-cols-md-2 g-4">
                <?php
                require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Modelo/glosarioMdl.php';
                $glo = new GlosarioMdl();
                $glo->mostrarGlosario();
                ?>
            </div>
        </section>
    </main>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/usuaria/footer.php'; ?>
</body>

</html>