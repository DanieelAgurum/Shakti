<?php
session_start();

if (!isset($_SESSION['id_usuaria'])) {
    echo json_encode([
        'status' => 'no-session',
        'message' => 'Únete a la comunidad para darle like y comentar'
    ]);
    exit;
}

include("../modelo/testimoniosMdl.php");
date_default_timezone_set('America/Mexico_City');

$temp = new Testimonios(null);
$db = $temp->conectarBD();
$testimonio = new Testimonios($db);

switch ($_REQUEST['opcion']) {
    case 1:
        $usuarioId = $_SESSION['id_usuaria'];
        $calificacion = $_REQUEST['calificacion'] ?? null;
        $opinion = $_REQUEST['opinion'] ?? '';

        if (!is_numeric($calificacion) || $calificacion < 1 || $calificacion > 5 || empty(trim($opinion))) {
            echo json_encode(['status' => 'error', 'message' => 'Datos inválidos.']);
            exit;
        }

        $resultado = $testimonio->guardarTestimonio($usuarioId, $calificacion, $opinion);

        if ($resultado) {
            echo json_encode(['status' => 'success', 'message' => 'Testimonio guardado correctamente']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se pudo guardar el testimonio']);
        }
        break;

    case 2:
        $testimonios = $testimonio->obtenerTestimonios();
        echo json_encode($testimonios);
        break;

    case 3:
        $id = $_REQUEST['id'];
        $testimonioData = $testimonio->obtenerTestimonioPorId($id);
        echo json_encode($testimonioData);
        break;
}