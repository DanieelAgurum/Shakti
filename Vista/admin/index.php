<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['correo']) || $_SESSION['id_rol'] != 3) {
    header("Location: ../../index.php");
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

    <h3 class="mt-4"> <?php echo (isset($_SESSION['nombre']) && isset($_SESSION['nombre_rol'])) ? $_SESSION['nombre_rol'] . " " . $_SESSION['nombre'] : " " ?></h3>
    <form action="../../Controlador/loginCtrl.php" method="post">
        <input type="hidden" name="opcion" value="2">
        <input type="submit" value="Cerrar Sesion">
    </form>
</body>

</html>