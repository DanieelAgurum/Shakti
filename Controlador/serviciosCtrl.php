<?php
require_once '../Modelo/serviciosMdl.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idUsuaria = $_POST['id_usuaria'] ?? null;
    $servicios = $_POST['servicios'] ?? [];

    if ($idUsuaria && !empty($servicios)) {
        $modelo = new ServiciosMdl();

        $serviciosConcat = implode(', ', $servicios);

        if ($modelo->agregarServicio($idUsuaria, $serviciosConcat)) {
            header('Location: ../Vista/especialista/perfil?status=success&message=' . urlencode('Servicios guardados correctamente'));
            exit;
        } else {
            header('Location: ../Vista/especialista/perfil?status=error&message=' . urlencode('Error al guardar los servicios'));
            exit;
        }
    } else {
        header('Location: ../Vista/especialista/perfil?status=error&message=' . urlencode('No se registro ningún servicio'));
        exit;
    }
}
