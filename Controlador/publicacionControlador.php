<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Modelo/publicacionModelo.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Modelo/notificacionesModelo.php';

$publicacionModelo = new PublicacionModelo();
$id_usuaria = $_SESSION['id_usuaria'] ?? null;

$requiereSesion = ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['guardar_publicacion']) || isset($_POST['editar_publicacion']))) || isset($_GET['borrar_id']) || isset($_GET['leida_id']);

if ($requiereSesion && !$id_usuaria) {
    $_SESSION['mensaje'] = "Debes iniciar sesión para realizar esta acción.";
    header("Location: ../Vista/usuaria/publicaciones.php");
    exit;
}

// GUARDAR PUBLICACIÓN
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar_publicacion'])) {
    $titulo = trim($_POST['titulo'] ?? '');
    $contenido = trim($_POST['contenido'] ?? '');

    if ($contenido === '' || strlen($contenido) < 5) {
        $_SESSION['mensaje'] = "El contenido no puede estar vacío o tener menos de 5 caracteres.";
    } elseif (strlen($titulo) < 3) {
        $_SESSION['mensaje'] = "El título debe tener al menos 3 caracteres.";
    } else {

        // $guardado = $publicacionModelo->guardar($titulo, $contenido, $anonima, $id_usuaria);
        // if ($guardado) {
        //     // Crear notificaciones para otras usuarias
        //     Notificacion::crearDesdePublicacion($id_usuaria);
        //     $_SESSION['mensaje'] = "Publicación guardada con éxito.";
        // } else {
        //     $_SESSION['mensaje'] = "Error al guardar la publicación.";
        // }

        $guardado = $publicacionModelo->guardar($titulo, $contenido, $id_usuaria);

        if ($guardado) {
            $id_publicacion = $publicacionModelo->ultimoInsertId();
            if ($id_publicacion) {
                Notificacion::crearDesdePublicacion($id_usuaria, $id_publicacion);
            }
            $_SESSION['mensaje'] = "Publicación guardada con éxito.";
        } else {
            $_SESSION['mensaje'] = "Error al guardar la publicación.";
        }
    }

    header("Location: ../Vista/usuaria/publicaciones.php");
    exit;
}

// MARCAR NOTIFICACIÓN COMO LEÍDA
if (isset($_GET['leida_id'])) {
    $id_notificacion = intval($_GET['leida_id']);
    Notificacion::marcarComoLeida($id_notificacion);
    header("Location: ../Vista/usuaria/publicaciones.php");
    exit;
}

// ELIMINAR PUBLICACIÓN (AJAX)
if (isset($_POST['borrar_id'])) {
    $id = intval($_POST['borrar_id']);

    $publicacion = $publicacionModelo->obtenerPorId($id);
    if ($publicacion && $publicacion['id_usuarias'] == $id_usuaria) {
        $borrado = $publicacionModelo->borrar($id, $id_usuaria);
        if ($borrado) {
            echo json_encode(["status" => "ok", "message" => "Publicación eliminada."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error al eliminar la publicación."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "No tienes permiso para eliminar esta publicación."]);
    }
    exit;
}

// EDITAR PUBLICACIÓN
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

// CONSULTA DE PUBLICACIONES
// if (isset($_GET['buscador'])) {
//     $buscar = $_GET['buscador'];
//     $publicacionModelo->inicializar($buscar);
//     $publicacionModelo->buscar($id_usuaria);
// } else {
//     $publicacionModelo->todos($id_usuaria);
// }
