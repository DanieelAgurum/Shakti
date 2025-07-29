<?php
if (!isset($_GET['id_legal'])) {
    die("Parámetros faltantes.");
}

$id_legal = intval($_GET['id_legal']);

include_once 'conexion.php';
$database = new ConectarDB();
$db = $database->open();

$sql = "SELECT documento FROM legales WHERE id_legal = :id_legal";
$stmt = $db->prepare($sql);
$stmt->bindParam(':id_legal', $id_legal, PDO::PARAM_INT);
$stmt->execute();

if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $contenido = $row['documento'];
    if (!empty($contenido)) {
        header("Content-Type: application/pdf");
        header("Content-Disposition: inline; filename=documento.pdf");
        echo $contenido;
        exit;
    } else {
        echo "El documento está vacío.";
    }
} else {
    echo "Documento no encontrado.";
}
