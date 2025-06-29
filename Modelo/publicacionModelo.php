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
            return false;
        }
    }

    // Obtiene todas las publicaciones con el nickname del autor
    public function obtenerTodasConNickname() {
        try {
            $sql = "SELECT p.*, u.nickname 
                    FROM publicacion p
                    JOIN usuarias u ON p.id_usuarias = u.id_usuaria
                    ORDER BY p.fecha_publicacion DESC";
            $stmt = $this->conn->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Obtiene todas las publicaciones
    public function obtenerTodas() {
        try {
            $sql = "SELECT * FROM publicacion ORDER BY fecha_publicacion DESC";
            $stmt = $this->conn->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Obtiene publicaciones solo de una usuaria específica
    public function obtenerPorUsuaria($id_usuaria) {
        try {
            $sql = "SELECT * FROM publicacion WHERE id_usuarias = :id_usuaria ORDER BY fecha_publicacion DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_usuaria', $id_usuaria);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Obtiene una publicación por su ID
    public function obtenerPorId($id_publicacion) {
        try {
            $sql = "SELECT * FROM publicacion WHERE id_publicacion = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id_publicacion);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    // Actualiza una publicación existente (solo si ya validaste que es del usuario correcto)
    public function actualizar($id, $titulo, $contenido) {
        try {
            $sql = "UPDATE publicacion SET titulo = :titulo, contenido = :contenido WHERE id_publicacion = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':titulo', $titulo);
            $stmt->bindParam(':contenido', $contenido);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    // Elimina una publicación si pertenece a la usuaria
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
