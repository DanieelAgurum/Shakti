<?php
date_default_timezone_set('America/Mexico_City');

class Testimonios
{
    private $db;


    public function conectarBD()
    {
        try {
            $pdo = new PDO("mysql:host=localhost;dbname=shakti", "root", "", [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
            ]);
            return $pdo;
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function guardarTestimonio($usuariaId, $calificacion, $opinion)
    {
        $sql = "INSERT INTO testimonios (id_usuaria, calificacion, opinion)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE calificacion = VALUES(calificacion), opinion = VALUES(opinion)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$usuariaId, $calificacion, $opinion]);
    }


    public function obtenerTestimonios()
    {
        $sql = "SELECT 
                t.*, 
                CONCAT(u.nombre, ' ', u.apellidos) AS nombre,
                u.foto AS foto
            FROM testimonios t
            INNER JOIN usuarias u ON t.id_usuaria = u.id
            ORDER BY t.fecha DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerTestimonioPorId($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM testimonios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
