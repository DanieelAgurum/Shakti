<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_GET['id']) || !isset($_GET['doc'])) {
    die("Parámetros faltantes.");
}

$id = intval($_GET['id']);
$doc = intval($_GET['doc']);

if ($doc < 0 || $doc > 4) {
    die("Documento no válido.");
}

include_once 'conexion.php';
$database = new ConectarDB();
$db = $database->open();

$columnas = ['id_oficial', 'documento1', 'documento2', 'documento3', 'documento4'];
$columna = $columnas[$doc];

$sql = "SELECT $columna FROM documentos WHERE id_usuaria = :id";
$stmt = $db->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();

if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $contenido = $row[$columna];
    if ($contenido) {
        header("Content-type: application/pdf");
        echo $contenido;
        exit;
    } else {
        echo "El documento está vacío.";
    }
} else {
    echo "Documento no encontrado.";
}
