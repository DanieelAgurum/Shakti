<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

require 'conexion.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$id) {
    echo json_encode(['error' => 'ID inválido']);
    exit;
}

$sql = "SELECT id, nickname, foto, id_rol FROM usuarias WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($usuario = $result->fetch_assoc()) {
    echo json_encode($usuario);
} else {
    echo json_encode(['error' => 'Usuario no encontrado']);
}
