<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Controlador/api_key.php';
require 'pusher_config.php';

class chatsMdl
{
    private $con;
    private $clave_secreta;
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->clave_secreta = hash('sha256', ($_SESSION['id'] ?? '') . 'xN7$wA9!tP3@zLq6VbE2#mF8jR1&yC5Q');
    }
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
    public function cargarChats($Especialista = null)
    {
        $id_usuaria = $_SESSION['id'] ?? null;

        if (!$id_usuaria) {
            echo json_encode(['error' => 'No hay sesión iniciada'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $con = $this->conectarBD();

        if ($Especialista) {
            // 🔹 Si se pasa un especialista cifrado (por ejemplo, desde la vista)
            $idEspecialista = $this->descifrarAESChatEspecialista($Especialista);

            if (empty($idEspecialista)) {
                echo json_encode([
                    'success' => false,
                    'mensaje' => 'Error al descifrar ID de especialista o ID vacío.'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            $sql = "SELECT u.id, u.nickname, u.foto
                FROM usuarias u
                WHERE u.id = ?
                  AND u.id_rol = 2
                  AND u.estatus = 1
                LIMIT 1";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("i", $idEspecialista);
        } else {
            // 🔹 Solo los usuarios con los que ya haya mensajes (chat activo)
            $sql = "SELECT 
                    u.id, 
                    u.nickname, 
                    u.foto, 
                    MAX(m.creado_en) AS ultimo_mensaje
                FROM mensajes m
                INNER JOIN usuarias u 
                    ON (m.id_emisor = u.id OR m.id_receptor = u.id)
                WHERE (m.id_emisor = ? OR m.id_receptor = ?)
                  AND u.id != ?
                GROUP BY u.id, u.nickname, u.foto
                ORDER BY MAX(m.creado_en) DESC
                LIMIT 1";

            $stmt = $con->prepare($sql);
            $stmt->bind_param("iii", $id_usuaria, $id_usuaria, $id_usuaria);
        }

        $stmt->execute();
        $resultado = $stmt->get_result();
        $chats = [];

        while ($row = $resultado->fetch_assoc()) {
            $chats[] = [
                'id' => $row['id'],
                'nickname' => $row['nickname'],
                'ultimo_mensaje' => $row['ultimo_mensaje'] ?? null,
                'foto' => $row['foto']
                    ? 'data:image/jpeg;base64,' . base64_encode($row['foto'])
                    : "https://cdn1.iconfinder.com/data/icons/avatar-3/512/Secretary-512.png"
            ];
        }

        echo json_encode([
            'success' => true,
            'data' => $chats
        ], JSON_UNESCAPED_UNICODE);

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
    public function enviarMensajeIanBot($mensaje)
    {
        $id_usuario = $_SESSION['id'] ?? null;
        $mensaje = $this->cifrarAESIanBot($mensaje);

        // ✅ Validar sesión activa
        if (!$id_usuario) {
            echo json_encode(["respuesta" => "⚠️ No hay sesión iniciada."]);
            return;
        }

        // ✅ Validar mensaje recibido
        $mensaje = trim($mensaje ?? '');
        if ($mensaje === '') {
            echo json_encode(["respuesta" => "⚠️ No se recibió ningún mensaje."]);
            return;
        }

        // === Conexión BD ===
        $con = $this->conectarBD();

        // ✅ Guardar mensaje del usuario
        $sqlUsuario = "INSERT INTO mensajes (id_emisor, id_receptor, mensaje, creado_en) VALUES (?, 0, ?, NOW())";
        $stmt = $con->prepare($sqlUsuario);
        $stmt->bind_param("is", $id_usuario, $mensaje);
        $stmt->execute();
        $stmt->close();

        $mensaje = $this->descifrarAESIanBot($mensaje);
        // === Recuperar historial existente de la BD ===
        $historial = $this->obtenerHistorialIanBot($con, $id_usuario);

        // Agregar mensaje del usuario al historial
        $historial[] = [
            "rol" => "usuario",
            "contenido" => htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8')
        ];

        // === Contexto base del bot ===
        $promptBase = <<<EOT
Eres IAn Bot, un asistente digital de acompañamiento emocional preventivo diseñado para hombres adultos entre 18 y 60 años.

🎯 Tu función es escuchar, apoyar y orientar de manera empática, ayudando a los usuarios a:
- Expresar cómo se sienten sin juicios.
- Identificar emociones básicas (estrés, ansiedad, tristeza, enojo, etc.).
- Ofrecer recomendaciones prácticas y cotidianas (ejercicios de respiración, técnicas de relajación, 
  consejos simples de autocuidado).
- Motivar con un tono amigable, empático y claro, solo cuando el contexto lo amerite.

⚠️ Limitaciones:
- No eres sustituto de atención psicológica profesional.
- No das diagnósticos médicos ni psicológicos.
- No das recetas médicas, tareas escolares, traducciones, ni información técnica o financiera.
- Si el usuario expresa pensamientos de daño hacia sí mismo u otros, responde con un mensaje breve de 
  contención y redirige hacia ayuda profesional inmediata.
- Si el usuario pide ayuda en temas de idiomas, tareas escolares, programación, finanzas, recetas, tecnología u 
  otros fuera de tu propósito, **responde con una frase breve como: ‘Entiendo lo que me pides, pero no 
  estoy autorizado para eso. Prefiero enfocarme en cómo te sientes tú’. Luego redirige la conversación con una 
  pregunta cálida hacia su estado emocional.
- Todo lo que compartas conmigo es confidencial y no será juzgado. Mi propósito es que te sientas en un 
  espacio seguro para expresarte.

💬 Estilo de comunicación:
- Usa frases cálidas, comprensibles y breves.
- Valida la emoción del usuario sin exagerar.
- No repitas constantemente frases de compañía (“siempre estoy aquí para ti”), úsalas solo en momentos clave.
- Haz preguntas indirectas y suaves para conocer mejor al usuario (nombre, edad, ocupación, intereses), pero de manera 
  escalonada y natural según el flujo de la conversación. Ejemplos:
  - “Por cierto, ¿cómo te llamas? Me gusta personalizar las charlas.”
  - “Me da curiosidad, ¿qué edad tienes? A veces la manera en que manejamos el estrés cambia según la etapa de la vida.”
  - “¿Y a qué te dedicas normalmente? El trabajo o los estudios suelen influir mucho en cómo nos sentimos.”
  - “Cuando tienes un rato libre, ¿qué es lo que más disfrutas hacer?”
- Alterna entre validar emociones y dejar caer alguna de estas preguntas sin forzar el tema.
- Usa las respuestas del usuario para personalizar consejos posteriores (ejemplo: si estudia → sugerir 
  pausas de estudio; si trabaja en oficina → recomendar estiramientos).
- Mantén un tono confidencial y respetuoso.
- Si el usuario guarda silencio, responde con una frase cálida que invite a expresarse sin presión, como: 
  “Está bien si no quieres hablar mucho ahora, ¿quieres que te comparta una idea simple para relajarte?”

📌 Reglas de continuidad y personalización:
- Recuerda la información que el usuario comparta y úsala de forma natural para dar continuidad.
- Haz que la conversación fluya sin sonar mecánica ni forzar consejos.
- Las sugerencias deben ser simples y accionables (ejemplo: respirar hondo tres veces, salir a caminar 5 minutos,
  escribir lo que sientes).
- Si el usuario responde con cualquier mensaje afirmativo o breve (como “sí”, “claro”, “vale”, “ok”, “smn” o 
  cualquier abreviatura), interpreta su intención de manera positiva y **retoma inmediatamente la acción o 
  sugerencia ofrecida** sin preguntar de nuevo.
- Evita tecnicismos psicológicos complejos.
- Siempre que ofrezcas pasos prácticos o recomendaciones para manejar emociones (estrés, frustración, ansiedad, 
  tristeza, enojo).
- Usa un tono motivador cuando el usuario muestre cansancio, frustración o duda, pero sin exagerar ni dar falsas promesas.

📌 Excepción importante:
- Si el usuario solicita repetir listas o consejos relacionados con bienestar o manejo del estrés, el bot debe hacerlo respetando su estilo empático y cálido, sin activar la limitación anterior.

✅ Meta: Que el usuario se sienta acompañado y comprendido, descubriendo pequeños pasos para cuidar su bienestar.
EOT;

        // === Crear prompt unificado ===
        $historialTexto = "";
        foreach ($historial as $linea) {
            $historialTexto .= ucfirst($linea['rol']) . ": " . $linea['contenido'] . "\n";
        }

        $promptFinal = $promptBase . "\n\n" . $historialTexto . "IAn Bot:";

        // === Llamada a OpenAI (respuesta del bot) ===
        $respuestaBot = $this->llamarOpenAI($promptFinal);

        // ✅ Validar error en la respuesta del bot
        if (empty($respuestaBot) || stripos($respuestaBot, 'error') !== false) {
            echo json_encode(["respuesta" => "⚠️ Hubo un problema al procesar tu mensaje. Intenta nuevamente."]);
            $con->close();
            return;
        }

        // ✅ Evitar guardar respuestas vacías o nulas en la BD
        if (trim($respuestaBot) === '') {
            echo json_encode(["respuesta" => "⚠️ No se recibió una respuesta válida del bot."]);
            $con->close();
            return;
        }

        // === Guardar respuesta del bot cifrada también ===
        $respuestaCifrada = $this->cifrarAESIanBot($respuestaBot);

        $sqlBot = "INSERT INTO mensajes (id_emisor, id_receptor, mensaje, creado_en) VALUES (0, ?, ?, NOW())";
        $stmtBot = $con->prepare($sqlBot);
        $stmtBot->bind_param("is", $id_usuario, $respuestaCifrada);
        $stmtBot->execute();
        $stmtBot->close();

        // === Actualizar historial en sesión ===
        $_SESSION['historial'] = $historial;
        $_SESSION['historial'][] = ["rol" => "bot", "contenido" => $respuestaBot];

        $con->close();

        // === Devolver respuesta (HTML limpio, conservando listas) ===
        echo json_encode(["respuesta" => $this->formatearRespuestaHTML($respuestaBot)]);
    }

    /* ============================
   FUNCIONES AUXILIARES PRIVADAS
       =========================== */

    private function formatearRespuestaHTML($texto)
    {
        // 1. Convertir negritas estilo Markdown (**texto** o *texto*) a HTML
        $texto = preg_replace('/\*\*(.*?)\*\*/s', '<strong>$1</strong>', $texto);
        $texto = preg_replace('/\*(.*?)\*/s', '<b>$1</b>', $texto);

        // 2. Separar líneas para listas y párrafos
        $lineas = preg_split('/\r\n|\r|\n/', trim($texto));
        $html = "";
        $enLista = false;

        foreach ($lineas as $linea) {
            $linea = trim($linea);
            if ($linea === "") continue;

            // Detectar líneas tipo lista: empiezan con -, *, • o números
            if (preg_match('/^(?:[\-\*\•]|\d+[\.\)])\s*(.+)/u', $linea, $matches)) {
                if (!$enLista) {
                    $html .= "<ul>";
                    $enLista = true;
                }
                $html .= "<li>" . $matches[1] . "</li>";
            } else {
                if ($enLista) {
                    $html .= "</ul>";
                    $enLista = false;
                }
                $html .= "<p>" . $linea . "</p>";
            }
        }

        if ($enLista) $html .= "</ul>";

        return $html;
    }
    private function llamarOpenAI($prompt)
    {
        $apiKey = OPENAI_API_KEY;
        $modelo = "gpt-4.1-mini";

        $curl = curl_init("https://api.openai.com/v1/responses");
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $apiKey",
                "Content-Type: application/json"
            ],
            CURLOPT_POSTFIELDS => json_encode([
                "model" => $modelo,
                "input" => $prompt,
                "max_output_tokens" => 500,
                "temperature" => 0.6
            ])
        ]);

        $respuesta = curl_exec($curl);
        if (curl_errno($curl)) {
            $error = curl_error($curl);
            curl_close($curl);
            return "⚠️ Error al conectar con OpenAI: $error";
        }
        curl_close($curl);

        $data = json_decode($respuesta, true);
        return $data['output'][0]['content'][0]['text'] ?? "";
    }
    /** Cargar mensajes del usuario y la IA (historial) */
    public function cargarMensajesIanBot()
    {
        $idEmisor = $_SESSION['id'] ?? null;
        $idReceptor = 0;

        if (!$idEmisor) {
            echo json_encode(['data' => []]);
            return;
        }

        $con = $this->conectarBD();

        $sql = "SELECT mensaje, id_emisor, id_receptor, creado_en, archivo
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
            $esBot = ($row['id_emisor'] == 0);
            $mensaje = $this->descifrarAESIanBot(html_entity_decode($row['mensaje'], ENT_QUOTES, 'UTF-8'));

            if ($esBot) {
                $mensaje = $this->formatearRespuestaHTML($mensaje);
            }

            $mensajes[] = [
                'mensaje'       => $mensaje,
                'id_emisor'     => $row['id_emisor'],
                'id_receptor'   => $row['id_receptor'],
                'creado_en'     => $row['creado_en'],
                'es_mensaje_yo' => ($row['id_emisor'] == $idEmisor),
            ];
        }

        echo json_encode(['data' => $mensajes], JSON_UNESCAPED_UNICODE);

        $stmt->close();
        $con->close();
    }
    /** 🔎 Recuperar historial completo (usuario ↔ IA) */
    private function obtenerHistorialIanBot($con, $id_usuario)
    {
        $historial = [];
        $sql = "SELECT id_emisor, mensaje FROM mensajes
            WHERE (id_emisor IN (?, 0) AND id_receptor IN (?, 0))
            ORDER BY creado_en ASC";

        $stmt = $con->prepare($sql);
        $stmt->bind_param("ii", $id_usuario, $id_usuario);
        $stmt->execute();

        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $rol = ($row['id_emisor'] == 0) ? "bot" : "usuario";
            $contenido = $row['mensaje'];

            // ✅ Solo sanitizar mensajes del usuario, no los del bot
            if ($rol === "usuario") {
                $contenido = $this->descifrarAESIanBot($row['mensaje']);
            }

            $historial[] = [
                "rol" => $rol,
                "contenido" => $contenido
            ];
        }

        $stmt->close();
        return $historial;
    }
    // Cifrado y descifrado Aes
    private function cifrarAESIanBot($texto)
    {
        $ci = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $cifrado = openssl_encrypt($texto, 'aes-256-cbc', $this->clave_secreta, 0, $ci);
        return base64_encode($ci . $cifrado);
    }
    private function descifrarAESIanBot($textoCodificado)
    {
        if (empty($textoCodificado)) return '';
        $datos = base64_decode($textoCodificado, true);
        if ($datos === false) return $textoCodificado;

        $ci_length = openssl_cipher_iv_length('aes-256-cbc');
        $ci = substr($datos, 0, $ci_length);
        $cifrado = substr($datos, $ci_length);

        $descifrado = openssl_decrypt($cifrado, 'aes-256-cbc', $this->clave_secreta, 0, $ci);
        return $descifrado !== false ? $descifrado : $textoCodificado;
    }
    private function descifrarAESChatEspecialista($idCodificado)
    {
        if (empty($idCodificado)) return '';
        $datos = base64_decode($idCodificado, true);
        if ($datos === false) return $idCodificado;

        $ci_length = openssl_cipher_iv_length('aes-256-cbc');
        $ci = substr($datos, 0, $ci_length);
        $cifrado = substr($datos, $ci_length);

        $descifrado = openssl_decrypt($cifrado, 'aes-256-cbc', 'xN7$wA9!tP3@zLq6VbE2#mF8jR1&yC5Q', 0, $ci);
        return $descifrado !== false ? $descifrado : $idCodificado;
    }
}
