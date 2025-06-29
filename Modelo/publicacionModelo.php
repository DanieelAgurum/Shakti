<?php
require_once __DIR__ . '/conexion.php';

class PublicacionModelo {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new ConectarDB();
        $this->conn = $this->db->open();
    }

    // Guarda una publicación nueva
    public function guardar($titulo, $contenido, $id_usuaria) {
        try {
            $sql = "INSERT INTO publicacion (titulo, contenido, fecha_publicacion, id_usuarias)
                    VALUES (:titulo, :contenido, NOW(), :id_usuaria)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':titulo', $titulo);
            $stmt->bindParam(':contenido', $contenido);
            $stmt->bindParam(':id_usuaria', $id_usuaria);
            return $stmt->execute();
        } catch (PDOException $e) {
            // Aquí podrías hacer un log del error si quieres
            return false;
        }
    }

    // Obtiene todas las publicaciones ordenadas por fecha descendente
    public function obtenerTodas() {
        try {
            $sql = "SELECT * FROM publicacion ORDER BY fecha_publicacion DESC";
            $stmt = $this->conn->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Borra una publicación solo si pertenece a la usuaria
    public function borrar($id, $id_usuaria) {
        try {
            $sql = "DELETE FROM publicacion WHERE id_publicacion = :id AND id_usuarias = :id_usuaria";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':id_usuaria', $id_usuaria);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function __destruct() {
        $this->db->close();
    }
}
