<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/modelo/Conexion.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    http_response_code(400);
    exit("ID requerido");
}

try {
    $db = (new ConectarDB())->open();
    $stmt = $db->prepare("SELECT foto FROM usuarias WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row || empty($row['foto'])) {
        header("Location: /Shakti/assets/img/default.png");
        exit;
    }

    header("Content-Type: image/jpeg"); // o image/png según el formato
    echo $row['foto'];
} catch (Exception $e) {
    http_response_code(500);
    echo "Error al cargar imagen";
}
