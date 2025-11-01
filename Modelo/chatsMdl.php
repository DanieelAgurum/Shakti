<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Controlador/api_key.php';
define('CLAVE_SECRETA', 'xN7$wA9!tP3@zLq6VbE2#mF8jR1&yC5Q');
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
    public function cargarChats($especialista = null)
    {
        $id_usuaria = $_SESSION['id'] ?? null;

        if (!$id_usuaria) {
            echo json_encode(['error' => 'No hay sesión iniciada'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $con = $this->conectarBD();

        if ($especialista) {
            $idEspecialista = $this->descifrarAESChatEspecialista($especialista);

            if (empty($idEspecialista)) {
                echo json_encode([
                    'success' => false,
                    'mensaje' => 'Error al descifrar al especialista.'
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
                'mensaje'       => $this->descifrarAES($row['mensaje']),
                'id_emisor'     => $row['id_emisor'],
                'id_receptor'   => $row['id_receptor'],
                'creado_en'     => $row['creado_en'],
                'es_mensaje_yo' => ($row['id_emisor'] == $idEmisor),
                'tipo'          => $this->descifrarAES($row['archivo']) ? "imagen" : "texto",
                'contenido'     => $this->descifrarAES($row['archivo']) ?: null
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
            $mensaje = $this->cifrarAES(htmlspecialchars(trim($mensaje), ENT_QUOTES, 'UTF-8'));

            // Antidoxing

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
                $maxWidth = 1500;
                $maxHeight = 1500;
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
                    $imagenURL = $this->cifrarAES($protocolo . $dominio . "/shakti/uploads/mensajes/" . $nombreArchivo);
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
                'mensaje'     => $this->descifrarAES($mensaje),
                'tipo'        => $this->descifrarAES($imagenURL) ? "imagen" : "texto",
                'contenido'   => $this->descifrarAES($imagenURL),
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
        $nombre_usuario = $_SESSION['nombre'] ?? 'usuario';

        if (!$id_usuario) {
            echo json_encode(["respuesta" => "⚠️ No hay sesión iniciada."]);
            return;
        }

        $mensajeOriginal = trim($mensaje ?? '');
        if ($mensajeOriginal === '') {
            echo json_encode(["respuesta" => "⚠️ No se recibió ningún mensaje."]);
            return;
        }

        $con = $this->conectarBD();

        // BLOQUE 1: Guardar mensaje del usuario
        $mensajeCifrado = $this->cifrarAESIanBot($mensajeOriginal);
        $sqlUsuario = "INSERT INTO mensajes (id_emisor, id_receptor, mensaje, creado_en) VALUES (?, 0, ?, NOW())";
        $stmt = $con->prepare($sqlUsuario);
        $stmt->bind_param("is", $id_usuario, $mensajeCifrado);
        $stmt->execute();
        $stmt->close();

        // BLOQUE 2: Obtener historial de conversación
        $historial = $this->obtenerHistorialIanBot($con, $id_usuario);
        $historial[] = ["rol" => "usuario", "contenido" => htmlspecialchars($mensajeOriginal, ENT_QUOTES, 'UTF-8')];

        $historialTexto = "";
        foreach ($historial as $linea) {
            $historialTexto .= ucfirst($linea['rol']) . ": " . $linea['contenido'] . "\n";
        }

        // BLOQUE 3: Prompt base con reglas de recomendación condicional
        $promptBase = <<<EOT
    Eres IAn Bot, un asistente digital de acompañamiento emocional preventivo diseñado para hombres adultos entre 18 y 60 años.

    Actualmente estás hablando con {$nombre_usuario}. Tu meta es escuchar, apoyar y orientar de manera empática.
    💡 Instrucción clave:
    No inventes nombres, servicios ni centros. Solo muestra lo que devuelvan las funciones internas `recomendarCentros()` y `recomendarEspecialistas()`.
    
    🎯 Tu función es escuchar, apoyar y orientar de manera empática, ayudando a los usuarios a:
    - Expresar cómo se sienten sin juicios.
    - Identificar emociones básicas (estrés, ansiedad, tristeza, enojo, etc.).
    - Ofrecer recomendaciones prácticas y cotidianas (ejercicios de respiración, técnicas de relajación, consejos simples de autocuidado).
    - Motivar con un tono amigable, empático y claro, solo cuando el contexto lo amerite.

    ⚠️ Limitaciones absolutas:
    - No eres sustituto de atención psicológica profesional.
    - No das diagnósticos médicos ni psicológicos.
    - No das recetas médicas, tareas escolares, traducciones, explicaciones técnicas, ni información sobre programación, código, 
      bases de datos, inglés, economía, finanzas, ciencia, tecnología ni ningún otro tema que no esté directamente relacionado con 
      la salud emocional o el bienestar personal.
    - Si el usuario pregunta o menciona algo técnico (por ejemplo: código, funciones, SQL, PHP, programación, IA, EOT, errores, tokens, etc.), 
      **ignora completamente el tema**. No respondas, no expliques, no aclares, no digas que no puedes, simplemente **redirige la 
      conversación con calidez hacia el estado emocional del usuario**, por ejemplo:
      👉 “Parece que estás muy enfocado en eso. Pero antes de seguir, ¿cómo te has sentido últimamente?”
    - No uses términos técnicos, ni menciones código, ni comentes sobre sistemas o bases de datos, incluso si el usuario los menciona.
    
    💬 Estilo de comunicación:
    - Usa frases cálidas, comprensibles y breves.
    - Valida la emoción del usuario sin exagerar.
    - Haz preguntas suaves para conocer mejor su estado emocional o cotidiano, de manera natural según el flujo.
    - Alterna entre validar emociones y preguntar con tacto.
    - Usa las respuestas del usuario para personalizar consejos posteriores.
    - Mantén un tono confidencial, empático y humano.

    📌 Reglas de continuidad y personalización:
    - Recuerda la información emocional o personal que el usuario comparta y úsala de forma natural.
    - Las sugerencias deben ser simples y accionables (respirar hondo, caminar, escribir lo que sientes).
    - Usa un tono motivador cuando el usuario muestre cansancio, frustración o duda, sin exagerar.

    🧱 Regla de bloqueo total:
    Si el mensaje del usuario contiene fragmentos de código, palabras como "function", "php", "sql", "SELECT", "database", "EOT", "token", 
      "API", "server", o cualquier otra palabra técnica o símbolo de programación (por ejemplo { }, ;, $, <, >), 
       NO DEBES RESPONDER NADA SOBRE EL CONTENIDO, 
       ni siquiera de forma empática.
       Ignora completamente el texto y redirige la conversación suavemente hacia el bienestar emocional del usuario, con una frase como:
       👉 “Entiendo que estás ocupado con eso, pero antes de seguir, ¿cómo te has sentido tú últimamente?”

    🚫 En resumen:
    Solo responde mensajes relacionados con emociones, estados de ánimo o bienestar. 
    Ignora por completo todo lo demás, incluso si el texto está mal escrito o confuso.

    ✅ Meta: Que {$nombre_usuario} se sienta comprendido, acompañado y emocionalmente escuchado.
    EOT;

        $promptFinal = $promptBase . "\n\n" . $historialTexto . "IAn Bot:";
        $respuestaBot = $this->llamarOpenAI($promptFinal);

        if (empty($respuestaBot) || stripos($respuestaBot, 'error') !== false) {
            echo json_encode(["respuesta" => "⚠️ Error en la comunicación con el bot."]);
            $con->close();
            return;
        }

        // BLOQUE 4: Analizar si se debe recomendar ayuda
        $totalMensajes = count($historial);
        $requiereAnalisis = false;

        if ($totalMensajes % 10 === 0) {
            $requiereAnalisis = true;
        }

        if (preg_match('/\b(ayuda|auxilio|ya no puedo|quiero morir|me siento mal|necesito hablar)\b/i', $mensajeOriginal)) {
            $requiereAnalisis = true;
        }

        // Evaluar riesgo emocional
        $riesgo = $this->analizarRiesgo($historialTexto);
        if ($riesgo === "ALTO") {
            $requiereAnalisis = true;
        }

        // Solo recomendar si el usuario pidió ayuda o el riesgo es alto
        if ($requiereAnalisis && stripos($mensajeOriginal, 'si') !== false) {
            $recomendacion = $this->recomendarCentros($con, $historialTexto);
            $recomendacion .= $this->recomendarEspecialistas($con, $historialTexto);
            $respuestaBot .= "\n\n" . $recomendacion;
        }

        // BLOQUE 5: Guardar respuesta del bot
        $respuestaCifrada = $this->cifrarAESIanBot($respuestaBot);
        $stmtBot = $con->prepare("INSERT INTO mensajes (id_emisor, id_receptor, mensaje, creado_en) VALUES (0, ?, ?, NOW())");
        $stmtBot->bind_param("is", $id_usuario, $respuestaCifrada);
        $stmtBot->execute();
        $stmtBot->close();
        $con->close();

        echo json_encode(["respuesta" => $this->formatearRespuestaHTML($respuestaBot)]);
    }
    private function analizarRiesgo($texto)
    {
        // 🧠 Prompt especializado para análisis de riesgo emocional
        $prompt = <<<EOT
        Analiza la siguiente conversación entre un usuario y un asistente emocional.
        Evalúa si existe riesgo emocional alto (por ejemplo, ideación suicida, desesperanza extrema o deseos de autodaño).

        Responde SOLO con una palabra:
        - "ALTO" → si detectas riesgo emocional, desesperación, ideas suicidas o pensamientos autodestructivos.
        - "BAJO" → si el texto no sugiere riesgo ni pensamientos autodestructivos.

        Conversación:
        $texto
        EOT;

        // Enviar análisis al mismo modelo que usa el bot
        $resultado = strtoupper(trim($this->OpenAICorto($prompt)));

        // Validación final (por si el modelo devuelve texto adicional)
        if (strpos($resultado, 'ALTO') !== false) {
            return "ALTO";
        }
        return "BAJO";
    }
    private function recomendarCentros($con, $historialTexto)
    {
        // Análisis contextual para ver si es una situación grave o si el usuario pide ayuda
        $riesgo = $this->analizarRiesgo($historialTexto);

        // Si el riesgo es alto → mostrar organizaciones masculinas de apoyo emocional
        if ($riesgo === "ALTO") {
            $sql = "SELECT nombre, descripcion, domicilio, numero 
                FROM organizaciones 
                WHERE nombre LIKE '%Hombre%' 
                   OR nombre LIKE '%Mascul%' 
                   OR descripcion LIKE '%apoyo%' 
                   OR descripcion LIKE '%emocional%' 
                LIMIT 5";
        } else {
            // Si el riesgo no es alto, mostrar algunos centros generales de orientación emocional
            $sql = "SELECT nombre, descripcion, domicilio, numero 
                FROM organizaciones 
                WHERE descripcion LIKE '%psicol%' 
                   OR descripcion LIKE '%emocional%' 
                   OR descripcion LIKE '%bienestar%' 
                LIMIT 5";
        }

        $resultado = $con->query($sql);
        if (!$resultado || $resultado->num_rows === 0) {
            return "No se encontraron centros disponibles en este momento.";
        }

        $respuesta = "💚 Aquí tienes algunos centros y organizaciones que podrían ayudarte:<br><br>";

        while ($row = $resultado->fetch_assoc()) {
            $nombre = htmlspecialchars($row['nombre'], ENT_QUOTES, 'UTF-8');
            $desc = htmlspecialchars($row['descripcion'], ENT_QUOTES, 'UTF-8');
            $dom = htmlspecialchars($row['domicilio'] ?? 'Sin dirección registrada', ENT_QUOTES, 'UTF-8');
            $num = htmlspecialchars($row['numero'] ?? 'Sin número disponible', ENT_QUOTES, 'UTF-8');

            // Crear enlaces interactivos
            $linkTel = ($row['numero'])
                ? "<a href='tel:{$num}' style='color:#007bff; text-decoration:none;' title='Llamar a {$nombre}'>{$num}</a>"
                : 'Sin número disponible';

            $linkMap = ($row['domicilio'])
                ? "<a href='https://www.google.com/maps/search/?api=1&query=" . urlencode($row['domicilio']) . "' target='_blank' style='color:#007bff; text-decoration:none;' title='Ver en Google Maps'>{$dom}</a>"
                : 'Sin dirección registrada';

            $respuesta .= "🏢 <b>{$nombre}</b><br>"
                . "📍 {$linkMap}<br>"
                . "📞 {$linkTel}<br>"
                // . "🧠 {$desc}<br><br>"
            ;
        }

        return $respuesta;
    }
    private function recomendarEspecialistas($con, $historialTexto)
    {
        $servicios = [
            "Terapia",
            "Consulta psicológica",
            "Psicopedagogía",
            "Terapia de lenguaje",
            "Coaching personal",
            "Terapia familiar",
            "Terapia de pareja",
            "Terapia grupal",
            "Acompañamiento emocional",
            "Intervención en crisis",
            "Talleres de autoestima",
            "Atención a adicciones"
        ];

        // Determinar servicio recomendado
        $prompt = <<<EOT
A continuación tienes una conversación de apoyo emocional entre un usuario y un asistente. 
Tu tarea es identificar cuál de los siguientes servicios profesionales sería más útil para el usuario, 
basándote en su situación emocional o contexto.

Solo puedes responder con una de estas opciones:
- Terapia
- Consulta psicológica
- Psicopedagogía
- Terapia de lenguaje
- Coaching personal
- Terapia familiar
- Terapia de pareja
- Terapia grupal
- Acompañamiento emocional
- Intervención en crisis
- Talleres de autoestima
- Atención a adicciones

Conversación:
$historialTexto
EOT;

        $servicioDetectado = trim($this->OpenAICorto($prompt));
        if (!in_array($servicioDetectado, $servicios)) {
            $servicioDetectado = "Acompañamiento emocional";
        }

        // Obtener especialistas
        $sql = "SELECT u.id, u.nombre, u.apellidos, s.servicio
            FROM usuarias u
            LEFT JOIN servicios_especialistas s ON u.id = s.id_usuaria
            WHERE u.id_rol = 2 AND u.estatus = 1 AND s.servicio LIKE ?
            LIMIT 3";

        $stmt = $con->prepare($sql);
        $param = "%{$servicioDetectado}%";
        $stmt->bind_param("s", $param);
        $stmt->execute();
        $resultado = $stmt->get_result();

        // Si no hay coincidencias, mostrar especialistas generales
        if ($resultado->num_rows === 0) {
            $sql2 = "SELECT u.id, u.nombre, u.apellidos, s.servicio
                 FROM usuarias u
                 LEFT JOIN servicios_especialistas s ON u.id = s.id_usuaria
                 WHERE u.id_rol = 2 AND u.estatus = 1
                 ORDER BY RAND()
                 LIMIT 3";
            $resultado = $con->query($sql2);

            if (!$resultado || $resultado->num_rows === 0) {
                return "Por ahora no hay especialistas disponibles, pero puedo seguir acompañándote si lo deseas 💚";
            }
        }

        // Armar respuesta en HTML
        $respuesta = "Según lo que compartiste, parece que podría ayudarte un servicio de <b>{$servicioDetectado}</b>.<br><br>";
        $respuesta .= "Aquí tienes algunos especialistas disponibles:<br><br>";

        while ($row = $resultado->fetch_assoc()) {
            $id = $row['id'];
            $nombreCompleto = htmlspecialchars($row['nombre'] . " " . $row['apellidos'], ENT_QUOTES, 'UTF-8');
            $servicio = strip_tags($row['servicio'] ?? 'Apoyo emocional general');
            $servicio = htmlspecialchars($servicio, ENT_QUOTES, 'UTF-8');


            $idCifrado = $this->cifrarAESChatEspecialista($id);

            $respuesta .= <<<HTML
<div class="mb-3 p-2 border rounded">
    <p>{$nombreCompleto}</p>
    🧠 {$servicio}
    <a href="/shakti/Vista/chat?especialistas={$idCifrado}" class="btn btn-outline-primary mt-2">
        <i class="bi bi-envelope-paper-heart"></i> Mensaje
    </a>
</div>
HTML;
        }

        return $respuesta;
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
                "max_output_tokens" => 200,
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
    private function OpenAICorto($prompt)
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
                "max_output_tokens" => 5,
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
        $sql = "SELECT id_emisor, mensaje, creado_en 
        FROM mensajes 
        WHERE (id_emisor = ? OR id_receptor = ?) AND creado_en >= NOW() - INTERVAL 15 MINUTE 
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
    private function cifrarAESChatEspecialista($id)
    {
        $clave = hash('sha256', 'xN7$wA9!tP3@zLq6VbE2#mF8jR1&yC5Q', true);
        $ci = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $cifrado = openssl_encrypt($id, 'aes-256-cbc', $clave, 0, $ci);
        return strtr(base64_encode($ci . $cifrado), '+/=', '-_,');
    }

    private function descifrarAESChatEspecialista($idCodificado)
    {
        $clave = hash('sha256', 'xN7$wA9!tP3@zLq6VbE2#mF8jR1&yC5Q', true);
        $datos = base64_decode(strtr($idCodificado, '-_,', '+/='), true);
        if ($datos === false) return $idCodificado;

        $ci_length = openssl_cipher_iv_length('aes-256-cbc');
        $ci = substr($datos, 0, $ci_length);
        $cifrado = substr($datos, $ci_length);

        $descifrado = openssl_decrypt($cifrado, 'aes-256-cbc', $clave, 0, $ci);
        return $descifrado !== false ? $descifrado : $idCodificado;
    }

    private function cifrarAES($texto)
    {
        $ci = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $cifrado = openssl_encrypt($texto, 'aes-256-cbc', CLAVE_SECRETA, 0, $ci);
        return base64_encode($ci . $cifrado);
    }
    private function descifrarAES($textoCodificado)
    {
        $datos = base64_decode($textoCodificado);
        $ci_length = openssl_cipher_iv_length('aes-256-cbc');
        $ci = substr($datos, 0, $ci_length);
        $cifrado = substr($datos, $ci_length);
        return openssl_decrypt($cifrado, 'aes-256-cbc', CLAVE_SECRETA, 0, $ci);
    }
}