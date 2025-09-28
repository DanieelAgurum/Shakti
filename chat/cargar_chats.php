<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

include_once 'conexion.php';

$miId = $_SESSION['id'] ?? null; 
$miNickname = $_SESSION['nickname'] ?? null;

if (!$miId || !$miNickname) {
    echo json_encode(['error' => 'No logueado']);
    exit;
}

$chats = [];

$sql = "SELECT 
    u.id AS id_amigo,
    u.nickname,
    u.foto
FROM usuarias u
INNER JOIN amigos a 
    ON (
        (a.nickname_enviado = ? AND a.nickname_amigo = u.nickname) 
        OR 
        (a.nickname_amigo = ? AND a.nickname_enviado = u.nickname)
    )
WHERE a.estado = 'aceptado'
  AND u.id != ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi", $miNickname, $miNickname, $miId);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    if ($row['foto']) {
        $row['foto'] = 'data:image/*;base64,' . base64_encode($row['foto']);
    } else {
        $row['foto'] = null;
    }
    
    $chats[] = $row;
}

echo json_encode($chats, JSON_UNESCAPED_UNICODE);
exit;