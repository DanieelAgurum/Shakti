<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/vendor/autoload.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Controlador/api_key.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class contactMdl
{
    private $correo;
    private $comentario;

    function validarMensajeConIA($comentario)
    {
        $apiKey = OPENAI_API_KEY;
        $modeloTexto = "gpt-4.1-mini";

        $promptBase = <<<EOT
Eres un filtro de seguridad de mensajes. 
Valida si el siguiente texto es APTO o NO APTO para enviarse.  

Criterios:  
- NO APTO si contiene lenguaje sexual explícito, agravios, insultos u odio hacia la persona receptora.  
- APTO si es un mensaje respetuoso, neutro o emocional sin ofensas.  

Responde SOLO con una palabra:  
"APTO" o "NO APTO".  

Texto del usuario: 
$comentario
EOT;

        $ch = curl_init("https://api.openai.com/v1/responses");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $apiKey",
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            "model" => $modeloTexto,
            "input" => $promptBase
        ]));

        $result = curl_exec($ch);
        if (!$result) {
            return "NO APTO"; // fallback si falla la API
        }

        curl_close($ch);

        $data = json_decode($result, true);
        $respuesta = $data['output'][0]['content'][0]['text'] ?? "NO APTO";

        return trim($respuesta);
    }

    function inicializar($correo, $comentario)
    {
        $this->correo = trim($correo); // ahora sí toma el correo enviado
        $this->comentario = trim($comentario);
    }

    function mandarContact()
    {
        if (empty($this->correo) || empty($this->comentario)) {
            echo "Todos los campos son obligatorios.";
            return;
        }

        $validacion = $this->validarMensajeConIA($this->comentario);
        if ($validacion !== "APTO") {
            echo "No Apto";
            return;
        }

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = "gooddani04@gmail.com";
            $mail->Password = "fxvlvxrxswzgunjk";
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;


            $mail->setFrom($mail->Username, 'Formulario de Contacto');
            $mail->addAddress('gooddani04@gmail.com');

            $nickname = $_SESSION['nickname'] ?? 'Usuario del formulario';
            $mail->addReplyTo($this->correo, $nickname);

            $mail->isHTML(true);
            $mail->Subject = 'Nuevo mensaje de contacto desde el formulario';
            $mail->Body = "
                <p><strong>Correo del usuario ({$nickname}):</strong> {$this->correo}</p>
                <p><strong>Comentario:</strong><br>{$this->comentario}</p>
            ";
            $mail->AltBody = "Correo del usuario ({$nickname}): {$this->correo}\nComentario: {$this->comentario}";

            $mail->send();
            echo "Enviado";
        } catch (Exception $e) {
            echo "Error al enviar el mensaje: {$mail->ErrorInfo}";
        }
    }
}
