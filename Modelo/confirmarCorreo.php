<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Modelo/conexion.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ConfirmarCorreo
{
    private $correo;
    private $nombre;
    private $urlBase;
    private $id_usuaria;
    private $con;

    public function inicializar($correo, $nombre, $urlBase, $id_usuaria)
    {
        $this->correo = $correo;
        $this->nombre = $nombre;
        $this->urlBase = rtrim($urlBase, '/');
        $this->id_usuaria = $id_usuaria;
    }

    private function conectarBD()
    {
        $db = new ConectarDB();
        $this->con = $db->open();
        return $this->con;
    }

    private function guardarToken($token)
    {
        $con = $this->conectarBD();
        date_default_timezone_set('America/Mexico_City');
        $fecha = date('Y-m-d H:i:s');

        // Verificar si existe token previo
        $sql = "SELECT COUNT(*) AS total FROM tokens_contrasena WHERE id_usuaria = ?";
        $stmt = $con->prepare($sql);
        $stmt->execute([$this->id_usuaria]);
        $count = $stmt->fetch()['total'];

        if ($count > 0) {
            $update = "UPDATE tokens_contrasena SET token = ?, fecha = ? WHERE id_usuaria = ?";
            $stmt = $con->prepare($update);
            $stmt->execute([$token, $fecha, $this->id_usuaria]);
        } else {
            $insert = "INSERT INTO tokens_contrasena (id_usuaria, token, fecha) VALUES (?, ?, ?)";
            $stmt = $con->prepare($insert);
            $stmt->execute([$this->id_usuaria, $token, $fecha]);
        }

        $con = null;
    }

    public function enviarCorreoVerificacion()
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'cristo045millanperez@gmail.com';
            $mail->Password = 'samn oqgn huyz ejkj';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->CharSet = 'UTF-8';
            $mail->setFrom("gooddani04@gmail.com", 'NexoH');

            // Token
            $token = bin2hex(random_bytes(60));
            $this->guardarToken($token);

            $link = $this->urlBase . "/Vista/confirmar.php?token=" . $token;

            $bodyHTML = "
            <html>
            <body>
                <h2>Confirma tu cuenta en Shakti</h2>
                <p>Hola <b>{$this->nombre}</b>, haz clic en el siguiente botón para verificar tu cuenta:</p>
                <a href='{$link}'>Confirmar mi cuenta</a>
            </body>
            </html>";

            $mail->addAddress($this->correo, $this->nombre);
            $mail->isHTML(true);
            $mail->Subject = 'Confirma tu cuenta en Shakti';
            $mail->Body = $bodyHTML;
            $mail->AltBody = "Confirma tu cuenta en este enlace: {$link}";

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function verificarCuenta($token)
    {
        $con = $this->conectarBD();

        $sql = "SELECT id_usuaria FROM tokens_contrasena WHERE token = ?";
        $stmt = $con->prepare($sql);
        $stmt->execute([$token]);
        $data = $stmt->fetch();

        if ($data) {
            $id = $data['id_usuaria'];

            $update = "UPDATE usuarias SET verificado = 1 WHERE id = ?";
            $stmt = $con->prepare($update);
            $stmt->execute([$id]);

            $delete = "DELETE FROM tokens_contrasena WHERE id_usuaria = ?";
            $stmt = $con->prepare($delete);
            $stmt->execute([$id]);

            $con = null;
            header("Location: " . $this->urlBase . "/Vista/login.php?status=success&message=" . urlencode("Cuenta verificada correctamente"));
            exit;
        } else {
            $con = null;
            header("Location: " . $this->urlBase . "/Vista/login.php?status=error&message=" . urlencode("Token inválido o ya utilizado"));
            exit;
        }
    }
}
