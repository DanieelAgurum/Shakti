<?php
date_default_timezone_set('America/Mexico_City');
class Comentario
{
    private function conectarBD()
    {
        $con = mysqli_connect("localhost", "root", "", "shakti");
        if (!$con) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Error de conexión a la base de datos'
            ]);
            exit;
        }
        return $con;
    }

    public function agregarComentario($contenido, $idPublicacion, $idUsuaria, $idPadre = null)
    {
        $conn = $this->conectarBD();

        if ($idPadre === null) {
            $query = "INSERT INTO comentarios (id_publicacion, id_usuaria, comentario, fecha_comentario) VALUES (?, ?, ?, NOW())";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("iis", $idPublicacion, $idUsuaria, $contenido);
        } else {
            $query = "INSERT INTO comentarios (id_publicacion, id_usuaria, comentario, id_padre, fecha_comentario) VALUES (?, ?, ?, ?, NOW())";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("iisi", $idPublicacion, $idUsuaria, $contenido, $idPadre);
        }

        if ($stmt->execute()) {
            $idInsertado = $stmt->insert_id;
            $stmt->close();
            $conn->close();
            return $idInsertado;
        } else {
            $stmt->close();
            $conn->close();
            return false;
        }
    }


    public function obtenerComentariosPorPublicacion($idPublicacion)
    {
        $conn = $this->conectarBD();

        $query = "SELECT 
                c.id_comentario, 
                c.comentario, 
                c.fecha_comentario AS fecha, 
                c.id_padre,
                u.nombre 
              FROM comentarios c 
              JOIN usuarias u ON c.id_usuaria = u.id 
              WHERE c.id_publicacion = ? 
              ORDER BY c.fecha_comentario ASC";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $idPublicacion);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $comentarios = [];
        while ($fila = $resultado->fetch_assoc()) {
            $comentarios[] = $fila;
        }

        $stmt->close();
        $conn->close();

        return $comentarios;
    }
}
