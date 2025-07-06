<?php
require_once __DIR__ . '/conexion.php';

class PublicacionModelo {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new ConectarDB();
        $this->conn = $this->db->open();
    }

    /**
     * Guarda una nueva publicación
     */
    public function guardar(string $titulo, string $contenido, int $id_usuaria): bool {
        try {
            $sql = "INSERT INTO publicacion (titulo, contenido, fecha_publicacion, id_usuarias)
                    VALUES (:titulo, :contenido, NOW(), :id_usuaria)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
            $stmt->bindParam(':contenido', $contenido, PDO::PARAM_STR);
            $stmt->bindParam(':id_usuaria', $id_usuaria, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al guardar publicación: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene todas las publicaciones con nickname de la usuaria
     */
    public function obtenerTodasConNickname(): array {
        try {
            $sql = "SELECT p.*, u.nickname 
                    FROM publicacion p
                    JOIN usuarias u ON p.id_usuarias = u.id
                    ORDER BY p.fecha_publicacion DESC";
            $stmt = $this->conn->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener publicaciones con nickname: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene solo las publicaciones creadas por administradores
     */
    public function obtenerPublicacionesAdmin(): array {
        try {
            $sql = "SELECT p.*, u.nickname 
                    FROM publicacion p
                    INNER JOIN usuarias u ON p.id_usuarias = u.id
                    WHERE u.id_rol = 3
                    ORDER BY p.fecha_publicacion DESC";
            $stmt = $this->conn->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener publicaciones de administradores: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene todas las publicaciones (sin nickname)
     */
    public function obtenerTodas(): array {
        try {
            $sql = "SELECT * FROM publicacion ORDER BY fecha_publicacion DESC";
            $stmt = $this->conn->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener todas las publicaciones: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene publicaciones de una usuaria específica
     */
    public function obtenerPorUsuaria(int $id_usuaria): array {
        try {
            $sql = "SELECT * FROM publicacion WHERE id_usuarias = :id_usuaria ORDER BY fecha_publicacion DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_usuaria', $id_usuaria, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener publicaciones por usuaria: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene una publicación por ID
     */
    public function obtenerPorId(int $id_publicacion): ?array {
        try {
            $sql = "SELECT * FROM publicacion WHERE id_publicacion = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id_publicacion, PDO::PARAM_INT);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado ?: null;
        } catch (PDOException $e) {
            error_log("Error al obtener publicación por ID: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Actualiza una publicación existente
     */
    public function actualizar(int $id, string $titulo, string $contenido): bool {
        try {
            $sql = "UPDATE publicacion SET titulo = :titulo, contenido = :contenido WHERE id_publicacion = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
            $stmt->bindParam(':contenido', $contenido, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al actualizar publicación: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Elimina una publicación si pertenece a la usuaria
     */
    public function borrar(int $id, int $id_usuaria): bool {
        try {
            $sql = "DELETE FROM publicacion WHERE id_publicacion = :id AND id_usuarias = :id_usuaria";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':id_usuaria', $id_usuaria, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al eliminar publicación (con verificación): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Elimina una publicación sin verificar usuaria (para administrador)
     */
    public function borrarSinVerificar(int $id_publicacion): bool {
        try {
            $sql = "DELETE FROM publicacion WHERE id_publicacion = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id_publicacion, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al eliminar publicación sin verificación: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cierra la conexión al destruir el objeto
     */
    public function __destruct() {
        $this->db->close();
    }
}
