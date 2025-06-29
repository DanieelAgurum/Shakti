<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/modelo/PublicacionModelo.php';

$publicacionModelo = new PublicacionModelo();
$id_usuaria = $_SESSION['id_usuaria'] ?? null;

if (!$id_usuaria) {
    $_SESSION['mensaje'] = "Debes iniciar sesión para publicar.";
    header("Location: ../Vista/usuaria/publicaciones.php");
    exit;
}

// GUARDAR PUBLICACIÓN
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar_publicacion'])) {
    $titulo = trim($_POST['titulo'] ?? '');
    $contenido = trim($_POST['contenido'] ?? '');

    if ($contenido === '') {
        $_SESSION['mensaje'] = "El contenido no puede estar vacío.";
    } else {
        $guardado = $publicacionModelo->guardar($titulo, $contenido, $id_usuaria);
        $_SESSION['mensaje'] = $guardado ? "Publicación guardada con éxito." : "Error al guardar la publicación.";
    }

    header("Location: ../Vista/usuaria/publicaciones.php");
    exit;
}

// ELIMINAR PUBLICACIÓN
if (isset($_GET['borrar_id'])) {
    $id = intval($_GET['borrar_id']);
    $borrado = $publicacionModelo->borrar($id, $id_usuaria);
    $_SESSION['mensaje'] = $borrado ? "Publicación eliminada." : "Error al eliminar publicación.";
    header("Location: ../Vista/usuaria/publicaciones.php");
    exit;
}

// SI SE ACCEDE DIRECTO, REDIRIGIR A LA VISTA
header("Location: ../Vista/usuaria/publicaciones.php");
exit;
