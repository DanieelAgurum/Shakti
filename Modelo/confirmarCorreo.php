<?php

include_once '../Modelo/conexion.php';
require_once '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class confirmarCorreo
{
    private $correo;
    private $con;
    private $urlBase;

    public function conectarBD()
    {
        $this->con = mysqli_connect("localhost", "root", "", "shakti");
        if (!$this->con) {
            die("Problemas con la conexión a la base de datos: " . mysqli_connect_error());
        }
        return $this->con;
    }

    public function inicializar($correo, $urlBase)
    {
        $this->correo = $correo;
        $this->urlBase = rtrim($urlBase, '/');
    }

    public function enviarCorreoConfirmacion()
    {
        $con = $this->conectarBD();

        $stmt = $con->prepare("SELECT id, nombre, correo FROM usuarias WHERE correo = ? LIMIT 1");
        $stmt->bind_param("s", $this->correo);
        $stmt->execute();
        $usuario = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        mysqli_close($con);

        if (!$usuario) {
            return ["success" => false, "error" => "No se encontró la usuaria"];
        }

        $mail = new PHPMailer(true);

        try {
            // Configuración SMTP para Gmail
            $mail->isSMTP();
            $mail->SMTPDebug = 0; // PRODUCCIÓN: no imprime debug
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'cristo045millanperez@gmail.com'; // TU CUENTA DE GMAIL
            $mail->Password = 'samn oqgn huyz ejkj';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->CharSet = 'UTF-8';
            $mail->setFrom('cristo045millanperez@gmail.com', 'Shakti'); // Debe coincidir con Username
            $mail->addAddress($usuario['correo'], $usuario['nombre']);

            $link = $this->urlBase . "/Vista/confirmarCorreo.php?correo=" . urlencode($usuario['correo']);

            $bodyHTML = "
            <html>
            <head>
            <style>
                body { font-family: Arial, sans-serif; background: #f7f7f7; padding: 20px; }
                .container { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
                h2 { color: #333; }
                a.button {
                    display: inline-block;
                    padding: 10px 20px;
                    margin-top: 20px;
                    background-color: #007bff;
                    color: white;
                    text-decoration: none;
                    border-radius: 5px;
                }
                a.button:hover { background-color: #0056b3; }
            </style>
            </head>
            <body>
                <div class='container'>
                    <h2>Confirma tu correo</h2>
                    <p>Hola <b>{$usuario['nombre']}</b>,</p>
                    <p>Haz clic en el siguiente botón para confirmar tu correo:</p>
                    <a href='{$link}' class='button'>Confirmar Correo</a>
                    <p>Si no solicitaste este correo, ignóralo.</p>
                </div>
            </body>
            </html>
            ";

            $mail->isHTML(true);
            $mail->Subject = 'Confirma tu correo en Shakti';
            $mail->Body = $bodyHTML;
            $mail->AltBody = "Hola {$usuario['nombre']}, para confirmar tu correo visita: {$link}";

            $mail->send();
            return ["success" => true];
        } catch (Exception $e) {
            return ["success" => false, "error" => "No se pudo enviar el correo: {$mail->ErrorInfo}"];
        }
    }
}













