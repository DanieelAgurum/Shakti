<?php
session_start();
include("../Modelo/contenidoMdl.php");

$c = new Contenido();
$c->conectarBD();

if (!isset($_REQUEST['opcion'])) {
    die("Opción no especificada.");
}

switch ($_REQUEST['opcion']) {

    case 1:
        $thumbnail = '';
        if (!empty($_FILES['thumbnail']['tmp_name'])) {
            $rutaDestino = "../uploads/thumbnails/";
            if (!file_exists($rutaDestino)) {
                mkdir($rutaDestino, 0777, true);
            }
            $nombre = uniqid() . "_" . basename($_FILES['thumbnail']['name']);
            $destino = rtrim($rutaDestino, '/') . '/' . $nombre;

            if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $destino)) {
                $thumbnail = $destino;
            }
        }

        $titulo = $_REQUEST['titulo'] ?? '';
        $descripcion = $_REQUEST['descripcion'] ?? '';
        $tipo = $_REQUEST['tipo'] ?? '';
        $estado = $_REQUEST['estado'] ?? 1;
        $categoria = $_REQUEST['categoria'] ?? '';

        // CAMPOS OPCIONALES
        $cuerpo_html = $_REQUEST['cuerpo_html'] ?? '';
        $url_contenido = $_REQUEST['url_contenido'] ?? '';
        $archivo = null;
        $imagen1 = null;
        $imagen2 = null;
        $imagen3 = null;

        switch ($tipo) {
            case 'infografia':
                if (!empty($_FILES['archivo']['tmp_name'])) {
                    $archivo = file_get_contents($_FILES['archivo']['tmp_name']);
                }
                break;

            case 'articulo':
                $cuerpo_html = $_REQUEST['cuerpo_html'] ?? '';
                if ($tipo === 'articulo') {
                    if (!empty($_FILES['imagen1']['tmp_name'])) $imagen1 = $_FILES['imagen1'];
                    if (!empty($_FILES['imagen2']['tmp_name'])) $imagen2 = $_FILES['imagen2'];
                    if (!empty($_FILES['imagen3']['tmp_name'])) $imagen3 = $_FILES['imagen3'];
                }
                break;

            case 'video':
                $url_contenido = $_REQUEST['url_contenido'] ?? '';
                break;
        }

        $c->inicializar(
            $titulo,
            $descripcion,
            $cuerpo_html,
            $tipo,
            $url_contenido,
            $archivo,
            $imagen1,
            $imagen2,
            $imagen3,
            $estado,
            $thumbnail,
            $categoria
        );

        $exito = $c->agregarContenido();

        header("Location: ../Vista/admin/contenido.php?status=" . ($exito ? "exito_agregar" : "error_agregar"));
        exit;

    case 2:

        $thumbnail = $_REQUEST['thumbnail_actual'] ?? '';
        if (!empty($_FILES['thumbnail']['tmp_name'])) {
            $rutaDestino = "../uploads/thumbnails/";
            if (!file_exists($rutaDestino)) {
                mkdir($rutaDestino, 0777, true);
            }
            $nombre = uniqid() . "_" . basename($_FILES['thumbnail']['name']);
            $destino = rtrim($rutaDestino, '/') . '/' . $nombre;

            if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $destino)) {
                $thumbnail = $destino;
            }
        }

        $titulo = $_REQUEST['titulo'] ?? '';
        $descripcion = $_REQUEST['descripcion'] ?? '';
        $tipo = $_REQUEST['tipo'] ?? '';
        $estado = $_REQUEST['estado'] ?? 1;
        $categoria = $_REQUEST['categoria'] ?? '';

        $cuerpo_html = $_REQUEST['cuerpo_html'] ?? '';
        $url_contenido = $_REQUEST['nueva_url_contenido'] ?? '';
        $archivo = null;
        $imagen1 = null;
        $imagen2 = null;
        $imagen3 = null;

        switch ($tipo) {
            case 'infografia':
                if (!empty($_FILES['nuevo_archivo']['tmp_name'])) {
                    // $archivo = file_get_contents($_FILES['nuevo_archivo']['tmp_name']);
                    $archivo = $_FILES['nuevo_archivo'];
                }
                break;

            case 'articulo':
                $cuerpo_html = $_REQUEST['cuerpo_html'] ?? '';
                if ($tipo === 'articulo') {
                    $imagen1 = !empty($_FILES['nueva_imagen1']['tmp_name']) ? $_FILES['nueva_imagen1'] : null;
                    $imagen2 = !empty($_FILES['nueva_imagen2']['tmp_name']) ? $_FILES['nueva_imagen2'] : null;
                    $imagen3 = !empty($_FILES['nueva_imagen3']['tmp_name']) ? $_FILES['nueva_imagen3'] : null;
                }
                break;

            case 'video':
                $url_contenido = $_REQUEST['nueva_url_contenido'] ?? '';
                break;
        }

        $exito = $c->editarContenido(
            $_REQUEST['id_contenido'],
            $_REQUEST['titulo'],
            $_REQUEST['descripcion'],
            $_REQUEST['cuerpo_html'] ?? '',
            $_REQUEST['nueva_url_contenido'] ?? '',
            $thumbnail,
            $imagen1,
            $imagen2,
            $imagen3,
            $_REQUEST['estado'] ?? 1,
            $categoria,
            $_FILES['nuevo_archivo'] ?? null
        );

        header("Location: ../Vista/admin/contenido.php?status=" . ($exito ? "exito_actualizar" : "error_actualizar"));
        exit;

    case 3:
        if (isset($_REQUEST['id_contenido'])) {
            $exito = $c->eliminarContenido($_REQUEST['id_contenido']);
            header("Location: ../Vista/admin/contenido.php?status=" . ($exito ? "eliminado" : "error_eliminar"));
        } else {
            header("Location: ../Vista/admin/contenido.php?status=error_eliminar");
        }
        exit;

    case 4:
        if (isset($_REQUEST['id_contenido'], $_REQUEST['nuevo_estado'])) {
            $exito = $c->cambiarEstado($_REQUEST['id_contenido'], $_REQUEST['nuevo_estado']);
            header("Location: ../Vista/admin/contenido.php?status=" . ($exito ? "estatus_actualizado" : "error_estatus"));
        } else {
            header("Location: ../Vista/admin/contenido.php?status=error_estatus");
        }
        exit;

    default:
        die("Opción no válida.");
}
