<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/modelo/Conexion.php';

header('Content-Type: text/plain');

$input = file_get_contents("php://input");
$data = json_decode($input, true);

$ids = $data['ids'] ?? [];
if (!is_array($ids) || empty($ids)) {
    echo "";
    exit;
}

$ids = array_filter($ids, fn($id) => is_numeric($id));
if (empty($ids)) {
    echo "";
    exit;
}

try {
    $db = (new ConectarDB())->open();
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $sql = "SELECT id, nombre, descripcion FROM usuarias WHERE id IN ($placeholders)";
    $stmt = $db->prepare($sql);
    $stmt->execute($ids);

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($result as $row) {
        $id = $row['id'];
        $nombre = limpiar($row['nombre']);
        $descripcion = limpiar($row['descripcion']);

        // Ruta para ver la foto desde ver_foto.php
        $fotoUrl = "/Shakti/controlador/ver_foto.php?id={$id}";

        echo "{$id}|{$nombre}|{$descripcion}|{$fotoUrl}\n";
    }
} catch (Exception $e) {
    http_response_code(500);
    echo "ERROR: " . $e->getMessage();
}

function limpiar($cadena) {
    if (!is_string($cadena)) return '';
    $cadena = str_replace(["\n", "\r", "|"], ' ', $cadena);
    return trim($cadena);
}
