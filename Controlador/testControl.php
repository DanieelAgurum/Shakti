<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Modelo/TestModelo.php';
header('Content-Type: application/json; charset=utf-8');

session_start();
$idUsuario = $_SESSION['id_usuaria'] ?? null;
if (!$idUsuario) {
    echo json_encode(["estado" => "error", "mensaje" => "Usuario no identificado."]);
    exit;
}

$respuestas = $_POST['respuestas'] ?? [];
if (empty($respuestas)) {
    echo json_encode(["estado" => "error", "mensaje" => "No se recibieron respuestas."]);
    exit;
}

$model = new TestIanMdl();
$mensajeIA = $model->analizarTest($respuestas, $idUsuario);

echo json_encode(["estado" => "ok", "mensaje" => $mensajeIA]);
