<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

require 'conexion.php';
require 'pusher_config.php';

$id_emisor   = $_POST['id_usuaria'] ?? null;
$id_receptor = $_POST['id_receptor'] ?? null;
$mensaje     = trim($_POST['mensaje'] ?? '');

if (!$id_emisor || !$id_receptor) {
    echo json_encode(['error' => 'Faltan datos esenciales']);
    exit;
}

if (
    empty($mensaje) &&
    (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK)
) {
    echo json_encode(['error' => 'Debe enviar un mensaje o un archivo']);
    exit;
}

$archivoBlob = null;
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['imagen']['tmp_name'];
    $tipoMime = "imagen";
    $archivoBlob = file_get_contents($fileTmpPath);
}

$sqlInsert = "INSERT INTO mensajes (id_emisor, id_receptor, mensaje, archivo, creado_en) VALUES (?, ?, ?, ?, NOW())";
$stmtInsert = $conn->prepare($sqlInsert);
if (!$stmtInsert) {
    echo json_encode(['error' => 'Error prepare insertar mensaje: ' . $conn->error]);
    exit;
}

$null = null;
if ($archivoBlob !== null) {
    $stmtInsert->bind_param("iiss", $id_emisor, $id_receptor, $mensaje, $archivoBlob);
    $stmtInsert->send_long_data(3, $archivoBlob);
} else {
    $stmtInsert->bind_param("iiss", $id_emisor, $id_receptor, $mensaje, $null);
}

if (!$stmtInsert->execute()) {
    echo json_encode(['error' => 'Error al guardar mensaje: ' . $stmtInsert->error]);
    exit;
}

$id_mensaje = $stmtInsert->insert_id;

// 🔹 Datos mínimos
$respuesta = [
    'id_mensaje'  => $id_mensaje,
    'id_emisor'   => $id_emisor,
    'id_receptor' => $id_receptor,
];

// 📡 Notificar a Pusher
$canal = 'chat-' . min($id_emisor, $id_receptor) . '-' . max($id_emisor, $id_receptor);
$pusher->trigger($canal, 'nuevo-mensaje', $respuesta);

// 📡 Devolver al emisor lo mismo
echo json_encode($respuesta);
exit;
