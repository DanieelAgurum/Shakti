<?php
class organizacionesModelo {
    private $db;

    public function __construct() {
        require_once '../../Modelo/conexion.php';
        $this->db = (new ConectarDB())->open();
    }

    public function getAll() {
        try {
            // Eliminado 'estatus' de la selección
            $sql = "SELECT id, nombre, descripcion, numero, imagen FROM organizaciones";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    public function getById($id) {
        try {
            // Eliminado 'estatus' de la selección
            $sql = "SELECT id, nombre, descripcion, numero, imagen FROM organizaciones WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    // Eliminado el parámetro 'estatus' del método create
    public function create($nombre, $descripcion, $numero, $imagen) {
        try {
            // Eliminado 'estatus' de la inserción SQL
            $sql = "INSERT INTO organizaciones (nombre, descripcion, numero, imagen) VALUES (:nombre, :descripcion, :numero, :imagen)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
            $stmt->bindParam(':numero', $numero, PDO::PARAM_STR);
            $stmt->bindParam(':imagen', $imagen, PDO::PARAM_LOB);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Eliminado el parámetro 'estatus' del método update
    public function update($id, $nombre, $descripcion, $numero, $imagen) {
        try {
            // Eliminado 'estatus' de la actualización SQL y del bindParam
            $sql = "UPDATE organizaciones SET nombre = :nombre, descripcion = :descripcion, numero = :numero, imagen = :imagen WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
            $stmt->bindParam(':numero', $numero, PDO::PARAM_STR);
            $stmt->bindParam(':imagen', $imagen, PDO::PARAM_LOB); // Eliminado PDO::PARAM_NULL condicional si imagen es null
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function delete($id) {
        try {
            $sql = "DELETE FROM organizaciones WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}