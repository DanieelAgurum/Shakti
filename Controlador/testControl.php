<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Modelo/TestModelo.php';
header('Content-Type: application/json; charset=utf-8');

$respuestas = $_POST['respuestas'] ?? [];

if (!$respuestas) {
    echo json_encode(["respuesta" => "No se recibieron respuestas"]);
    exit;
}

$model = new TestIanMdl();
$mensajeIA = $model->analizarTest($respuestas);

// Formateo simple para mostrar en HTML
$mensajeIA = nl2br(htmlspecialchars($mensajeIA));

echo json_encode(["respuesta" => $mensajeIA]);
