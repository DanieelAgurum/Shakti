<?php
$vista = $_POST['vista'] ?? 'desconocida';
$duracion = floatval($_POST['tiempo_estancia'] ?? 0);
$nombreFinal = ucfirst(str_replace('.php', '', $vista));

$conexion = new mysqli("localhost", "root", "", "shakti");

if ($conexion->connect_error) {
    http_response_code(500);
    exit("Error de conexión: " . $conexion->connect_error);
}

$stmt = $conexion->prepare("INSERT INTO metricas (vista, tiempo_estancia) VALUES (?, ?)");
$stmt->bind_param("sd", $nombreFinal, $duracion);
$stmt->execute();
$stmt->close();
$conexion->close();

http_response_code(200);
?>
