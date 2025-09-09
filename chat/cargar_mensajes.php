<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require 'conexion.php';

$id_emisor   = intval($_GET['id_emisor'] ?? 0);
$id_receptor = intval($_GET['id_receptor'] ?? 0);
$mensajes    = [];

if (!$id_emisor || !$id_receptor) {
    echo json_encode(['error' => 'Faltan parámetros emisor o receptor'], JSON_UNESCAPED_UNICODE);
    exit;
}

$sql = "SELECT 
            m.*, 
            u.nickname AS emisor_nombre
        FROM mensajes m
        LEFT JOIN usuarias u ON u.id = m.id_emisor
        WHERE 
            (m.id_emisor = ? AND m.id_receptor = ?)
            OR (m.id_emisor = ? AND m.id_receptor = ?)
        ORDER BY m.creado_en ASC, m.id ASC";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['error' => 'Error prepare mensajes: ' . $conn->error], JSON_UNESCAPED_UNICODE);
    exit;
}

$stmt->bind_param("iiii", $id_emisor, $id_receptor, $id_receptor, $id_emisor);
$stmt->execute();
$result = $stmt->get_result(); // ← Faltaba esto

if ($result->num_rows === 0) {
    echo json_encode([], JSON_UNESCAPED_UNICODE);
    exit;
}

$finfo = new finfo(FILEINFO_MIME_TYPE); 

while ($row = $result->fetch_assoc()) {
    $row['es_mensaje_yo'] = ($row['id_emisor'] == $id_emisor);

    if (!empty($row['archivo'])) {
        $mime   = $finfo->buffer($row['archivo']);
        $base64 = base64_encode($row['archivo']);
        if (str_starts_with($mime, 'image/')) {
            $row['tipo']      = 'imagen';
            $row['contenido'] = "data:$mime;base64,$base64";
        } else {
            $row['tipo']      = 'archivo';
            $row['contenido'] = "data:$mime;base64,$base64";
        }
        unset($row['archivo']);
    } else {
        $row['tipo']      = 'texto';
        $row['contenido'] = $row['mensaje'];
    }

    $mensajes[] = $row;
}

echo json_encode($mensajes, JSON_UNESCAPED_UNICODE);
exit;