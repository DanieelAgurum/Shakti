<?php
parse_str(file_get_contents("php://input"), $data);

$vista = $data['vista'] ?? 'desconocida';
$duracion = floatval($data['tiempo_estancia'] ?? 0);

$conexion = new mysqli("localhost", "root", "", "shakti");

if ($conexion->connect_error) {
    http_response_code(500);
    exit("Error de conexión: " . $conexion->connect_error);
}

$stmt = $conexion->prepare("INSERT INTO metricas (vista, tiempo_estancia) VALUES (?, ?)");
$stmt->bind_param("sd", $vista, $duracion);
$stmt->execute();
$stmt->close();
$conexion->close();

http_response_code(200);
?>
