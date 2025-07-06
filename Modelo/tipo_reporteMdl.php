<?php
class TipoReporteMdl
{
    private $nombre;
    private $tipo;
    private $con;

    public function conectarBD()
    {
        $this->con = new mysqli("localhost", "root", "", "shakti");

        if ($this->con->connect_error) {
            die("Error de conexión: " . $this->con->connect_error);
        }
        return $this->con;
    }

    public function inicializar($nombre, $tipo)
    {
        $this->nombre = trim($nombre);
        $this->tipo = trim($tipo);
    }

    public function agregar()
    {
        $this->conectarBD();

        if (empty($this->nombre) || empty($this->tipo)) {
            return json_encode([
                "opcion" => 0,
                "mensaje" => "Faltan parámetros: nombre o tipo"
            ]);
        }

        // Verificar si ya existe el nombre con tipo 4
        $queryTipo4 = "SELECT 1 FROM tipo_reporte WHERE nombre_reporte = ? AND tipo_objetivo = 4";
        $stmtTipo4 = $this->con->prepare($queryTipo4);
        $stmtTipo4->bind_param("s", $this->nombre);
        $stmtTipo4->execute();
        $resultadoTipo4 = $stmtTipo4->get_result();

        if ($resultadoTipo4->num_rows > 0) {
            return json_encode([
                "opcion" => 0,
                "mensaje" => "Ya existe un registro general con ese nombre (tipo 'Todas')"
            ]);
        }

        // Verificar si ya existe el mismo nombre con el mismo tipo
        $query = "SELECT 1 FROM tipo_reporte WHERE nombre_reporte = ? AND tipo_objetivo = ?";
        $stmt = $this->con->prepare($query);
        $stmt->bind_param("ss", $this->nombre, $this->tipo);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            return json_encode([
                "opcion" => 0,
                "mensaje" => "Ya existe ese nombre para ese tipo"
            ]);
        }

        // Insertar si no hay conflictos
        $queryInsert = "INSERT INTO tipo_reporte (nombre_reporte, tipo_objetivo) VALUES (?, ?)";
        $stmtInsert = $this->con->prepare($queryInsert);
        $stmtInsert->bind_param("ss", $this->nombre, $this->tipo);

        if ($stmtInsert->execute()) {
            return json_encode([
                "opcion" => 1,
                "mensaje" => "Tipo de reporte agregado correctamente"
            ]);
        } else {
            return json_encode([
                "opcion" => 0,
                "mensaje" => "Error al insertar: " . $this->con->error
            ]);
        }
    }

    public function eliminarTipo()
    {
        echo "E";
    }

    public function verTipos()
    {
        $this->conectarBD();
        $sql = "SELECT id_tipo_reporte, nombre_reporte, tipo_objetivo FROM tipo_reporte";
        $result = $this->con->query($sql);

        if (!$result || $result->num_rows === 0) {
            echo '<tr><td colspan="4" class="text-center"><h4>Sin registros</h4></td></tr>';
            return;
        }

        while ($row = $result->fetch_assoc()) {
            $id     = htmlspecialchars($row['id_tipo_reporte']);
            $nombre = htmlspecialchars($row['nombre_reporte']);
            $tipo   = htmlspecialchars($row['tipo_objetivo']);

            echo '<tr>';
            echo "<td>$id</td>";
            echo "<td>$nombre</td>";
            echo "<td>$tipo</td>";
            echo '<td>
                <a href="editar.php?id=' . $id . '" class="btn btn-sm btn-outline-primary me-1">Editar</a>
                <button
                    type="button"
                    class="btn btn-danger btn-sm btnEliminar"
                    data-id="' . $id . '"
                    data-nombre="' . $nombre . '"
                    data-bs-toggle="modal"
                    data-bs-target="#miModal"
                >
                    <i class="fa-solid fa-eraser"></i> Eliminar
                </button>
              </td>';
            echo '</tr>';
        }
    }


    public function cerrarBD()
    {
        if (isset($this->con)) {
            $this->con->close();
        }
    }
}
