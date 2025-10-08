<?php
include($_SERVER['DOCUMENT_ROOT'] . "/shakti/Modelo/loginMdl.php");

// Crear instancia del modelo
$login = new loginMdln();

// Verificar qué acción se va a realizar
switch ($_POST['opcion'] ?? '') {
    case 1: // Iniciar sesión
        $correo = $_POST['correo'] ?? '';
        $contraseña = $_POST['contraseña'] ?? '';

        $login->inicializar($correo, $contraseña);
        $login->iniciarSesion(); // Este método ya hace todo: validación, verificación, redirección
        break;

    case 2: // Cerrar sesión
        $login->cerrarSesion();
        break;

    default:
        header("Location: ../Vista/login.php");
        exit;
}
?>
