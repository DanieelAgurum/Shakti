<?php
include("../Modelo/completarPerfil.php");

$e = new Completar();
$e->conectarBD();

switch ($_REQUEST['opcion']) {
    
    case 1:
        $nuevoEstado = $_POST['nuevo_estado'] ?? 0;
        $exito = $e->cambiarEstatusCuenta($_POST['id'], $nuevoEstado);
        $status = $exito ? 'estatus_actualizado' : 'error_estatus';
        break;
    case 2:
        $exito = $e->eliminarCuenta($_REQUEST['id']);
        $status = $exito ? 'eliminada' : 'error_eliminar';
        break;

    default:
        $status = 'opcion_invalida';
        break;
}

header("Location: ../Vista/admin/especialistas.php?status={$status}");
exit;
