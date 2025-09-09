<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
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
$tipoMensaje = "";
$nombreArchivo = null;
$tipoMime = null;

if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['imagen']['tmp_name'];
    $archivoBlob = file_get_contents($fileTmpPath);
    $nombreArchivo = $_FILES['imagen']['name'];

    $tipoMime = mime_content_type($fileTmpPath);
    if (substr($tipoMime, 0, 6) === 'image/') {
        $tipoMensaje = '[Archivo o imagen]';
    } else {
        $tipoMensaje = '[Archivo o imagen]';
    }
} else {
    $tipoMensaje = $mensaje;
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

// Obtener contenido base64 para devolver en la respuesta si hay archivo
$contenidoBase64 = null;
if ($archivoBlob !== null) {
    $contenidoBase64 = 'data:' . $tipoMime . ';base64,' . base64_encode($archivoBlob);
}

// Enviar solo datos mínimos a Pusher para optimizar
$dataPusher = [
    'id_mensaje'  => $id_mensaje,
    'id_emisor'   => $id_emisor,
    'id_receptor' => $id_receptor,
];

$canal = 'chat-' . min($id_emisor, $id_receptor) . '-' . max($id_emisor, $id_receptor);
$pusher->trigger($canal, 'nuevo-mensaje', $dataPusher);

// Respuesta para emisor con datos completos para mostrar inmediatamente
echo json_encode([
    'id_mensaje'    => $id_mensaje,
    'id_emisor'     => $id_emisor,
    'id_receptor'   => $id_receptor,
    'tipo_mensaje' => $tipoMensaje,
    'es_mensaje_yo' => true,
    'creado_en'     => date('Y-m-d H:i:s'),
]);
exit;
