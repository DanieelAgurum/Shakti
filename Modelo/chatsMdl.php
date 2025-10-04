<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'pusher_config.php';
class chatsMdl
{
    private $con;

    public function conectarBD()
    {
        if (!$this->con) {
            $this->con = new mysqli("localhost", "root", "", "shakti");
            if ($this->con->connect_error) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Error en la conexión a la base de datos: ' . $this->con->connect_error
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }
        }
        return $this->con;
    }

    public function cargarChats()
    {
        $id_usuaria = $_SESSION['id'] ?? null;

        if (!$id_usuaria) {
            echo json_encode(['error' => 'No hay sesión iniciada'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $con = $this->conectarBD();

        $sql = "SELECT u.id, u.nickname, u.foto, MAX(m.creado_en) AS ultimo_mensaje
FROM usuarias u
INNER JOIN amigos a
    ON (a.nickname_enviado = u.nickname OR a.nickname_amigo = u.nickname)
    AND a.estado = 'aceptado'
LEFT JOIN mensajes m
    ON ( (m.id_emisor = ? AND m.id_receptor = u.id) 
       OR (m.id_receptor = ? AND m.id_emisor = u.id) )
WHERE u.id != ?
GROUP BY u.id, u.nickname, u.foto
ORDER BY 
    CASE WHEN MAX(m.creado_en) IS NULL THEN 1 ELSE 0 END,
    MAX(m.creado_en) DESC,
    u.nickname ASC";

        $stmt = $con->prepare($sql);
        if (!$stmt) {
            echo json_encode(['error' => $con->error], JSON_UNESCAPED_UNICODE);
            return;
        }

        $stmt->bind_param("iii", $id_usuaria, $id_usuaria, $id_usuaria);

        if (!$stmt->execute()) {
            echo json_encode(['error' => $stmt->error], JSON_UNESCAPED_UNICODE);
            return;
        }

        // Variables donde se guardarán los resultados
        $id = $nickname = $foto = $ultimo_mensaje = null;
        $stmt->bind_result($id, $nickname, $foto, $ultimo_mensaje);

        $chats = [];
        while ($stmt->fetch()) {
            $chats[] = [
                'id' => $id,
                'nickname' => $nickname,
                'ultimo_mensaje' => $ultimo_mensaje,
                'foto' => $foto ? 'data:image/jpeg;base64,' . base64_encode($foto) : "https://cdn1.iconfinder.com/data/icons/avatar-3/512/Secretary-512.png"
            ];
        }


        // JSON de salida
        echo json_encode(['success' => true, 'data' => $chats], JSON_UNESCAPED_UNICODE);

        $stmt->close();
        $con->close();
    }

    public function cargarMensajes($idEmisor, $idReceptor)
    {
        $con = $this->conectarBD();

        $sql = "SELECT *
            FROM mensajes
            WHERE (id_emisor IN (?, ?) AND id_receptor IN (?, ?))
              AND id_emisor <> id_receptor
            ORDER BY creado_en ASC";

        $stmt = $con->prepare($sql);
        $stmt->bind_param("iiii", $idEmisor, $idReceptor, $idEmisor, $idReceptor);
        $stmt->execute();

        $result = $stmt->get_result();
        $mensajes = [];

        while ($row = $result->fetch_assoc()) {
            $mensajes[] = [
                'mensaje'       => $row['mensaje'],
                'id_emisor'     => $row['id_emisor'],
                'id_receptor'   => $row['id_receptor'],
                'creado_en'     => $row['creado_en'],
                'es_mensaje_yo' => ($row['id_emisor'] == $idEmisor),
                'tipo'          => $row['archivo'] ? "imagen" : "texto",
                'contenido'        => $row['archivo'] ?: null // Aquí ya es URL absoluta si existe
            ];
        }

        echo json_encode(['data' => $mensajes], JSON_UNESCAPED_UNICODE);

        $stmt->close();
        $con->close();
    }

    public function enviarMensaje($id_receptor, $mensaje, $imagen = null)
    {
        try {
            $id_emisor = $_SESSION['id'] ?? null;
            $nickname_emisor = $_SESSION['nickname'] ?? "usuario";

            if (!$id_emisor) {
                http_response_code(401);
                echo json_encode(['status' => 'error', 'mensaje' => 'Sesión no iniciada'], JSON_UNESCAPED_UNICODE);
                return;
            }

            // Escapar texto
            $mensaje = htmlspecialchars(trim($mensaje), ENT_QUOTES, 'UTF-8');

            // Crear carpeta si no existe
            $carpetaUploads = $_SERVER['DOCUMENT_ROOT'] . '/shakti/uploads/mensajes/';
            if (!is_dir($carpetaUploads)) {
                mkdir($carpetaUploads, 0777, true);
            }

            $imagenURL = null;

            // Procesar imagen si existe
            if ($imagen && isset($imagen['tmp_name']) && is_uploaded_file($imagen['tmp_name']) && $imagen['error'] === UPLOAD_ERR_OK) {
                $imgInfo = getimagesize($imagen['tmp_name']);
                if (!$imgInfo) {
                    throw new Exception("El archivo no es una imagen válida.");
                }

                $ext = strtolower(pathinfo($imagen['name'], PATHINFO_EXTENSION));
                $extPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                if (!in_array($ext, $extPermitidas)) {
                    $ext = "jpg";
                }

                $width = $imgInfo[0];
                $height = $imgInfo[1];
                $maxWidth = 1500;   // puedes ajustar
                $maxHeight = 1500;  // puedes ajustar
                $ratio = min($maxWidth / $width, $maxHeight / $height, 1);
                $newWidth = (int)($width * $ratio);
                $newHeight = (int)($height * $ratio);

                switch ($imgInfo['mime']) {
                    case 'image/jpeg':
                    case 'image/jpg':
                        $original = imagecreatefromjpeg($imagen['tmp_name']);
                        break;
                    case 'image/png':
                        $original = imagecreatefrompng($imagen['tmp_name']);
                        break;
                    case 'image/gif':
                        $original = imagecreatefromgif($imagen['tmp_name']);
                        break;
                    case 'image/webp':
                        $original = imagecreatefromwebp($imagen['tmp_name']);
                        break;
                    default:
                        throw new Exception("Formato de imagen no soportado.");
                }

                if ($original) {
                    $thumb = imagecreatetruecolor($newWidth, $newHeight);

                    // Fondo transparente para PNG/WebP
                    if (in_array($ext, ['png', 'webp'])) {
                        imagealphablending($thumb, false);
                        imagesavealpha($thumb, true);
                        $transparente = imagecolorallocatealpha($thumb, 0, 0, 0, 127);
                        imagefill($thumb, 0, 0, $transparente);
                    }

                    imagecopyresampled($thumb, $original, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

                    $fecha = date("YmdHis");
                    $nombreArchivo = "{$nickname_emisor}_{$fecha}_{$id_receptor}.{$ext}";
                    $rutaArchivo = $carpetaUploads . $nombreArchivo;

                    // 🔹 Verificar tamaño original (si > 20 MB, aplicar compresión fuerte)
                    $calidadAlta = 80;
                    $calidadBaja = 40; // fuerza más compresión

                    $pesoOriginal = $imagen['size']; // bytes
                    $calidadFinal = ($pesoOriginal > (20 * 1024 * 1024)) ? $calidadBaja : $calidadAlta;

                    switch ($ext) {
                        case 'png':
                            // en PNG el "quality" es al revés: 0 = sin compresión, 9 = máxima compresión
                            $nivel = ($pesoOriginal > (20 * 1024 * 1024)) ? 9 : 4;
                            imagepng($thumb, $rutaArchivo, $nivel);
                            break;
                        case 'gif':
                            imagegif($thumb, $rutaArchivo);
                            break;
                        case 'webp':
                            imagewebp($thumb, $rutaArchivo, $calidadFinal);
                            break;
                        default: // jpg
                            imagejpeg($thumb, $rutaArchivo, $calidadFinal);
                            break;
                    }

                    imagedestroy($original);
                    imagedestroy($thumb);

                    if (!file_exists($rutaArchivo)) {
                        throw new Exception("Error al guardar la imagen.");
                    }

                    // Crear URL absoluta
                    $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
                    $dominio = $_SERVER['HTTP_HOST'];
                    $imagenURL = $protocolo . $dominio . "/shakti/uploads/mensajes/" . $nombreArchivo;
                }
            }

            // Guardar mensaje en BD
            $sqlInsert = "INSERT INTO mensajes (id_emisor, id_receptor, mensaje, archivo, creado_en) 
                  VALUES (?, ?, ?, ?, NOW())";
            $stmtInsert = $this->conectarBD()->prepare($sqlInsert);

            if (!$stmtInsert) {
                throw new Exception("Error al preparar inserción: " . $this->conectarBD()->error);
            }

            $archivoParam = $imagenURL ?? null;
            $stmtInsert->bind_param("iiss", $id_emisor, $id_receptor, $mensaje, $archivoParam);

            if (!$stmtInsert->execute()) {
                throw new Exception("Error al guardar mensaje: " . $stmtInsert->error);
            }

            $respuesta = [
                'id_emisor'   => $id_emisor,
                'id_receptor' => $id_receptor,
                'mensaje'     => $mensaje,
                'tipo'        => $imagenURL ? "imagen" : "texto",
                'contenido'   => $imagenURL,
                'creado_en'   => date("Y-m-d H:i:s")
            ];

            // Notificar con Pusher
            global $pusher;
            if ($pusher) {
                $canal = 'chat-' . min($id_emisor, $id_receptor) . '-' . max($id_emisor, $id_receptor);
                $pusher->trigger($canal, 'nuevo-mensaje', $respuesta);
            }

            echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);

            $stmtInsert->close();
            $this->conectarBD()->close();
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "mensaje" => $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
    }
}

