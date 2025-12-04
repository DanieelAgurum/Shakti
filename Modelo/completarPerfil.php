<?php
class Completar
{
    private $con;
    private $id_oficial;
    private $documento1;
    private $documento2;
    private $documento3;
    private $documento4;

    public function conectarBD()
    {
        $this->con = mysqli_connect(
            "localhost",
            "root",
            "",
            "shakti"
        );

        if (!$this->con) {
            die("❌ Problemas con la conexión a la base de datos: " . mysqli_connect_error());
        }

        mysqli_set_charset($this->con, "utf8mb4");
    }

    public function inicializar($id_oficial, $d1 = null, $d2 = null, $d3 = null, $d4 = null)
    {
        $this->id_oficial = $this->leerArchivo($id_oficial);
        $this->documento1 = $d1 ? $this->leerArchivo($d1) : null;
        $this->documento2 = $d2 ? $this->leerArchivo($d2) : null;
        $this->documento3 = $d3 ? $this->leerArchivo($d3) : null;
        $this->documento4 = $d4 ? $this->leerArchivo($d4) : null;
    }

    private function leerArchivo($archivo)
    {
        if ($archivo && $archivo['error'] === UPLOAD_ERR_OK) {
            return file_get_contents($archivo['tmp_name']);
        }
        return null;
    }

    public function completarPerfil($idUsuaria)
    {
        if (!$this->con) {
            $this->conectarBD();
        }

        $con = $this->con;

        // Verifica si ya existen documentos para esta usuaria
        $check = mysqli_query($con, "SELECT * FROM documentos WHERE id_usuaria = $idUsuaria");
        $fila = mysqli_fetch_assoc($check);
        $exists = $fila !== null;

        // Verifica si ya tiene guardada la identificación oficial
        $yaTieneIdOficial = $exists && !empty($fila['id_oficial']);

        // Si no tiene en la base y no subió una nueva, se redirige con error
        if (!$yaTieneIdOficial && (!isset($_FILES['id_oficial']) || $_FILES['id_oficial']['error'] !== 0)) {
            header("Location: ../Vista/especialista/perfil.php?status=error&message=Debe+subir+una+identificaci%C3%B3n+oficial");
            exit;
        }

        // Prepara los campos no vacíos
        $fields = [];
        if ($this->id_oficial !== null) $fields['id_oficial'] = $this->id_oficial;
        if ($this->documento1 !== null) $fields['documento1'] = $this->documento1;
        if ($this->documento2 !== null) $fields['documento2'] = $this->documento2;
        if ($this->documento3 !== null) $fields['documento3'] = $this->documento3;
        if ($this->documento4 !== null) $fields['documento4'] = $this->documento4;

        if (empty($fields)) {
            die("No se recibieron documentos válidos.");
        }

        if ($exists) {
            // UPDATE
            $updateParts = [];
            foreach ($fields as $key => $val) {
                $escapedVal = mysqli_real_escape_string($con, $val);
                $updateParts[] = "$key = '" . $escapedVal . "'";
            }
            $setClause = implode(', ', $updateParts);
            $query = "UPDATE documentos SET $setClause WHERE id_usuaria = $idUsuaria";
        } else {
            // INSERT
            $columns = implode(', ', array_merge(['id_usuaria'], array_keys($fields)));
            $escapedValues = array_map(function ($val) use ($con) {
                return mysqli_real_escape_string($con, $val);
            }, array_values($fields));
            $values = implode("', '", array_merge([$idUsuaria], $escapedValues));
            $query = "INSERT INTO documentos ($columns) VALUES ('$values')";
        }

        mysqli_query($con, $query) or die("Error en la consulta: " . mysqli_error($con));
    }


    public function mostrarDocumentos($idUsuaria)
    {
        if (!$this->con) {
            $this->conectarBD();
        }
        $sql = "SELECT id_oficial, documento1, documento2, documento3, documento4 FROM documentos WHERE id_usuaria = ?";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param('i', $idUsuaria);
        $stmt->execute();
        $resultado = $stmt->get_result();
        if ($resultado->num_rows > 0) {
            return $resultado->fetch_assoc();
        }
        return null;
    }

    public function eliminarCuenta($idUsuaria)
    {
        if (!$this->con) {
            $this->conectarBD();
        }

        $sql = "DELETE FROM usuarias WHERE id = ?";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param('i', $idUsuaria);

        return $stmt->execute();
    }

    public function cambiarEstatusCuenta($idUsuaria, $nuevoEstado)
    {
        if (!$this->con) {
            $this->conectarBD();
        }

        $sql = "UPDATE usuarias SET estatus = ? WHERE id = ?";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param('ii', $nuevoEstado, $idUsuaria);

        return $stmt->execute();
    }
}
