<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../Modelo/configuracionMdl.php";
require_once "../libs/PHPMailer/PHPMailer.php";
require_once "../libs/PHPMailer/SMTP.php";
require_once "../libs/PHPMailer/Exception.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION['id_usuaria'])) {
    die("Acceso denegado");
}

$idUsuaria = $_SESSION['id_usuaria'];
$config = new ConfiguracionMdl();

// Guardar datos de cuenta (email)
$email = $_POST['email'] ?? null;
$config->actualizarCuenta($idUsuaria, $email);

// Si hay solicitud de nueva contraseña → generar, guardar y enviar por correo
if (isset($_POST['newPassword']) && !empty($_POST['newPassword'])) {
    $newPass = $_POST['newPassword'];
    $hash = password_hash($newPass, PASSWORD_BCRYPT);
    $config->actualizarPassword($idUsuaria, $hash);

    // Enviar correo con PHPMailer
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = "smtp.tu-servidor.com";
        $mail->SMTPAuth = true;
        $mail->Username = "tu_correo@dominio.com";
        $mail->Password = "tu_password";
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom("tu_correo@dominio.com", "Soporte Shakti");
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = "Tu nueva contraseña";
        $mail->Body = "<p>Hola, tu contraseña ha sido actualizada.</p>
                       <p><b>Nueva contraseña:</b> {$newPass}</p>";

        $mail->send();
    } catch (Exception $e) {
        error_log("Error enviando correo: " . $mail->ErrorInfo);
    }
}

// Guardar configuraciones de privacidad, notificaciones y accesibilidad
$datosConfig = [
    'permitir_amigos' => isset($_POST['permitir_amigos']) ? 1 : 0,
    'perfil_privado' => isset($_POST['perfil_privado']) ? 1 : 0,
    'notificar_mensajes' => isset($_POST['notificar_mensajes']) ? 1 : 0,
    'notificar_comentarios' => isset($_POST['notificar_comentarios']) ? 1 : 0,
    'tamano_fuente' => $_POST['tamano_fuente'] ?? 'medium',
    'modo_oscuro' => isset($_POST['modo_oscuro']) ? 1 : 0,
    'alto_contraste' => isset($_POST['alto_contraste']) ? 1 : 0
];

$config->guardarConfiguracion($idUsuaria, $datosConfig);

// Redirigir
header("Location: ../vistas/perfil.php?config=ok");
exit;
