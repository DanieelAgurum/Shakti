<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/modelo/PublicacionModelo.php';

$urlBase = '/Shakti/';
$publicacionModelo = new PublicacionModelo();


if (!isset($_SESSION['correo'])) {
    $_SESSION['mensaje'] = "Debes iniciar sesión.";
    header("Location: $urlBase/Vista/login.php");
    exit;
}

// GUARDAR PUBLICACIÓN
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar_publicacion'])) {
    $titulo = trim($_POST['titulo'] ?? '');
    $contenido = trim($_POST['contenido'] ?? '');
    $id_usuaria = intval($_POST['id_usuaria'] ?? 0); 

    if ($id_usuaria <= 0) {
        $_SESSION['mensaje'] = "Debes seleccionar una usuaria válida.";
    } elseif (strlen($titulo) < 3) {
        $_SESSION['mensaje'] = "El título debe tener al menos 3 caracteres.";
    } elseif (strlen($contenido) < 5) {
        $_SESSION['mensaje'] = "El contenido debe tener al menos 5 caracteres.";
    } else {
        $guardado = $publicacionModelo->guardar($titulo, $contenido, $id_usuaria);
        $_SESSION['mensaje'] = $guardado ? "Publicación creada correctamente." : "Error al guardar la publicación.";
    }

    header("Location: $urlBase/Vista/admin/publicaciones.php");
    exit;
}


if (isset($_GET['borrar_id'])) {
    $id = intval($_GET['borrar_id']);
    $borrado = $publicacionModelo->borrarSinVerificar($id);
    $_SESSION['mensaje'] = $borrado ? "Publicación eliminada." : "Error al eliminar la publicación.";
    header("Location: $urlBase/Vista/admin/publicaciones.php");
    exit;
}

// EDITAR PUBLICACIÓN
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_publicacion'])) {
    $id = intval($_POST['id_publicacion']);
    $titulo = trim($_POST['titulo'] ?? '');
    $contenido = trim($_POST['contenido'] ?? '');

    if (strlen($titulo) < 3) {
        $_SESSION['mensaje'] = "El título debe tener al menos 3 caracteres.";
    } elseif (strlen($contenido) < 5) {
        $_SESSION['mensaje'] = "El contenido debe tener al menos 5 caracteres.";
    } else {
        $actualizado = $publicacionModelo->actualizar($id, $titulo, $contenido);
        $_SESSION['mensaje'] = $actualizado ? "Publicación actualizada." : "Error al actualizar.";
    }

    header("Location: $urlBase/Vista/admin/publicaciones.php");
    exit;
}

// Si llega sin acción específica
header("Location: $urlBase/Vista/admin/publicaciones.php");
exit;
