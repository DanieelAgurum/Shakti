<?php
require_once '../vendor/autoload.php';
require_once '../Modelo/conexion.php';

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
        $this->con = mysqli_connect("localhost", "root", "", "shakti");
        if (!$this->con) {
            die("Error al conectar a la base de datos: " . mysqli_connect_error());
        }
        return $this->con;
    }

    private function guardarToken($token)
    {
        $con = $this->conectarBD();
        date_default_timezone_set('America/Mexico_City');
        $fecha = date('Y-m-d H:i:s');

        // Si ya existe token previo, actualiza
        $sql = "SELECT COUNT(*) FROM tokens_contrasena WHERE id_usuaria = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "i", $this->id_usuaria);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $count);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        if ($count > 0) {
            $update = "UPDATE tokens_contrasena SET token = ?, fecha = ? WHERE id_usuaria = ?";
            $stmt = mysqli_prepare($con, $update);
            mysqli_stmt_bind_param($stmt, "ssi", $token, $fecha, $this->id_usuaria);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        } else {
            $insert = "INSERT INTO tokens_contrasena (id_usuaria, token, fecha) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($con, $insert);
            mysqli_stmt_bind_param($stmt, "iss", $this->id_usuaria, $token, $fecha);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        mysqli_close($con);
    }

    public function enviarCorreoVerificacion()
    {
        $mail = new PHPMailer(true);

        try {
            // Configuración SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'cristo045millanperez@gmail.com';
            $mail->Password = 'samn oqgn huyz ejkj';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->CharSet = 'UTF-8';
            $mail->setFrom('cristo045millanperez@gmail.com', 'Shakti');

            // Generar token
            $token = bin2hex(random_bytes(60));
            $this->guardarToken($token);

            // Crear enlace
            $link = $this->urlBase . "/Vista/confirmar.php?token=" . $token;

            // Contenido del correo
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
                    <h2>Confirma tu cuenta en Shakti</h2>
                    <p>Hola <b>{$this->nombre}</b>,</p>
                    <p>Gracias por registrarte en Shakti. Haz clic en el siguiente botón para verificar tu cuenta:</p>
                    <a href='{$link}' class='button'>Confirmar mi cuenta</a>
                    <p>Si no realizaste este registro, puedes ignorar este mensaje.</p>
                </div>
            </body>
            </html>
            ";

            $mail->addAddress($this->correo, $this->nombre);
            $mail->isHTML(true);
            $mail->Subject = 'Confirma tu cuenta en Shakti';
            $mail->Body = $bodyHTML;
            $mail->AltBody = "Hola {$this->nombre}, confirma tu cuenta en: {$link}";

            $mail->send();
            return true;

        } catch (Exception $e) {
            return false;
        }
    }

    public function verificarCuenta($token)
    {
        $con = $this->conectarBD();
        $tokenEscaped = mysqli_real_escape_string($con, $token);

        $sql = "SELECT id_usuaria FROM tokens_contrasena WHERE token = '$tokenEscaped'";
        $result = mysqli_query($con, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $data = mysqli_fetch_assoc($result);
            $id = $data['id_usuaria'];

            $update = "UPDATE usuarias SET verificado = 1 WHERE id = $id";
            mysqli_query($con, $update);

            $delete = "DELETE FROM tokens_contrasena WHERE id_usuaria = $id";
            mysqli_query($con, $delete);

            mysqli_close($con);
            header("Location: " . $this->urlBase . "/Vista/login.php?status=success&message=" . urlencode("Cuenta verificada correctamente"));
            exit;
        } else {
            mysqli_close($con);
            header("Location: " . $this->urlBase . "/Vista/login.php?status=error&message=" . urlencode("Token inválido o ya utilizado"));
            exit;
        }
    }
}
