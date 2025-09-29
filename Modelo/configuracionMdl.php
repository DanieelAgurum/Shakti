<?php
require_once "conexion.php";

class ConfiguracionMdl extends ConectarDB
{
    public function actualizarPassword($idUsuaria, $passwordHash)
    {
        $con = $this->open();
        $sql = "UPDATE usuarias SET contraseña = :pass WHERE id = :id";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(":pass", $passwordHash);
        $stmt->bindParam(":id", $idUsuaria, PDO::PARAM_INT);
        $stmt->execute();
        $this->close();
    }

    public function guardarToken($idUsuaria, $token)
    {
        $con = $this->open();
        $sql = "INSERT INTO tokens_contrasena (id_usuaria, token, fecha)
                VALUES (:id, :token, NOW())";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(":id", $idUsuaria, PDO::PARAM_INT);
        $stmt->bindParam(":token", $token);
        $stmt->execute();
        $this->close();
    }

    public function verificarToken($idUsuaria, $token)
    {
        $con = $this->open();
        $sql = "SELECT * FROM tokens_contrasena 
                WHERE id_usuaria = :id 
                  AND token = :token 
                  AND fecha >= NOW() - INTERVAL 15 MINUTE
                ORDER BY fecha DESC LIMIT 1";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(":id", $idUsuaria, PDO::PARAM_INT);
        $stmt->bindParam(":token", $token);
        $stmt->execute();
        $valido = $stmt->rowCount() > 0;
        $this->close();
        return $valido;
    }

    public function borrarToken($idUsuaria, $token)
    {
        $con = $this->open();
        $sql = "DELETE FROM tokens_contrasena WHERE id_usuaria = :id AND token = :token";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(":id", $idUsuaria, PDO::PARAM_INT);
        $stmt->bindParam(":token", $token);
        $stmt->execute();
        $this->close();
    }

    public function guardarConfiguracion($idUsuaria, $datos)
    {
        $con = $this->open();
        $sqlCheck = "SELECT id_config FROM configuraciones WHERE id_usuaria = :id LIMIT 1";
        $stmtCheck = $con->prepare($sqlCheck);
        $stmtCheck->bindParam(":id", $idUsuaria, PDO::PARAM_INT);
        $stmtCheck->execute();

        if ($stmtCheck->rowCount() > 0) {
            $sql = "UPDATE configuraciones SET 
        permitir_amigos = :amigos,
        perfil_privado = :privado,
        notificar_mensajes = :notifMsg,
        notificar_comentarios = :notifCom,
        tamano_fuente = :fuente,
        modo_oscuro = :oscuro,
        alto_contraste = :contraste
        WHERE id_usuaria = :id_usuaria";
        } else {
            $sql = "INSERT INTO configuraciones 
        (id_usuaria, permitir_amigos, perfil_privado, notificar_mensajes, notificar_comentarios, tamano_fuente, modo_oscuro, alto_contraste)
        VALUES (:id_usuaria, :amigos, :privado, :notifMsg, :notifCom, :fuente, :oscuro, :contraste)";
        }

        $stmt = $con->prepare($sql);
        $stmt->execute([
            ":id_usuaria" => $idUsuaria,
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

    public function obtenerConfiguracion($idUsuaria)
    {
        $con = $this->open();
        $sql = "SELECT * FROM configuraciones WHERE id_usuaria = :id LIMIT 1";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(":id", $idUsuaria, PDO::PARAM_INT);
        $stmt->execute();
        $config = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->close();
        return $config;
    }
    
}
