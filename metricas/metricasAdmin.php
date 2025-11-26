<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/Modelo/conexion.php';

$db = new ConectarDB();
$conexion = $db->open();

$vista = $_POST['vista'] ?? 'desconocida';
$duracion = floatval($_POST['tiempo_estancia'] ?? 0);
$nombreFinal = ucfirst(str_replace('.php', '', $vista));

// Verificar conexión
if (!$conexion) {
    http_response_code(500);
    exit("Error: No se pudo conectar a la base de datos.");
}

try {
    $stmt = $conexion->prepare("INSERT INTO metricas (vista, tiempo_estancia) VALUES (:vista, :tiempo)");
    $stmt->bindParam(':vista', $nombreFinal, PDO::PARAM_STR);
    $stmt->bindParam(':tiempo', $duracion, PDO::PARAM_STR);
    $stmt->execute();

    http_response_code(200);
} catch (PDOException $e) {
    http_response_code(500);
    echo "Error al insertar en métricas: " . $e->getMessage();
}

$db->close();