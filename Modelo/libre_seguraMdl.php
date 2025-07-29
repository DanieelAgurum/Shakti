<?php
date_default_timezone_set('America/Mexico_City');

class Legales
{
    private $con;
    private $portada;
    private $titulo;
    private $documento;
    private $descripcion;


    public function conectarBD()
    {
        $this->con = mysqli_connect("localhost", "root", "", "shakti");
        if (!$this->con) {
            die("Problemas con la conexión a la base de datos: " . mysqli_connect_error());
        }
    }

    public function inicializar($portada, $titulo, $documento, $descripcion)
    {
        $this->portada = $this->leerArchivo($portada);
        $this->titulo = $titulo;
        $this->documento = $this->leerArchivo($documento);
        $this->descripcion = $descripcion;
    }


    private function leerArchivo($archivo)
    {
        if ($archivo && $archivo['error'] === UPLOAD_ERR_OK) {
            return file_get_contents($archivo['tmp_name']);
        }
        return null;
    }

    public function agregar()
    {
        $this->conectarBD();
        $stmt = $this->con->prepare("INSERT INTO legales (portada, titulo, documento, descripcion, fecha) VALUES (?, ?, ?, ?, NOW())");
        if (!$stmt) {
            die("Error en la preparación: " . $this->con->error);
        }

        $stmt->bind_param("ssss", $this->portada, $this->titulo, $this->documento, $this->descripcion);
        $stmt->send_long_data(3, $this->portada);
        $stmt->send_long_data(1, $this->documento);

        $resultado = $stmt->execute();

        if ($resultado) {
            return true;
        } else {
            return false;
        }
    }

    public function actualizar($id_legal, $portada, $titulo, $documento, $descripcion)
    {
        $this->conectarBD();
        $nuevaPortada = $this->leerArchivo($portada);
        $nuevoDocumento = $this->leerArchivo($documento);

        if ($nuevoDocumento !== null && $nuevaPortada !== null) {
            $stmt = $this->con->prepare("UPDATE legales SET portada = ?, titulo = ?, documento = ?, descripcion = ?, fecha = NOW() WHERE id_legal = ?");
            $stmt->bind_param("ssssi", $nuevaPortada,$titulo, $nuevoDocumento, $descripcion, $id_legal);
            $stmt->send_long_data(3, $nuevaPortada);
            $stmt->send_long_data(1, $nuevoDocumento);
        } elseif ($nuevoDocumento !== null) {
            $stmt = $this->con->prepare("UPDATE legales SET titulo = ?, documento = ?, descripcion = ?, fecha = NOW() WHERE id_legal = ?");
            $stmt->bind_param("sssi", $titulo, $nuevoDocumento, $descripcion, $id_legal);
            $stmt->send_long_data(1, $nuevoDocumento);
        } elseif ($nuevaPortada !== null) {
            $stmt = $this->con->prepare("UPDATE legales SET  portada = ?, titulo = ?, descripcion = ?, fecha = NOW() WHERE id_legal = ?");
            $stmt->bind_param("sssi", $nuevaPortada,$titulo, $descripcion, $id_legal);
            $stmt->send_long_data(2, $nuevaPortada);
        } else {
            $stmt = $this->con->prepare("UPDATE legales SET titulo = ?, descripcion = ?, fecha = NOW() WHERE id_legal = ?");
            $stmt->bind_param("ssi", $titulo, $descripcion, $id_legal);
        }


        $resultado = $stmt->execute();
        $stmt->close();

        if ($resultado) {
            return true;
        } else {
            return false;
        }
    }

    public function eliminar($id_legal)
    {
        $this->conectarBD();

        $stmt = $this->con->prepare("DELETE FROM legales WHERE id_legal = ?");
        $stmt->bind_param("i", $id_legal);

        $resultado = $stmt->execute();
        $stmt->close();

        if ($resultado) {
            return true;
        } else {
            return false;
        }
    }
}
