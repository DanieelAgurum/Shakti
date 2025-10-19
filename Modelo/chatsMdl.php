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

        // ===================== BLOQUE 1: Guardar mensaje del usuario =====================
        $mensajeCifrado = $this->cifrarAESIanBot($mensajeOriginal);
        $sqlUsuario = "INSERT INTO mensajes (id_emisor, id_receptor, mensaje, creado_en) VALUES (?, 0, ?, NOW())";
        $stmt = $con->prepare($sqlUsuario);
        $stmt->bind_param("is", $id_usuario, $mensajeCifrado);
        $stmt->execute();
        $stmt->close();

        // ===================== BLOQUE 2: Obtener historial =====================
        $historial = $this->obtenerHistorialIanBot($con, $id_usuario);
        $historial[] = ["rol" => "usuario", "contenido" => htmlspecialchars($mensajeOriginal, ENT_QUOTES, 'UTF-8')];

        $historialTexto = "";
        foreach ($historial as $linea) {
            $historialTexto .= ucfirst($linea['rol']) . ": " . $linea['contenido'] . "\n";
        }

        // ===================== BLOQUE 3: Prompt completo del bot =====================
        $promptBase = <<<EOT
Eres IAn Bot, un asistente digital de acompañamiento emocional preventivo diseñado para hombres adultos entre 18 y 60 años.

Actualmente estás hablando con {$nombre_usuario}. Tu meta es escuchar, apoyar y orientar de manera empática.

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
- Haz preguntas indirectas y suaves para conocer mejor al usuario (nombre, edad, ocupación, intereses), pero de manera 
  escalonada y natural según el flujo de la conversación.
- Alterna entre validar emociones y dejar caer alguna de estas preguntas sin forzar el tema.
- Usa las respuestas del usuario para personalizar consejos posteriores.
- Mantén un tono confidencial y respetuoso.

📌 Reglas de continuidad y personalización:
- Recuerda la información que el usuario comparta y úsala de forma natural para dar continuidad.
- Las sugerencias deben ser simples y accionables (respirar hondo, caminar, escribir lo que sientes).
- Usa un tono motivador cuando el usuario muestre cansancio, frustración o duda, sin exagerar.

✅ Meta: Que {$nombre_usuario} se sienta comprendido y acompañado emocionalmente.
EOT;

        $promptFinal = $promptBase . "\n\n" . $historialTexto . "IAn Bot:";
        $respuestaBot = $this->llamarOpenAI($promptFinal);

        if (empty($respuestaBot) || stripos($respuestaBot, 'error') !== false) {
            echo json_encode(["respuesta" => "⚠️ Error en la comunicación con el bot."]);
            $con->close();
            return;
        }

        // ===================== BLOQUE 4: Evaluar necesidad de ayuda profesional =====================
        $promptRiesgo = "Analiza la siguiente conversación y responde solo con 'SI' o 'NO' si necesita atención profesional inmediata:\n$historialTexto";
        $pideAyudaRaw = $this->llamarOpenAI($promptRiesgo);
        $pideAyuda = strtoupper(trim($pideAyudaRaw ?? "NO"));

        // ===================== BLOQUE 5: Buscar centros de ayuda si es necesario =====================
        if ($pideAyuda === "SI") {
            $direccion = null;
            $filtroMunicipio = null;

            // Detectar dirección automáticamente en historial
            foreach ($historial as $h) {
                $d = $this->detectarDireccion($h['contenido']);
                if ($d) {
                    $direccion = $d;
                    break;
                }
            }

            // Extraer municipio/ciudad/estado si el usuario lo proporciona
            foreach ($historial as $h) {
                if (preg_match('/\b(en|dentro de|ciudad de|municipio de)\s+([\w\s]+)/i', $h['contenido'], $m)) {
                    $filtroMunicipio = trim($m[2]);
                    break;
                }
            }

            // Si no hay dirección, usar la BD
            if (!$direccion) {
                $stmtDir = $con->prepare("SELECT direccion FROM usuarias WHERE id = ?");
                $stmtDir->bind_param("i", $id_usuario);
                $stmtDir->execute();
                $resDir = $stmtDir->get_result()->fetch_assoc();
                $direccion = $resDir['direccion'] ?? null;
                $stmtDir->close();
            }

            // Buscar centros con dirección detectada
            $centros = $this->buscarCentrosNominatim($direccion, $filtroMunicipio);

            if (empty($centros)) {
                $respuestaBot .= "\n\n📍 No pude ubicar tu dirección exactamente. ¿Podrías indicarme el municipio, ciudad o estado para ofrecerte centros de ayuda cercanos?";
            } elseif (count($centros) > 2) {
                // Más de 2 resultados: pedir al usuario que precise
                $respuestaBot .= "\n\n📍 Encontré varios posibles centros cerca de ti. ¿Podrías indicarme cuál colonia o referencia específica para mostrar la mejor opción?";
            } else {
                // 2 o menos resultados: usar el primero
                $respuestaBot .= "\n\n🏥 <b>Centro de ayuda cercano (fuente: OpenStreetMap):</b><br>";
                $c = $centros[0];
                $respuestaBot .= "• {$c['nombre']} — {$c['direccion']} — <a href='{$c['maps']}' target='_blank'>Ver en Maps</a> — Tel: {$c['telefono']}<br>";
            }
        }
        // ===================== BLOQUE 6: Guardar respuesta y retornar =====================
        $respuestaCifrada = $this->cifrarAESIanBot($respuestaBot);
        $stmtBot = $con->prepare("INSERT INTO mensajes (id_emisor, id_receptor, mensaje, creado_en) VALUES (0, ?, ?, NOW())");
        $stmtBot->bind_param("is", $id_usuario, $respuestaCifrada);
        $stmtBot->execute();
        $stmtBot->close();
        $con->close();

        echo json_encode(["respuesta" => $this->formatearRespuestaHTML($respuestaBot)]);
    }
    private function detectarDireccion($texto)
    {
        // Patrón simple: número + letras/calle + posible ciudad
        if (preg_match('/\d{1,5}[\w\s.,#-]+/i', $texto)) {
            return trim($texto);
        }
        return null;
    }
    private function buscarCentrosNominatim($direccion, $filtroMunicipio = null)
    {
        $coords = $this->obtenerCoordenadasAmbigua($direccion, $filtroMunicipio);
        if (!$coords) return [];

        $busquedas = [
            "centro de salud mental",
            "hospital psiquiátrico",
            "clínica psicológica",
            "centro DIF",
            "centro de atención psicológica"
        ];

        $centros = [];

        foreach ($busquedas as $q) {
            $lon = (float)$coords['lon'];
            $lat = (float)$coords['lat'];

            $url = "https://nominatim.openstreetmap.org/search?format=json&q=" . urlencode($q . " ,México") .
                "&viewbox=" . ($lon - 0.2) . "," . ($lat + 0.2) . "," . ($lon + 0.2) . "," . ($lat - 0.2) .
                "&bounded=1&countrycodes=mx";

            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 15,
                CURLOPT_USERAGENT => "IANBot/1.0"
            ]);
            $response = curl_exec($curl);
            curl_close($curl);

            $data = json_decode($response, true);
            if (is_array($data)) {
                foreach ($data as $place) {
                    if (!isset($place['display_name'])) continue;
                    $nombreCompleto = $place['display_name'];
                    $partes = explode(',', $nombreCompleto);
                    $nombre = trim($partes[0]);
                    $direccionCorta = isset($partes[1]) ? trim(implode(',', array_slice($partes, 1))) : '';

                    if (!in_array($nombre, array_column($centros, 'nombre'))) {
                        $centros[] = [
                            "nombre" => $nombre,
                            "direccion" => $direccionCorta,
                            "maps" => "https://www.google.com/maps/search/?api=1&query=" . urlencode($nombreCompleto),
                            "telefono" => "No disponible"
                        ];
                    }
                }
            }
        }
        return array_slice($centros, 0, 5); // Solo 5 resultados
    }
    private function obtenerCoordenadasAmbigua($direccion, $filtroMunicipio = null)
    {
        $url = "https://nominatim.openstreetmap.org/search?format=json&q=" . urlencode($direccion . " México");
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_USERAGENT => "IANBot/1.0"
        ]);
        $response = curl_exec($curl);
        curl_close($curl);

        $data = json_decode($response, true);
        if (!$data || !is_array($data)) return null;

        // Filtrar resultados si se indicó municipio/ciudad/estado
        if ($filtroMunicipio) {
            $data = array_filter($data, function ($d) use ($filtroMunicipio) {
                return stripos($d['display_name'], $filtroMunicipio) !== false;
            });
            $data = array_values($data); // reindexar
        }

        // Si no hay ningún resultado filtrado, usar el primero
        if (empty($data)) return null;

        // Si hay múltiples resultados y no se proporcionó filtro, devolver null para pedir más datos al usuario
        if (count($data) > 1 && !$filtroMunicipio) return null;

        return [
            'lat' => (float)$data[0]['lat'],
            'lon' => (float)$data[0]['lon']
        ];
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
