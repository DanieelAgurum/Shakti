<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

include_once 'conexion.php';
$id_usuario = $_SESSION['id_usuaria'] ?? null;
$id_rol = $_SESSION['id_rol'] ?? null;

if (!$id_usuario) {
    echo json_encode(['error' => 'No logueado']);
    exit;
}

$chats = [];

if ($id_rol == 1) {
    // Todos los usuarios rol 2 con estatus=1, mostrando último mensaje si existe
    $sql = "SELECT 
    u.id AS id_amigo,
    u.nickname,
    u.foto,
    m.mensaje,
    m.archivo,
    m.creado_en
    FROM usuarias u
LEFT JOIN (
    -- Subconsulta para obtener la fecha del último mensaje por amigo
    SELECT ultimos.id_amigo, msj.mensaje, msj.archivo, msj.creado_en
    FROM (
        SELECT 
            CASE 
                WHEN id_emisor = ? THEN id_receptor
                ELSE id_emisor
            END AS id_amigo,
            MAX(creado_en) AS ultimo_creado
        FROM mensajes
        WHERE id_emisor = ? OR id_receptor = ?
        GROUP BY id_amigo
    ) ultimos
    INNER JOIN mensajes msj 
        ON (
            (msj.id_emisor = ? AND msj.id_receptor = ultimos.id_amigo)
            OR 
            (msj.id_receptor = ? AND msj.id_emisor = ultimos.id_amigo)
        )
        AND msj.creado_en = ultimos.ultimo_creado
) m ON u.id = m.id_amigo
WHERE u.id_rol = 2 
  AND u.estatus = 1
  AND u.id != ?
ORDER BY 
    m.creado_en IS NULL,
    m.creado_en DESC,   
    u.nickname";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiiiii", $id_usuario, $id_usuario, $id_usuario, $id_usuario, $id_usuario, $id_usuario);
} elseif ($id_rol == 2) {
    // Rol 2: solo usuarios con mensajes y rol 1, estatus=1
    $sql = "SELECT 
    u.id AS id_amigo,
    u.nickname,
    u.foto,
    m.id,
    m.mensaje,
    m.archivo,
    m.creado_en
FROM usuarias u
INNER JOIN (
    SELECT ultimos.id_amigo, msj.mensaje, msj.archivo, msj.creado_en
    FROM (
        SELECT 
            CASE 
                WHEN id_emisor = 21 THEN id_receptor
                ELSE id_emisor
            END AS id_amigo,
            MAX(creado_en) AS ultimo_creado
        FROM mensajes
        WHERE id_emisor = ? OR id_receptor = ?
        GROUP BY id_amigo
    ) ultimos
    INNER JOIN mensajes msj 
        ON (
            (msj.id_emisor = ? AND msj.id_receptor = ultimos.id_amigo)
            OR 
            (msj.id_receptor = ? AND msj.id_emisor = ultimos.id_amigo)
        )
        AND msj.creado_en = ultimos.ultimo_creado
) m ON u.id = m.id_amigo
WHERE u.id_rol = 1 
  AND u.id != ?
ORDER BY 
    m.creado_en DESC,   
    u.nickname";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiiii", $id_usuario, $id_usuario, $id_usuario, $id_usuario, $id_usuario);
} else {
    echo json_encode(['error' => 'Rol no válido']);
    exit;
}

$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    if ($row['foto']) {
        $row['foto'] = 'data:image/*;base64,' . base64_encode($row['foto']);
    } else {
        $row['foto'] = null;
    }

    if (!empty($row['archivo'])) {
        $ultimo = "[Archivo o imagen]";
    } elseif (!empty($row['mensaje'])) {
        $ultimo = $row['mensaje'];
    } else {
        $ultimo = "No hay mensajes";
    }

    $row['ultimo_mensaje'] = $ultimo;
    unset($row['mensaje'], $row['archivo']);

    $chats[] = $row;
}

echo json_encode($chats, JSON_UNESCAPED_UNICODE);
exit;
