<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Controlador/api_key.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Modelo/conexion.php';

class TestIanMdl {
    private $apiKey;
    private $db;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $this->apiKey = OPENAI_API_KEY;

        $conexion = new ConectarDB();
        $this->db = $conexion->open();
        if (!$this->db) die(" Error: no se pudo conectar a la base de datos.");
    }
     // validacion para saber si puede hacer el test
    public function puedeHacerTest(int $idUsuario): bool {
        $sql = "SELECT fecha_realizacion FROM test 
                WHERE id_usuaria = :id_usuario 
                ORDER BY fecha_realizacion DESC LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":id_usuario" => $idUsuario]);
        $ultimo = $stmt->fetch();

        if ($ultimo) {
            $fechaUltimo = strtotime($ultimo['fecha_realizacion']);
            $dias = (time() - $fechaUltimo) / (60*60*24);
            return $dias >= 7;
        }
        return true;
    }
     //prompt para la IA
private function crearPrompt(array $respuestas): string {
    $mensaje = "Eres IAnBot, asistente de bienestar emocional.\n";
    $mensaje .= "Analiza estas respuestas de un test de salud mental. No des diagnósticos médicos.\n\n";
    $mensaje .= "Respuestas del usuario:\n";

    foreach ($respuestas as $pregunta => $valor) {
        $mensaje .= ucfirst($pregunta) . ": " . $valor . "\n";
    }

    $mensaje .= "\nInstrucciones:\n";
    $mensaje .= "- Comenta brevemente lo que percibes de cada respuesta, indicando emociones o posibles signos de ansiedad, estrés o depresión.\n";
    $mensaje .= "- Ofrece consejos prácticos y personalizados para mejorar el bienestar emocional.\n";
    $mensaje .= "- Mantén un tono cálido y motivador, como si hablaras con un amigo.\n";
    $mensaje .= "- Concluye con un resumen en **una frase corta** indicando posibles señales: 'ansiedad', 'depresión', 'estrés', o 'sin alerta'.\n";
    $mensaje .= "- No seas genérico; toma en cuenta cada respuesta para que el análisis sea único para el usuario.\n";
    $mensaje .= "- Entrega todo en un solo párrafo seguido del resumen final.\n";

    return $mensaje;
}

  // Realiza el análisis del test y guarda el resultado
    public function analizarTest(array $respuestas, int $idUsuario): string {
        if (!$this->puedeHacerTest($idUsuario)) {
            return " Ya realizaste este test recientemente. Debes esperar 7 días para volver a hacerlo.";
        }

        $prompt = $this->crearPrompt($respuestas);

        $curl = curl_init("https://api.openai.com/v1/chat/completions");
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer {$this->apiKey}",
                "Content-Type: application/json"
            ],
            CURLOPT_POSTFIELDS => json_encode([
                "model" => "gpt-4.1-mini",
                "messages" => [
                    ["role" => "system", "content" => "Eres IAnBot, asistente emocional."],
                    ["role" => "user", "content" => $prompt]
                ],
                "temperature" => 0.7,
                "max_tokens" => 500
            ])
        ]);

        $respuesta = curl_exec($curl);
        if(curl_errno($curl)) {
            curl_close($curl);
            return "⚠️ Error de conexión con OpenAI: " . curl_error($curl);
        }
        curl_close($curl);

        $data = json_decode($respuesta, true);

        if(isset($data['error'])) {
            return " Error de la API: " . $data['error']['message'];
        }

        $textoIA = $data['choices'][0]['message']['content'] 
                    ?? $data['output'][0]['content'][0]['text'] 
                    ?? null;

        if (!$textoIA) {
            return " La IA no respondió correctamente. Revisa la API Key, modelo o prompt.";
        }

        $resumenResultado = '';
        if (preg_match('/ansiedad|depresión|estrés|sin alerta/i', $textoIA, $coincidencias)) {
            $resumenResultado = strtolower($coincidencias[0]);
        } else {
            $resumenResultado = 'sin alerta';
        }

        $sql = "INSERT INTO test (id_usuaria, resultado_test, resultado, fecha_realizacion)
        VALUES (:id_usuario, :resultado_test, :resultado, NOW())";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ":id_usuario" => $idUsuario,
            ":resultado_test" => $textoIA,
            ":resultado" => $resumenResultado
        ]);

        return $textoIA;
    }
}

