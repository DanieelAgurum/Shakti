<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Controlador/api_key.php';

class TestIanMdl {
    private $apiKey;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $this->apiKey = OPENAI_API_KEY;
    }

    private function crearPrompt(array $respuestas): string {
        $mensaje = "Eres IAn Bot, un asistente digital experto en bienestar emocional masculino.\n";
        $mensaje .= "Analiza las siguientes respuestas de un test sobre manejo emocional. No des diagnósticos profesionales.\n\n";

        foreach ($respuestas as $pregunta => $valor) {
            $mensaje .= ucfirst($pregunta) . ": " . $valor . "\n";
        }

        $mensaje .= "\nInstrucciones:\n";
        $mensaje .= "- Valida las emociones del usuario.\n";
        $mensaje .= "- Da consejos prácticos y sencillos para manejar estrés, ansiedad, cansancio o frustración.\n";
        $mensaje .= "- Mantén un tono cálido, motivador y breve.\n";
        $mensaje .= "- Invita a platicar con IAn Bot o un profesional si desea más apoyo.\n";
        $mensaje .= "- Entrega la respuesta en un solo párrafo listo para mostrar al usuario.\n";
        $mensaje .= "- No hagas diagnósticos médicos o psicológicos.\n";

        return $mensaje;
    }

    public function analizarTest(array $respuestas): string {
        $prompt = $this->crearPrompt($respuestas);

        $curl = curl_init("https://api.openai.com/v1/responses");
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer {$this->apiKey}",
                "Content-Type: application/json"
            ],
            CURLOPT_POSTFIELDS => json_encode([
                "model" => "gpt-4.1-mini",
                "input" => $prompt,
                "temperature" => 0.7,
                "max_output_tokens" => 500
            ])
        ]);

        $respuesta = curl_exec($curl);
        if (curl_errno($curl)) {
            curl_close($curl);
            return "⚠️ Error al conectar con la IA: " . curl_error($curl);
        }
        curl_close($curl);

        $data = json_decode($respuesta, true);

        return $data['output'][0]['content'][0]['text'] ?? "⚠️ La IA no respondió correctamente.";
    }
}
