<?php
class organizacionesModelo {
    private $db;

    public function __construct() {
        require_once '../../Modelo/conexion.php';
        $this->db = (new ConectarDB())->open();
    }

    public function getAll() {
        try {
            $sql = "SELECT id, nombre, descripcion, numero, imagen, estatus FROM organizaciones";
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
            $sql = "SELECT id, nombre, descripcion, numero, imagen, estatus FROM organizaciones WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    public function create($nombre, $descripcion, $numero, $imagen, $estatus = 1) {
        try {
            $sql = "INSERT INTO organizaciones (nombre, descripcion, numero, imagen, estatus) VALUES (:nombre, :descripcion, :numero, :imagen, :estatus)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
            $stmt->bindParam(':numero', $numero, PDO::PARAM_STR);
            $stmt->bindParam(':imagen', $imagen, PDO::PARAM_LOB);
            $stmt->bindParam(':estatus', $estatus, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function update($id, $nombre, $descripcion, $numero, $imagen, $estatus = 1) {
        try {
            $sql = "UPDATE organizaciones SET nombre = :nombre, descripcion = :descripcion, numero = :numero, imagen = :imagen, estatus = :estatus WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
            $stmt->bindParam(':numero', $numero, PDO::PARAM_STR);
            $stmt->bindParam(':imagen', $imagen, PDO::PARAM_LOB, $imagen ? PDO::PARAM_LOB : PDO::PARAM_NULL);
            $stmt->bindParam(':estatus', $estatus, PDO::PARAM_INT);
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