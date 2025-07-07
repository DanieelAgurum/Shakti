<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/modelo/likeModelo.php';

if (!isset($_SESSION['id'])) {
    echo json_encode(['error' => 'Debes iniciar sesión para dar like.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_publicacion'])) {
    $id_usuaria = $_SESSION['id'];
    $id_publicacion = (int) $_POST['id_publicacion'];

    $modelo = new likeModelo();

    if ($modelo->usuarioYaDioLike($id_usuaria, $id_publicacion)) {
        $modelo->quitarLike($id_usuaria, $id_publicacion);
    } else {
        $modelo->darLike($id_usuaria, $id_publicacion);
    }

    $totalLikes = $modelo->contarLikes($id_publicacion);
    echo json_encode(['likes' => $totalLikes]);
} else {
    echo json_encode(['error' => 'Solicitud inválida.']);
}
