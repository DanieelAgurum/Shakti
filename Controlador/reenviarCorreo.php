<?php
header('Content-Type: application/json');

require_once '../Modelo/ConfirmarCorreo.php';
include '../obtenerLink/obtenerLink.php';

$correo = $_POST['correo'] ?? '';

if (!empty($correo)) {
    $urlBase = getBaseUrl();

    // Conectar para obtener nombre e id del usuario
    $con = mysqli_connect("localhost", "root", "", "shakti");
    if (!$con) {
        echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos']);
        exit;
    }

    $correoEscaped = mysqli_real_escape_string($con, $correo);
    $sql = "SELECT id, nombre FROM usuarias WHERE correo = '$correoEscaped' LIMIT 1";
    $result = mysqli_query($con, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $usuario = mysqli_fetch_assoc($result);
        $id_usuaria = $usuario['id'];
        $nombre = $usuario['nombre'];

        // Inicializar la clase con todos los parámetros requeridos
        $confirmar = new ConfirmarCorreo();
        $confirmar->inicializar($correo, $nombre, $urlBase, $id_usuaria);

        // Enviar correo
        if ($confirmar->enviarCorreoVerificacion()) {
            echo json_encode(['success' => true, 'message' => 'Correo de verificación reenviado correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudo enviar el correo.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Correo no registrado']);
    }

    mysqli_close($con);
} else {
    echo json_encode(['success' => false, 'message' => 'Correo no proporcionado']);
}
