<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/obtenerLink/obtenerLink.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/modelo/conexion.php';

$urlBase = getBaseUrl();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['correo']) || $_SESSION['id_rol'] == 2) {
    header("Location: {$urlBase}Vista/especialista/perfil.php");
    exit;
} else if (empty($_SESSION['correo']) || $_SESSION['id_rol'] == 3) {
    header("Location: {$urlBase}Vista/admin");
    exit;
} else if (empty($_SESSION['correo']) || $_SESSION['id_rol'] != 1) {
    header("Location: {$urlBase}index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Especialistas - Shakti</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="<?= $urlBase ?>css/animacionCarga.css" />
    <?php include '../../components/usuaria/navbar.php'; ?>
</head>

<body>

    <!-- Buscador -->
    <div class="search-wrapper w-100">
        <div class="search-box">
            <i class="bi bi-search search-icon"></i>
            <input type="text" class="form-control search-input" name="especialista" placeholder="Busca a un especialista...">
        </div>
    </div>

    <div class="foro">
        <div id="loaderInicio" class="loader-container">
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

    <div class="container mt-4">
        <div id="contenedor-especialistas" class="row">
            <div id="resultados" class="row"></div>
            <div class="mt-3">
                <ul id="paginacion" class="pagination justify-content-center"></ul>
            </div>
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="../../peticiones(js)/cargaEspecialistas.js"></script>
    <script src="../../peticiones(js)/especialistas.js"></script>
    <script src="<?= $urlBase ?>peticiones(js)/mandarMetricas.js.php?vista=<?= urlencode(basename($_SERVER['PHP_SELF'])) ?>"></script>
    <?php include '../../components/usuaria/footer.php'; ?>

</body>

</html>