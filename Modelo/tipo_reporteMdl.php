<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/obtenerLink/obtenerLink.php';

class TipoReporteMdl
{
    private $nombre;
    private $tipo;
    private $con;
    private $urlBase;

    public function __construct()
    {
        $this->urlBase = function_exists('getBaseUrl') ? getBaseUrl() : '';
    }

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

        $queryTipo4 = "SELECT * FROM tipo_reporte WHERE nombre_reporte = ? AND tipo_objetivo = 4";
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

    public function eliminarTipo($id)
    {
        $this->conectarBD();

        $sql = "DELETE FROM tipo_reporte WHERE id_tipo_reporte = ?";
        $stmt = $this->con->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                $stmt->close();
                $this->cerrarBD();
                header("Location: " . $this->urlBase . "/Vista/admin/tipos_reportes.php?estado=eliminado");
                exit;
            } else {
                $stmt->close();
                $this->cerrarBD();
                header("Location: " . $this->urlBase . "/Vista/admin/tipos_reportes.php?estado=error_eliminar");
                exit;
            }
        } else {
            $this->cerrarBD();
            die("Error en prepare: " . $this->con->error);
        }
    }

    public function modificarDatos($id, $nombre, $tipo)
    {
        $con = $this->conectarBD();

        // Evitar errores por espacios y proteger mínimamente
        $id = intval($id);
        $nombre = trim($nombre);
        $tipo = intval($tipo);

        // Si el nuevo tipo es 4 (Todos)
        if ($tipo == 4) {
            $sql = "SELECT * FROM tipo_reporte 
                WHERE nombre_reporte = '$nombre' 
                AND tipo_objetivo = 4 
                AND id_tipo_reporte != $id";
            $consul = mysqli_query($con, $sql);
            if ($consul && mysqli_num_rows($consul) > 0) {
                return json_encode([
                    'opcion' => 1,
                    'mensaje' => 'Ya existe este tipo con opción "Todos"'
                ]);
            }
        }

        // Si el nuevo tipo es 1, 2 o 3
        if (in_array($tipo, [1, 2, 3])) {
            // Validar si ya existe ese nombre en el mismo tipo
            $sql = "SELECT * FROM tipo_reporte 
                WHERE nombre_reporte = '$nombre' 
                AND tipo_objetivo = $tipo 
                AND id_tipo_reporte != $id";
            $consul = mysqli_query($con, $sql);
            if ($consul && mysqli_num_rows($consul) > 0) {
                return json_encode([
                    'opcion' => 1,
                    'mensaje' => 'Ya existe este tipo en ese objetivo específico'
                ]);
            }

            // Validar si ya existe ese nombre con tipo 4 (Todos)
            $sql = "SELECT * FROM tipo_reporte 
                WHERE nombre_reporte = '$nombre' 
                AND tipo_objetivo = 4 
                AND id_tipo_reporte != $id";
            $consul = mysqli_query($con, $sql);
            if ($consul && mysqli_num_rows($consul) > 0) {
                return json_encode([
                    'opcion' => 1,
                    'mensaje' => 'Este tipo ya existe como "Todos" y no puede duplicarse'
                ]);
            }
        }

        // Si pasó todas las validaciones, actualizar
        $sql = "UPDATE tipo_reporte 
            SET nombre_reporte = '$nombre', tipo_objetivo = $tipo 
            WHERE id_tipo_reporte = $id";
        $actualizar = mysqli_query($con, $sql);

        if ($actualizar) {
            return json_encode([
                'opcion' => 0,
                'mensaje' => 'Tipo de reporte actualizado correctamente'
            ]);
        } else {
            return json_encode([
                'opcion' => 1,
                'mensaje' => 'Error al actualizar los datos'
            ]);
        }
    }

    public function verTipos()
    {
        $this->conectarBD();
        $sql = "SELECT id_tipo_reporte, nombre_reporte, tipo_objetivo FROM tipo_reporte ORDER BY id_tipo_reporte DESC;";
        $result = $this->con->query($sql);

        if (!$result || $result->num_rows === 0) {
            echo '<tr><td colspan="4" class="text-center"><h4>Sin registros</h4></td></tr>';
            return;
        }

        while ($row = $result->fetch_assoc()) {
            $id     = htmlspecialchars($row['id_tipo_reporte']);
            $nombre = htmlspecialchars($row['nombre_reporte']);
            $tipo   = htmlspecialchars($row['tipo_objetivo']);
            $nombreTipo = "";

            switch ($tipo) {
                case 1:
                    $nombreTipo = "Contenido";
                    break;
                case 2:
                    $nombreTipo = "Usuarias";
                    break;
                case 3:
                    $nombreTipo = "Post";
                    break;
                default:
                    $nombreTipo = "Indefinido";
                    break;
            }
            echo '<tr>';
            echo "<td>$id</td>";
            echo "<td>$nombre</td>";
            echo "<td>" . $nombreTipo . "</td>";
            echo '<td class="text-center">
            <button type="button" onclick="modificarDatos(' . $id . ', \'' . addslashes($nombre) . ')" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modificarModal"> <i class="fa-solid fa-pen"></i> Editar
            </button> 
        <button type="button"
        class="btn btn-danger btn-sm btnEliminar" data-id="' . $id . '"
        data-nombre="' . addslashes($nombre) . '"
        data-bs-toggle="modal"
        data-bs-target="#miModal">
        <i class="fa-solid fa-eraser"></i> Eliminar</button></td>';

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
