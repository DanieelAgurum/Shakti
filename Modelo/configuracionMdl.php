<?php
require_once "conexion.php";

class ConfiguracionMdl extends ConectarDB
{
    public function actualizarCuenta($idUsuaria, $email)
    {
        $con = $this->open();

        if ($email) {
            $sql = "UPDATE usuarias SET correo = :email WHERE id_usuaria = :id";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":id", $idUsuaria, PDO::PARAM_INT);
            $stmt->execute();
        }

        $this->close();
    }

    public function actualizarPassword($idUsuaria, $passwordHash)
    {
        $con = $this->open();

        $sql = "UPDATE usuarias SET contrasena = :pass WHERE id_usuaria = :id";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(":pass", $passwordHash);
        $stmt->bindParam(":id", $idUsuaria, PDO::PARAM_INT);
        $stmt->execute();

        $this->close();
    }

    public function guardarConfiguracion($idUsuaria, $datos)
    {
        $con = $this->open();

        $sqlCheck = "SELECT id_config FROM configuracion_cuenta WHERE id_usuaria = :id LIMIT 1";
        $stmtCheck = $con->prepare($sqlCheck);
        $stmtCheck->bindParam(":id", $idUsuaria, PDO::PARAM_INT);
        $stmtCheck->execute();

        if ($stmtCheck->rowCount() > 0) {
            // UPDATE
            $sql = "UPDATE configuracion_cuenta SET 
                permitir_amigos = :amigos,
                perfil_privado = :privado,
                notificar_mensajes = :notifMsg,
                notificar_comentarios = :notifCom,
                tamano_fuente = :fuente,
                modo_oscuro = :oscuro,
                alto_contraste = :contraste
                WHERE id_usuaria = :id";
        } else {
            // INSERT
            $sql = "INSERT INTO configuracion_cuenta 
                (id_usuaria, permitir_amigos, perfil_privado, notificar_mensajes, notificar_comentarios, tamano_fuente, modo_oscuro, alto_contraste)
                VALUES (:id, :amigos, :privado, :notifMsg, :notifCom, :fuente, :oscuro, :contraste)";
        }

        $stmt = $con->prepare($sql);
        $stmt->execute([
            ":id" => $idUsuaria,
            ":amigos" => $datos['permitir_amigos'],
            ":privado" => $datos['perfil_privado'],
            ":notifMsg" => $datos['notificar_mensajes'],
            ":notifCom" => $datos['notificar_comentarios'],
            ":fuente" => $datos['tamano_fuente'],
            ":oscuro" => $datos['modo_oscuro'],
            ":contraste" => $datos['alto_contraste']
        ]);

        $this->close();
    }
}
