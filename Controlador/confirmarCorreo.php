<?php
include_once '../Modelo/conexion.php';
include_once '../Modelo/confirmarCorreo.php';
include_once '../obtenerLink/obtenerLink.php';

$correoObj = new confirmarCorreo();
$urlBase = getBaseUrl();

if (isset($_POST['correo']) && filter_var($_POST['correo'], FILTER_VALIDATE_EMAIL)) {
    $correo = $_POST['correo'];

    // Conectar a la base de datos
    $con = mysqli_connect("localhost", "root", "", "shakti");
    if (!$con) {
        header("Location: {$urlBase}/Vista/confirmarCorreo.php?status=error&message=" . urlencode("Error de conexión a la base de datos"));
        exit;
    }

    // Verificar si el correo existe
    $stmt = $con->prepare("SELECT id, nombre, correo FROM usuarias WHERE correo=? LIMIT 1");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $usuario = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    mysqli_close($con);

    if ($usuario) {
        // Si existe, enviar correo de confirmación
        $correoObj->inicializar($correo, $urlBase);
        $resultado = $correoObj->enviarCorreoConfirmacion();

        if ($resultado['success']) {
            header("Location: {$urlBase}/Vista/login.php?status=success&message=" . urlencode("Correo de confirmación enviado, revisa tu bandeja"));
            exit;
        } else {
            header("Location: {$urlBase}/Vista/login.php?status=error&message=" . urlencode("Error al enviar el correo: " . $resultado['error']));
            exit;
        }
    } else {
        // Si no existe, mostrar error
        header("Location: {$urlBase}/Vista/login.php?status=error&message=" . urlencode("El correo ingresado no está registrado"));
        exit;
    }

} else {
    header("Location: {$urlBase}/Vista/login.php?status=error&message=" . urlencode("Correo inválido"));
    exit;
}
?>
