<?php
session_start();

if (!isset($_SESSION['id_usuaria'])) {
    http_response_code(401);
    echo json_encode(['error' => 'sin sesion']);
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
        $calificacion = $_REQUEST['calificacion'];
        $opinion = $_REQUEST['opinion'] ?? '';
        $testimonio->guardarTestimonio($usuarioId, $calificacion, $opinion);
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
