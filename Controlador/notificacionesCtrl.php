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

// Si se pide marcar todas como leídas
if (isset($_GET['marcarLeidas'])) {
    Notificacion::marcarTodasComoLeidas($id_usuaria);
    echo json_encode(['status' => 'ok']);
    exit;
}

// Si no, devolver notificaciones no leídas
$notificaciones = Notificacion::obtenerParaUsuaria($id_usuaria);
$noLeidas = array_values(array_filter($notificaciones, fn($n) => $n['leida'] == 0));

header('Content-Type: application/json; charset=utf-8');
echo json_encode($noLeidas);
