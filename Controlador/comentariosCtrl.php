<?php
date_default_timezone_set('America/Mexico_City');
session_start();
require_once("../Modelo/comentariosModelo.php");

header('Content-Type: application/json');

if (!isset($_SESSION['id_usuaria']) || !isset($_SESSION['nombre'])) {
    echo json_encode(['status' => 'error', 'message' => 'Únete a la comunidad para darle like y comentar']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['opcion'])) {
    $opcion = intval($_POST['opcion']);

    if ($opcion === 1) {
        $comentario = trim($_POST['comentario'] ?? '');
        $id_publicacion = intval($_POST['id_publicacion'] ?? 0);
        $id_padre = isset($_POST['id_padre']) && $_POST['id_padre'] !== '' ? intval($_POST['id_padre']) : null;
        $id_usuaria = $_SESSION['id_usuaria'];
        $nombre = $_SESSION['nombre'];

        if ($comentario && $id_publicacion > 0) {
            $modelo = new Comentario();
            $id_comentario = $modelo->agregarComentario($comentario, $id_publicacion, $id_usuaria, $id_padre);

            if ($id_comentario) {
                echo json_encode([
                    'status' => 'ok',
                    'id_comentario' => $id_comentario,
                    'nombre' => htmlspecialchars($nombre),
                    'comentario' => htmlspecialchars($comentario),
                    'fecha' => date('d M Y H:i'),
                    'id_padre' => $id_padre
                ]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No se pudo guardar el comentario']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Faltan datos']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Opción no válida']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No se recibió ninguna opción']);
}
