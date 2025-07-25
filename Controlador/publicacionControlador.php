<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/modelo/PublicacionModelo.php';

$publicacionModelo = new PublicacionModelo();
$id_usuaria = $_SESSION['id_usuaria'] ?? null;

// ⚠️ Solo redirigimos si intenta guardar, editar o eliminar sin sesión
$requiereSesion = ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['guardar_publicacion']) || isset($_POST['editar_publicacion']))) || isset($_GET['borrar_id']);

if ($requiereSesion && !$id_usuaria) {
    $_SESSION['mensaje'] = "Debes iniciar sesión para realizar esta acción.";
    header("Location: ../Vista/usuaria/publicaciones.php");
    exit;
}

// 🟣 GUARDAR PUBLICACIÓN
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar_publicacion'])) {
    $titulo = trim($_POST['titulo'] ?? '');
    $contenido = trim($_POST['contenido'] ?? '');
    $anonima = isset($_POST['anonima']) ? '1' : '0';
    

    if ($contenido === '' || strlen($contenido) < 5) {
        $_SESSION['mensaje'] = "El contenido no puede estar vacío o tener menos de 5 caracteres.";
    } elseif (strlen($titulo) < 3) {
        $_SESSION['mensaje'] = "El título debe tener al menos 3 caracteres.";
    } else {
        $guardado = $publicacionModelo->guardar($titulo, $contenido, $anonima, $id_usuaria);
        $_SESSION['mensaje'] = $guardado ? "Publicación guardada con éxito." : "Error al guardar la publicación.";
    }

    header("Location: ../Vista/usuaria/publicaciones.php");
    exit;
}

// 🔴 ELIMINAR PUBLICACIÓN
if (isset($_GET['borrar_id'])) {
    $id = intval($_GET['borrar_id']);

    $publicacion = $publicacionModelo->obtenerPorId($id);
    if ($publicacion && $publicacion['id_usuarias'] == $id_usuaria) {
        $borrado = $publicacionModelo->borrar($id, $id_usuaria);
        $_SESSION['mensaje'] = $borrado ? "Publicación eliminada." : "Error al eliminar la publicación.";
    } else {
        $_SESSION['mensaje'] = "No tienes permiso para eliminar esta publicación.";
    }

    header("Location: ../Vista/usuaria/publicaciones.php");
    exit;
}

// ✏️ EDITAR PUBLICACIÓN
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_publicacion'])) {
    $id = intval($_POST['id_publicacion']);
    $titulo = trim($_POST['titulo'] ?? '');
    $contenido = trim($_POST['contenido'] ?? '');

    $publicacion = $publicacionModelo->obtenerPorId($id);
    if (!$publicacion || $publicacion['id_usuarias'] != $id_usuaria) {
        $_SESSION['mensaje'] = "No tienes permiso para editar esta publicación.";
    } elseif ($contenido === '' || strlen($contenido) < 5) {
        $_SESSION['mensaje'] = "El contenido no puede estar vacío o tener menos de 5 caracteres.";
    } elseif (strlen($titulo) < 3) {
        $_SESSION['mensaje'] = "El título debe tener al menos 3 caracteres.";
    } else {
        $actualizado = $publicacionModelo->actualizar($id, $titulo, $contenido);
        $_SESSION['mensaje'] = $actualizado ? "Publicación actualizada con éxito." : "Error al actualizar la publicación.";
    }

    header("Location: ../Vista/usuaria/publicaciones.php");
    exit;
}

// 🔍 CONSULTAS (AJAX o vistas)
if (isset($_GET['buscador'])) {
    $buscar = $_GET['buscador'];
    $publicacionModelo->inicializar($buscar);
    $publicacionModelo->buscar($id_usuaria); // importante: pasar el id_usuaria
} else {
    $publicacionModelo->todos($id_usuaria); // importante: pasar el id_usuaria
}
