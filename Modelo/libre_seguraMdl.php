<?php
date_default_timezone_set('America/Mexico_City');

class Legales
{
    private $con;
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

    public function inicializar($titulo, $documento, $descripcion)
    {
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
        $stmt = $this->con->prepare("INSERT INTO legales (titulo, documento, descripcion, fecha) VALUES (?, ?, ?, NOW())");
        if (!$stmt) {
            die("Error en la preparación: " . $this->con->error);
        }

        $stmt->bind_param("sss", $this->titulo, $this->documento, $this->descripcion);
        $stmt->send_long_data(1, $this->documento);

        $resultado = $stmt->execute();

        if ($resultado) {
            return true;
        } else {
            return false;
        }
    }

    public function actualizar($id_legal, $titulo, $documento, $descripcion)
    {
        $this->conectarBD();
        $nuevoDocumento = $this->leerArchivo($documento);

        if ($nuevoDocumento !== null) {
            $stmt = $this->con->prepare("UPDATE legales SET titulo = ?, documento = ?, descripcion = ?, fecha = NOW() WHERE id_legal = ?");
            $stmt->bind_param("sssi", $titulo, $nuevoDocumento, $descripcion, $id_legal);
            $stmt->send_long_data(1, $nuevoDocumento);
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
