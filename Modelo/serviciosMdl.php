<?php
class ServiciosMdl
{
    private $con;

    private function conectarBD()
    {
        try {
            $this->con = new PDO(
                "mysql:host=localhost;dbname=shakti;charset=utf8mb4",
                "root",
                ""
            );
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->con->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->con->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (PDOException $e) {
            die("Error en la conexión: " . $e->getMessage());
        }
    }

    public function agregarServicio($idUsuaria, $nombreServicio)
    {
        $this->conectarBD();

        $sql = "INSERT INTO servicios_especialistas (id_usuaria, servicio) 
            VALUES (:id_usuaria, :servicio)
            ON DUPLICATE KEY UPDATE servicio = :servicio_actualizado";

        $stmt = $this->con->prepare($sql);

        $stmt->bindParam(':id_usuaria', $idUsuaria);
        $stmt->bindParam(':servicio', $nombreServicio);
        $stmt->bindParam(':servicio_actualizado', $nombreServicio);

        return $stmt->execute();
    }
    public function obtenerServiciosPorUsuaria($idUsuaria)
    {
        $this->conectarBD();

        $sql = "SELECT servicio FROM servicios_especialistas WHERE id_usuaria = :id_usuaria";
        $stmt = $this->con->prepare($sql);
        $stmt->bindParam(':id_usuaria', $idUsuaria);
        $stmt->execute();

        $servicios = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $servicios[] = $row['servicio'];
        }
        return $servicios;
    }
}
