<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once "../Modelo/configuracionMdl.php";
require_once "../vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION['id_usuaria'])) die("Acceso denegado");

$idUsuaria = $_SESSION['id_usuaria'];
$config = new ConfiguracionMdl();

if (isset($_POST['accion']) && $_POST['accion'] === "generar_token") {
    $token = bin2hex(random_bytes(16));
    $config->guardarToken($idUsuaria, $token);

    $con = (new ConectarDB())->open();
    $stmt = $con->prepare("SELECT correo FROM usuarias WHERE id = :id");
    $stmt->bindParam(":id", $idUsuaria, PDO::PARAM_INT);
    $stmt->execute();
    $email = $stmt->fetch(PDO::FETCH_ASSOC)['correo'];
    $con = null;

    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = "gooddani04@gmail.com"; 
        $mail->Password = "fxvl vxrx swzg unjk"; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom("gooddani04@gmail.com", "Soporte Shakti");
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = "Token para cambio de contraseña";
        $mail->Body = '<html>
        <head>
        <meta charset="UTF-8">
        <title>Token para cambio de contraseña</title>
        </head>
        <body style="font-family: Arial, sans-serif; background-color:#f4f4f4; padding:20px;">
          <table width="100%" cellpadding="0" cellspacing="0" style="max-width:600px; margin:auto; background-color:#ffffff; border-radius:10px; overflow:hidden;">
            <tr>
              <td style="background-color:#1e1e2f; color:#ffffff; text-align:center; padding:20px;">
                <h2>Soporte Shakti</h2>
              </td>
            </tr>
            <tr>
              <td style="padding:30px; color:#333333;">
                <p>Hola,</p>
                <p>Has solicitado cambiar tu contraseña. Usa el siguiente token para completar el proceso:</p>
                <p style="text-align:center; margin:30px 0;">
                  <span style="font-size:24px; font-weight:bold; color:#007bff; background:#f0e6f7; padding:10px 20px; border-radius:5px; display:inline-block;">
                    ' . $token . '
                  </span>
                </p>
                <p>Este token caduca en <strong>15 minutos</strong>.</p>
                <p>Si no solicitaste este cambio, ignora este correo.</p>
              </td>
            </tr>
            <tr>
              <td style="background-color:#eeeeee; text-align:center; padding:15px; font-size:12px; color:#666666;">
                © 2025 Shakti. Todos los derechos reservados.
              </td>
            </tr>
          </table>
        </body>
        </html>';

        $mail->send();
        echo json_encode(["status" => "ok", "msg" => "Token enviado"]);
    } catch (Exception $e) {
        echo json_encode([
            "status" => "error",
            "msg" => "Error al enviar correo: " . $mail->ErrorInfo
        ]);
    }
    exit;
}

if (isset($_POST['accion']) && $_POST['accion'] === "guardar_configuracion") {
    $token = $_POST['token'] ?? "";
    $newPass = $_POST['newPassword'] ?? "";

    $datosConfig = [
        'permitir_amigos' => isset($_POST['permitir_amigos']) ? 1 : 0,
        'perfil_privado' => isset($_POST['perfil_privado']) ? 1 : 0,
        'notificar_mensajes' => isset($_POST['notificar_mensajes']) ? 1 : 0,
        'notificar_comentarios' => isset($_POST['notificar_comentarios']) ? 1 : 0,
        'tamano_fuente' => $_POST['tamano_fuente'] ?? 'medium',
        'modo_oscuro' => isset($_POST['modo_oscuro']) ? 1 : 0,
        'alto_contraste' => isset($_POST['alto_contraste']) ? 1 : 0
    ];

    if (empty($newPass)) {
        $config->guardarConfiguracion($idUsuaria, $datosConfig);
        echo json_encode(["status" => "ok", "msg" => "Configuraciones actualizadas"]);
        exit;
    }

    if (empty($token)) {
        echo json_encode(["status" => "error", "msg" => "Debes ingresar un token para cambiar la contraseña"]);
        exit;
    }

    if ($config->verificarToken($idUsuaria, $token)) {
        $hash = password_hash($newPass, PASSWORD_BCRYPT);
        $config->actualizarPassword($idUsuaria, $hash);
        $config->borrarToken($idUsuaria, $token);

        $config->guardarConfiguracion($idUsuaria, $datosConfig);

        echo json_encode(["status" => "ok", "msg" => "Contraseña y configuraciones actualizadas"]);
    } else {
        echo json_encode(["status" => "error", "msg" => "Token inválido o caducado"]);
    }
    exit;
}
