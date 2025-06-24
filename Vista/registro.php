<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro-Shakti</title>
    <?php
    include '../components/usuaria/estilos.php';
    ?>
</head>

<body class="auth-body">
    <?php
    require '../components/usuaria/navbar.php';
    ?>
    <div class="auth-container">
        <div class="auth-header">
            <img src="https://source.unsplash.com/random/80x80?logo" alt="Logo" />
            <h1>Crear cuenta</h1>
        </div>
        <form class="auth-form" enctype="multipart/form-data">
            <label>Nombre</label>
            <input type="text" placeholder="Roxana" required />
            <label>Apellido Paterno</label>
            <input type="text" placeholder="Pérez" required />
            <label>Apellido Materno</label>
            <input type="text" placeholder="López" required />
            <label>Nombre de usuario</label>
            <input type="text" placeholder="Roxrz10" required />
            <label>Correo electrónico</label>
            <input type="email" placeholder="correo@dominio.com" required />
            <label>Contraseña</label>
            <input type="password" placeholder="••••••••" required />
            <label>Confirmar contraseña</label>
            <input type="password" placeholder="••••••••" required />
            <label>Subir documento PDF (Para confirmar identidad)</label>
            <input type="file" accept="application/pdf" required />
            <label>Fecha de nacimiento</label>
            <input type="date" required />
            <button type="submit">Enviar</button>
        </form>
        <div class="auth-footer">
            <a href="login.html">¿Ya tienes una cuenta? Inicia sesión</a>
        </div>
    </div>
</body>

</html>