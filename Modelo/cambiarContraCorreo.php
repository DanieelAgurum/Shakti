<?php

include_once '../obtenerLink/obtenerLink.php';
include_once '../Modelo/conexion.php';
require_once '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class cambiarContraCorreo
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
        $this->urlBase = rtrim($urlBase, '/'); // eliminar posible barra final
    }

    private function guardarTokenEnBD($userId, $token)
    {
        $con = $this->conectarBD();
        date_default_timezone_set('America/Mexico_City');
        $fecha = date('Y-m-d H:i:s');

        // Primero verificamos si ya existe token para ese usuario
        $sqlCheck = "SELECT COUNT(*) FROM tokens_contrasena WHERE id_usuaria = ?";
        $stmtCheck = mysqli_prepare($con, $sqlCheck);
        mysqli_stmt_bind_param($stmtCheck, "i", $userId);
        mysqli_stmt_execute($stmtCheck);
        mysqli_stmt_bind_result($stmtCheck, $count);
        mysqli_stmt_fetch($stmtCheck);
        mysqli_stmt_close($stmtCheck);

        if ($count > 0) {
            // Hacer UPDATE con fecha desde PHP
            $sqlUpdate = "UPDATE tokens_contrasena SET token = ?, fecha = ? WHERE id_usuaria = ?";
            $stmt = mysqli_prepare($con, $sqlUpdate);
            mysqli_stmt_bind_param($stmt, "ssi", $token, $fecha, $userId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        } else {
            // Hacer INSERT con fecha desde PHP
            $sqlInsert = "INSERT INTO tokens_contrasena (id_usuaria, token, fecha) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($con, $sqlInsert);
            mysqli_stmt_bind_param($stmt, "iss", $userId, $token, $fecha);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        mysqli_close($con);
    }

    public function enviarToken()
    {
        $con = $this->conectarBD();

        $sql = "SELECT id, correo, nickname FROM usuarias WHERE correo = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "s", $this->correo);
        $usuario = mysqli_stmt_execute($stmt);

        if (!$usuario) {
            echo "Error en la ejecución de la consulta.";
            return;
        }

        mysqli_stmt_bind_result($stmt, $id, $correo, $nickname);
        $existeUsuario = mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($con);

        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'cristo045millanperez@gmail.com';
            $mail->Password = 'samn oqgn huyz ejkj';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Remitente y destinatario
            $mail->CharSet = 'UTF-8';
            $mail->setFrom('cristo045millanperez@gmail.com', 'Shakti');

            // Si existe usuario, generamos token y link con token
            if ($existeUsuario) {
                $mail->addAddress($correo, $nickname);
                $token = bin2hex(random_bytes(60) . hash("sha512", $correo));
                $this->guardarTokenEnBD($id, $token);

                $link = $this->urlBase . "/Vista/recuperarContra.php?token=" . $token;

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
                        background-color: #28a745;
                        color: white;
                        text-decoration: none;
                        border-radius: 5px;
                    }
                    a.button:hover { background-color: #218838; }
                </style>
                </head>
                <body>
                    <div class='container'>
                        <h2>Recuperación de contraseña</h2>
                        <p>Hola <b>{$nickname}</b>,</p>
                        <p>Haz clic en el siguiente botón para recuperar tu contraseña:</p>
                        <a href='{$link}' class='button'>Recuperar Contraseña</a>
                        <p>Si no solicitaste este correo, ignóralo.</p>
                    </div>
                </body>
                </html>
                ";

                $mail->isHTML(true);
                $mail->Subject = 'Recuperación de contraseña';
                $mail->Body = $bodyHTML;
                $mail->AltBody = "Hola {$nickname}, para recuperar tu contraseña visita: {$link}";
                $mail->send();

                echo json_encode(["success" => true]);
                exit;
            } else {
                $mail->addAddress($this->correo);

                $linkSimple = $this->urlBase . "/Vista/registro.php";

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
                        <h2>Registrate en Shakti</h2>
                        <p>Hola, de parte de Shakti:</p>
                        <p>Parece que no tenemos una cuenta asociada a este correo.</p>
                        <p>De todas formas, puedes intentar registrarte en el siguiente enlace:</p>
                        <a href='{$linkSimple}' class='button'>Registrate</a>
                    </div>
                </body>
                </html>
                ";

                $mail->isHTML(true);
                $mail->Subject = 'Reegistrate en Shakti';
                $mail->Body = $bodyHTML;
                $mail->AltBody = "Parece que no hay una cuenta asociada a este correo. Puedes intentar registrarte en: {$linkSimple}";
                $mail->send();

                echo json_encode(["success" => true]);
                exit;
            }
        } catch (Exception $e) {
            echo json_encode(["success" => false, "error" => "No se pudo enviar el correo."]);
            exit;
        }
    }


    public function obtenerRuta($ruta)
    {
        $this->urlBase = $ruta;
    }

    public function cambiarContra($token, $contraseña)
    {
        $con = $this->conectarBD();

        $tokenEscaped = mysqli_real_escape_string($con, $token);
        $sql = "SELECT u.id 
            FROM usuarias u 
            JOIN tokens_contrasena t ON u.id = t.id_usuaria 
            WHERE t.token = '$tokenEscaped'";
        $result = mysqli_query($con, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $fila = mysqli_fetch_assoc($result);
            $idUsuaria = $fila['id'];

            $hash = password_hash($contraseña, PASSWORD_DEFAULT);

            $update = "UPDATE usuarias SET contraseña = '$hash' WHERE id = $idUsuaria";
            $resultadoUpdate = mysqli_query($con, $update);


            if ($resultadoUpdate) {
                $sql = "DELETE FROM tokens_contrasena WHERE id_usuaria = $idUsuaria";
                $result = mysqli_query($con, $sql);
                if ($result) {
                    mysqli_close($con);
                    header("Location: " . $this->urlBase . "/Vista/login.php?status=success&message=" . urlencode("Se actualizó correctamente la contraseña"));
                    exit;
                } else {
                    header("Location: " . $this->urlBase . "/Vista/login.php?status=error&message=" . urlencode("Ocurrió un error intentar más tarde"));
                    exit;
                }
            } else {
                header("Location: " . $this->urlBase . "/Vista/login.php?status=error&message=" . urlencode("Ocurrió un problema al actualizar la contraseña"));
                exit;
            }
        } else {
            mysqli_close($con);
            header("Location: " . $this->urlBase . "/Vista/login.php?status=error&message=" . urlencode("Token inválido o expirado"));
            exit;
        }
    }
}
