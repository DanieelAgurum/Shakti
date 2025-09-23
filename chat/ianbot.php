<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Controlador/api_key.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// === Leer y decodificar la solicitud entrante ===
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, true);

// === Verificar si hay mensaje ===
$mensaje = trim($input['mensaje'] ?? '');
if (!$mensaje) {
    echo json_encode(["respuesta" => "⚠️ No se recibió ningún mensaje."]);
    exit;
}

// Inicializar historial si no existe
if (!isset($_SESSION['historial'])) {
    $_SESSION['historial'] = [];
}

// Guardar mensaje del usuario en historial
$_SESSION['historial'][] = ["rol" => "usuario", "contenido" => $mensaje];

// === Prompt base (identidad del bot) ===
$promptBase = <<<EOT
Eres IAn Bot, un asistente digital de acompañamiento emocional preventivo diseñado para hombres adultos entre 18 y 60 años.

🎯 Tu función es escuchar, apoyar y orientar de manera empática, ayudando a los usuarios a:  
- Expresar cómo se sienten sin juicios.  
- Identificar emociones básicas (estrés, ansiedad, tristeza, enojo, etc.).  
- Ofrecer recomendaciones prácticas y cotidianas (ejercicios de respiración, técnicas de relajación, consejos simples de autocuidado).  
- Motivar con un tono amigable, empático y claro, evitando tecnicismos psicológicos complejos.

⚠️ Limitaciones importantes:  
- No eres un sustituto de la atención psicológica profesional.  
- No des diagnósticos médicos ni psicológicos.  
- Si el usuario expresa pensamientos de daño hacia sí mismo u otros, responde con un mensaje de contención breve y redirige hacia ayuda profesional inmediata.

💬 Estilo de comunicación:  
- Usa frases cortas, comprensibles y cálidas.  
- Valida siempre la emoción del usuario.  
- Mantén un tono confidencial y respetuoso.  
- Ofrece pasos pequeños y realistas, no soluciones complejas.

📌 Reglas de continuidad, memoria y acción:  
- Recuerda todo lo que el usuario diga durante la conversación y úsalo para responder coherentemente.  
- Si el usuario cambia de tema y luego vuelve a un tema anterior, retoma el hilo anterior correctamente sin repetir información innecesaria.  
- Si el usuario pregunta algo que ya se dijo, responde recordando lo que ya se dijo y ofrece nuevas ideas solo si se solicita.  
- Mantén un flujo natural de conversación lineal según corresponda al tema actual.  
- Usa la información previa del usuario para personalizar respuestas (nombre, preferencias, emociones).  
- Cuando tengas la ubicación del usuario, proporciona centros o instituciones de apoyo cercanas.  
- Si el usuario responde con cualquier mensaje afirmativo o breve (como “sí”, “claro”, “vale”, “ok”, “smn” o cualquier abreviatura), interpreta su intención de manera positiva y **retoma inmediatamente la acción o sugerencia ofrecida** sin preguntar de nuevo.  
- Incluso si el usuario escribe algo ambiguo o poco claro, ofrece ayuda concreta, pasos prácticos o recomendaciones basadas en lo que ya se había sugerido.

✅ Meta: que el usuario se sienta acompañado, escuchado, comprendido y animado a cuidar su bienestar emocional.

Indica cuando quieras que la respuesta incluya listas en formato HTML usando <ul> y <li>.  

Ahora responde al siguiente mensaje del usuario en base a estas reglas:
EOT;

// === Construir historial para enviar al modelo ===
$historialTexto = "";
foreach ($_SESSION['historial'] as $linea) {
    $historialTexto .= ucfirst($linea['rol']) . ": " . $linea['contenido'] . "\n";
}

// === Prompt final para OpenAI ===
$promptFinal = $promptBase . "\n\n" . $historialTexto . "IAn Bot:";

// === Generar respuesta usando OpenAI ===
$respuestaBot = llamarOpenAI($promptFinal);

// Guardar respuesta en historial
$_SESSION['historial'][] = ["rol" => "bot", "contenido" => $respuestaBot];

// === Formatear la respuesta en HTML ===
$respuestaBotHTML = formatearRespuestaHTML($respuestaBot);

echo json_encode([
    "respuesta" => $respuestaBotHTML
]);

// === Función para formatear respuestas en HTML con listas y saltos de línea ===
function formatearRespuestaHTML($texto)
{
    // Separar por líneas para detectar listas
    $lineas = explode("\n", $texto);
    $html = "";
    $enLista = false;

    foreach ($lineas as $linea) {
        $linea = trim($linea);
        if ($linea === "") continue;

        // Si empieza con guion, número o asterisco, convertir a <li>
        if (preg_match('/^(?:\d+\.|\-|\*)\s*(.*)/', $linea, $matches)) {
            if (!$enLista) {
                $html .= "<ul>\n";
                $enLista = true;
            }
            $html .= "<li>" . $matches[1] . "</li>\n";
        } else {
            if ($enLista) {
                $html .= "</ul>\n";
                $enLista = false;
            }
            $html .= "<p>$linea</p>\n";
        }
    }

    if ($enLista) $html .= "</ul>\n";

    return $html;
}

// === Función para llamar al modelo de OpenAI ===
function llamarOpenAI($prompt)
{
    $apiKey = OPENAI_API_KEY;
    $modeloTexto = "gpt-4.1-mini";

    $curl = curl_init("https://api.openai.com/v1/responses");
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $apiKey",
            "Content-Type: application/json"
        ],
        CURLOPT_POSTFIELDS => json_encode([
            "model" => $modeloTexto,
            "input" => $prompt,
            "max_output_tokens" => 500,
            "temperature" => 0.7
        ])
    ]);

    $respuesta = curl_exec($curl);
    curl_close($curl);

    $data = json_decode($respuesta, true);
    return $data['output'][0]['content'][0]['text'] ?? "⚠️ No se pudo generar respuesta.";
}
