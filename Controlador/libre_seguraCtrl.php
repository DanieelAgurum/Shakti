<?php
session_start();
include("../Modelo/libre_seguraMdl.php");

$l = new Legales();
$l->conectarBD();

if (!isset($_REQUEST['opcion'])) {
    die("Opción no especificada.");
}

switch ($_REQUEST['opcion']) {
    case 1:
        if (
            isset($_FILES['portada']) &&
            isset($_REQUEST['titulo'], $_REQUEST['descripcion']) &&
            isset($_FILES['documento'])
        ) {
            $l->inicializar(
                $_FILES['portada'],
                $_REQUEST['titulo'],
                $_FILES['documento'],
                $_REQUEST['descripcion']

            );
            $exito = $l->agregar();
            header("Location: ../Vista/admin/libre_segura?status=" . ($exito ? "agregado" : "error_agregar"));
            exit;
        } else {
            header("Location: ../Vista/admin/libre_segura?status=error_agregar");
            exit;
        }

    case 2:
        if (
            isset($_FILES['portada']) &&
            isset($_REQUEST['id_legal'], $_REQUEST['titulo'], $_REQUEST['descripcion'],) &&
            isset($_FILES['documento'])
        ) {
            $exito = $l->actualizar(
                $_REQUEST['id_legal'],
                $_FILES['portada'],
                $_REQUEST['titulo'],
                $_FILES['documento'],
                $_REQUEST['descripcion']

            );
            header("Location: ../Vista/admin/libre_segura?status=" . ($exito ? "legal_actualizado" : "error_legal"));
            exit;
        } else {
            header("Location: ../Vista/admin/libre_segura?status=error_legal");
            exit;
        }


    case 3:
        if (isset($_REQUEST['id_legal'])) {
            $exito = $l->eliminar($_REQUEST['id_legal']);
            header("Location: ../Vista/admin/libre_segura?status=" . ($exito ? "eliminada" : "error_eliminar"));
            exit;
        } else {
            header("Location: ../Vista/admin/libre_segura?status=error_eliminar");
            exit;
        }

    default:
        die("Opción no válida.");
}
