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

    <!-- Cards -->
    <?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/modelo/especialistaModelo.php';
    $esp = new EspecialistaModelo();
    $esp->mostrarEspecialistas();
    ?>


    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="../../peticiones(js)/especialistas.js"></script>
    <script src="<?= $urlBase ?>peticiones(js)/mandarMetricas.js.php?vista=<?= urlencode(basename($_SERVER['PHP_SELF'])) ?>"></script>
    <?php include '../../components/usuaria/footer.php'; ?>

</body>

</html>