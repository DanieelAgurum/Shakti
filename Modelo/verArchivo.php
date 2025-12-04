<?php
if (!isset($_GET['id_contenido'])) {
    die("Parámetros faltantes.");
}

$id_contenido = intval($_GET['id_contenido']);

include_once 'conexion.php';
$database = new ConectarDB();
$db = $database->open();

$sql = "SELECT archivo FROM contenidos WHERE id_contenido = :id_contenido";
$stmt = $db->prepare($sql);
$stmt->bindParam(':id_contenido', $id_contenido, PDO::PARAM_INT);
$stmt->execute();

if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $contenido = $row['archivo'];
    if (!empty($contenido)) {
        header("Content-Type: application/pdf");
        header("Content-Disposition: inline; filename=archivo.pdf");
        echo $contenido;
        exit;
    } else {
        echo "El archivo está vacío.";
    }
} else {
    echo "archivo no encontrado.";
}
