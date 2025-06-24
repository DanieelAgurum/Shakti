<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesion-Shakti</title>
    <?php
    include '../components/usuaria/estilos.php';
    ?>
</head>
<?php
require '../components/usuaria/navbar.php';
?>

<body class="auth-body">
    <div class="auth-container">
        <div class="auth-header">
            <img src="https://source.unsplash.com/random/80x80?logo" alt="Logo" />
            <h1>Bienvenido</h1>
        </div>
        <form class="auth-form">
            <label>Correo electrónico</label>
            <input type="email" placeholder="correo@dominio.com" required />
            <label>Contraseña</label>
            <input type="password" placeholder="••••••••" required />
            <button type="submit">Ingresar</button>
        </form>
        <div class="auth-footer">
            <a href="#">¿Olvidaste tu contraseña?</a>
            <a href="registro.php">¿No tienes una cuenta? Regístrate</a>
        </div>
    </div>
</body>

</html>