<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json; charset=utf-8');

require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Modelo/chatsMdl.php';

$chat = new chatsMdl();

if (isset($_GET['cargarChats'])) {
    $chat->cargarChats();
}

if (isset($_GET['cargarMensajes'])) {
    $idEmisor = $_GET['idEmisor'] ?? 0;
    $idReceptor = $_GET['idReceptor'] ?? 0;
    $chat->cargarMensajes($idEmisor, $idReceptor);
}

if (isset($_GET['enviarMensaje'])) {
    $mensaje = $_POST['mensaje'];
    $imagen = $_FILES['imagen'];
    $id_receptor = $_POST['id_receptor'];
    $chat->enviarMensaje($id_receptor, $mensaje, $imagen);
}

if (isset($_GET['enviarMensajeIanBot'])) {
    $input = json_decode(file_get_contents("php://input"), true);
    $mensaje = $input['mensaje'] ?? '';
    $chat->enviarMensajeIanBot($mensaje);
}

if (isset($_GET['cargarMensajesIanBot'])) {
    $chat->cargarMensajesIanBot();
}