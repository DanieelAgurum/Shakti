<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/obtenerLink/obtenerLink.php';
$urlBase = getBaseUrl();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['correo']) || $_SESSION['id_rol'] == 1) {
    header("Location: {$urlBase}Vista/usuaria/perfil.php");
    exit;
} else if (empty($_SESSION['correo']) || $_SESSION['id_rol'] == 2) {
    header("Location: {$urlBase}Vista/especialista/perfil.php");
    exit;
} else if (empty($_SESSION['correo']) || $_SESSION['id_rol'] != 3) {
    header("Location: {$urlBase}index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    include '../../components/admin/navbar.php';
    ?>
</body>

</html>