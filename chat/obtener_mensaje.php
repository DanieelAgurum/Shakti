<?php
require 'conexion.php';

$id_mensaje = $_GET['id'] ?? null;
if (!$id_mensaje) {
    echo json_encode(['error' => 'Falta ID']);
    exit;
}

$sql = "SELECT id, id_emisor, id_receptor, mensaje, archivo, creado_en FROM mensajes WHERE id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['error' => 'Error prepare obtener mensaje: ' . $conn->error]);
    exit;
}
$stmt->bind_param("i", $id_mensaje);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    echo json_encode(['error' => 'Mensaje no encontrado']);
    exit;
}

$stmt->bind_result($id, $emisor, $receptor, $mensaje, $archivoBlob, $creado_en);
$stmt->fetch();

$contenidoBase64 = null;
$tipoMime = null;

if ($archivoBlob !== null) {
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $tipoMime = $finfo->buffer($archivoBlob);
    $contenidoBase64 = 'data:' . $tipoMime . ';base64,' . base64_encode($archivoBlob);
}

echo json_encode([
    'id_mensaje' => $id,
    'id_emisor' => $emisor,
    'id_receptor' => $receptor,
    'mensaje' => $mensaje,
    'contenido' => $contenidoBase64,
    'tipo' => $tipoMime ?? "texto", 
    'creado_en' => $creado_en,
]);
exit;

