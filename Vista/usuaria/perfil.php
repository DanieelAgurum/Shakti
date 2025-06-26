<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 

if (empty($_SESSION['correo'])) {
    header("Location: ../../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> </title>
</head>

<body>
    <?php
    include '../../components/usuaria/navbar.php';
    ?>
    <main>
        <?php
        echo '<h1>Bienvenido ' . (isset($_SESSION['nombre_rol']) ? $_SESSION['nombre_rol'] : '') . " " . (isset($_SESSION['nombre']) ? $_SESSION['nombre'] : '') . '</h1>';
        ?>
    </main>
</body>

<form action="../../Controlador/loginCtrl.php" method="post">
    <input type="hidden" name="opcion" value="2">
    <input type="submit" value="Cerrar Sesion">
</form>

</html>