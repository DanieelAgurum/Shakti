<?php
require_once '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class contactMdl
{
    private $correo;
    private $comentario;

    function inicializar($correo, $comentario)
    {
        $this->correo = $correo;
        $this->comentario = $comentario;
    }

    function mandarContact()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($this->correo) || empty($this->comentario)) {
            echo "Todos los campos son obligatorios.";
            return;
        }

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'cristo045millanperez@gmail.com';
            $mail->Password = 'samn oqgn huyz ejkj';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('cristo045millanperez@gmail.com', 'Formulario de Contacto');
            $mail->addAddress('cristo045millanperez@gmail.com');

            $nickname = $_SESSION['nickname'] ?? 'Usuario del formulario';

            $mail->addReplyTo($this->correo, $nickname);

            // Crear cuerpo del mensaje
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
