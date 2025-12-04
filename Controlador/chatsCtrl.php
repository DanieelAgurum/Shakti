<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ============================================================
// Bloquear acceso directo desde navegador
// ============================================================
$host = $_SERVER['HTTP_HOST'] ?? '';
$referer = $_SERVER['HTTP_REFERER'] ?? '';

if (empty($referer) || !str_contains($referer, $host)) {
    header('HTTP/1.0 403 Forbidden');
    exit('Acceso denegado');
}
// ============================================================


header('Content-Type: application/json; charset=utf-8');
require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Modelo/chatsMdl.php';

$chat = new chatsMdl();
if (isset($_GET['cargarChats'])) {
    $especialista = $_GET['especialista'] ?? $_GET['especialista'] ?? null;
    $chat->cargarChats($especialista);
}

if (isset($_GET['cargarMensajes'])) {
    $idEmisor   = $_GET['idEmisor'] ?? 0;
    $idReceptor = $_GET['idReceptor'] ?? 0;
    $chat->cargarMensajes($idEmisor, $idReceptor);
}

if (isset($_GET['enviarMensaje'])) {
    $mensaje      = $_POST['mensaje'];
    $imagen       = $_FILES['imagen'];
    $id_receptor  = $_POST['id_receptor'];
    $chat->enviarMensaje($id_receptor, $mensaje, $imagen);
}

if (isset($_GET['enviarMensajeIanBot'])) {
    $input   = json_decode(file_get_contents("php://input"), true);
    $mensaje = $input['mensaje'] ?? '';
    $chat->enviarMensajeIanBot($mensaje);
}

if (isset($_GET['cargarMensajesIanBot'])) {
    $chat->cargarMensajesIanBot();
}
