<?php
// === Leer y decodificar la solicitud entrante ===
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, true);

// API Key de OpenAI 
$apiKey = "AQUI_TU_API_KEY"; 
$modeloTexto = "gpt-4.1-mini";

// === Verificar si hay mensaje ===
$mensaje = trim($input['mensaje'] ?? '');
if (!$mensaje) {
    echo json_encode(["respuesta" => "⚠️ No se recibió ningún mensaje."]);
    exit;
}

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
- Si el usuario expresa pensamientos de daño hacia sí mismo u otros, responde con un mensaje de contención breve y redirige hacia ayuda profesional inmediata, mencionando al DIF u otras instituciones de apoyo en salud mental.  
- Cada respuesta debe tener un máximo de 200 palabras.  
- Solo responde cuando el usuario pregunte o comparta algo; no envíes información sin que sea solicitada.  

💬 Estilo de comunicación:  
- Usa frases cortas, comprensibles y cálidas.  
- Valida siempre la emoción del usuario (“Entiendo lo que sientes…”, “Es normal sentirse así…”).  
- Mantén un tono confidencial y respetuoso.  
- Ofrece pasos pequeños y realistas, no soluciones complejas.  

✅ Meta: que el usuario se sienta acompañado, escuchado y animado a cuidar su bienestar emocional y, si lo necesita, buscar ayuda profesional.  

Ahora responde al siguiente mensaje del usuario en base a estas reglas:
EOT;


// === Concatenar prompt con el mensaje ===
$promptFinal = $promptBase . "\n\nUsuario: " . $mensaje . "\nIAn Bot:";

// === Generar respuesta usando OpenAI ===
$respuestaBot = llamarOpenAI($promptFinal);

echo json_encode([
    "respuesta" => $respuestaBot
]);

// === Función para consultar el modelo de texto ===
function llamarOpenAI($prompt)
{
    global $apiKey, $modeloTexto;

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
            "max_output_tokens" => 300,
            "temperature" => 0.7
        ])
    ]);

    $respuesta = curl_exec($curl);
    curl_close($curl);

    $data = json_decode($respuesta, true);
    return $data['output'][0]['content'][0]['text'] ?? "⚠️ No se pudo generar respuesta.";
}
