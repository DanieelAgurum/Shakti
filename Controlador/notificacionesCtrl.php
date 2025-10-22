<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Modelo/notificacionesModelo.php';

$id_usuaria = $_SESSION['id_usuaria'] ?? null;

if (!$id_usuaria) {
    http_response_code(403);
    echo json_encode(['error' => 'No autenticado']);
    exit;
}

try {
    $pdo = new PDO("mysql:host=localhost;dbname=shakti;charset=utf8mb4", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    $stmt = $pdo->prepare("SELECT notificar_publicaciones, notificar_comentarios FROM configuraciones WHERE id_usuaria = ?");
    $stmt->execute([$id_usuaria]);
    $config = $stmt->fetch() ?: ['notificar_publicaciones' => 1];
} catch (PDOException $e) {
    error_log("Error al obtener configuración de notificaciones: " . $e->getMessage());
    $config = ['notificar_publicaciones' => 1];
}

if (isset($_GET['marcarLeidas'])) {
    Notificacion::marcarTodasComoLeidas($id_usuaria);
    echo json_encode(['status' => 'ok']);
    exit;
}

$notificaciones = Notificacion::obtenerParaUsuaria($id_usuaria);
$noLeidas = array_values(array_filter($notificaciones, fn($n) => $n['leida'] == 0));

header('Content-Type: application/json; charset=utf-8');
echo json_encode([
    'config' => $config,
    'notificaciones' => $noLeidas
]);
