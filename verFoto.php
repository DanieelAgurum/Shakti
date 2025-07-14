<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/modelo/Conexion.php';

$id = $_GET['id'] ?? $_GET['id2'] ?? null;

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
        // Redirigir a imagen por defecto si no hay foto
        header("Location: /Shakti/img/usuario.jpg");
        exit;
    }

    $foto = $row['foto'];

    // Detectar MIME type real de la imagen binaria
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->buffer($foto);

    // Asegurarnos que el MIME sea tipo imagen
    if (!str_starts_with($mime, 'image/')) {
        // Si no es imagen, redirigir a default
        header("Location: /Shakti/assets/img/default.png");
        exit;
    }

    header("Content-Type: $mime");
    echo $foto;

} catch (Exception $e) {
    http_response_code(500);
    echo "Error al cargar imagen";
}
