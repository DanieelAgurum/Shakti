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
        $this->con->set_charset("utf8mb4");
        return $this->con;
    }

    public function cerrarBD()
    {
        if (isset($this->con)) {
            $this->con->close();
        }
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

        // Validar existencia nombre con tipo_objetivo=4
        $queryTipo4 = "SELECT 1 FROM tipo_reporte WHERE nombre_reporte = ? AND tipo_objetivo = 4";
        $stmtTipo4 = $this->con->prepare($queryTipo4);
        $stmtTipo4->bind_param("s", $this->nombre);
        $stmtTipo4->execute();
        $resultadoTipo4 = $stmtTipo4->get_result();

        if ($resultadoTipo4->num_rows > 0) {
            $stmtTipo4->close();
            $this->cerrarBD();
            return json_encode([
                "opcion" => 0,
                "mensaje" => "Ya existe un registro general con ese nombre (tipo 'Todas')"
            ]);
        }
        $stmtTipo4->close();

        // Validar existencia nombre y tipo_objetivo específico
        $query = "SELECT 1 FROM tipo_reporte WHERE nombre_reporte = ? AND tipo_objetivo = ?";
        $stmt = $this->con->prepare($query);
        $stmt->bind_param("ss", $this->nombre, $this->tipo);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $stmt->close();
            $this->cerrarBD();
            return json_encode([
                "opcion" => 0,
                "mensaje" => "Ya existe ese nombre para ese tipo"
            ]);
        }
        $stmt->close();

        // Insertar nuevo tipo de reporte
        $queryInsert = "INSERT INTO tipo_reporte (nombre_reporte, tipo_objetivo) VALUES (?, ?)";
        $stmtInsert = $this->con->prepare($queryInsert);
        $stmtInsert->bind_param("ss", $this->nombre, $this->tipo);

        if ($stmtInsert->execute()) {
            $stmtInsert->close();
            $this->cerrarBD();
            return json_encode([
                "opcion" => 1,
                "mensaje" => "Tipo de reporte agregado correctamente"
            ]);
        } else {
            $error = $this->con->error;
            $stmtInsert->close();
            $this->cerrarBD();
            return json_encode([
                "opcion" => 0,
                "mensaje" => "Error al insertar: " . $error
            ]);
        }
    }

    public function eliminarTipo($id)
    {
        $this->conectarBD();

        // Verificar reportes asociados
        $sql = "SELECT 1 FROM reportar WHERE id_tipo_reporte = ?";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->close();
            $this->cerrarBD();
            header("Location: " . $this->urlBase . "/Vista/admin/tipos_reportes.php?estado=hay_reportes");
            exit;
        }
        $stmt->close();

        // Eliminar tipo de reporte
        $sqlDelete = "DELETE FROM tipo_reporte WHERE id_tipo_reporte = ?";
        $stmtDelete = $this->con->prepare($sqlDelete);
        if (!$stmtDelete) {
            $this->cerrarBD();
            die("Error en prepare: " . $this->con->error);
        }
        $stmtDelete->bind_param("i", $id);

        if ($stmtDelete->execute()) {
            $stmtDelete->close();
            $this->cerrarBD();
            header("Location: " . $this->urlBase . "/Vista/admin/tipos_reportes.php?estado=eliminado");
            exit;
        } else {
            $stmtDelete->close();
            $this->cerrarBD();
            header("Location: " . $this->urlBase . "/Vista/admin/tipos_reportes.php?estado=error_eliminar");
            exit;
        }
    }

    public function modificarDatos($id, $nombre, $tipo)
    {
        $this->conectarBD();

        $id = intval($id);
        $nombre = trim($nombre);
        $tipo = intval($tipo);

        // Validaciones con consultas preparadas
        if ($tipo === 4) {
            $sql = "SELECT 1 FROM tipo_reporte WHERE nombre_reporte = ? AND tipo_objetivo = 4 AND id_tipo_reporte != ?";
            $stmt = $this->con->prepare($sql);
            $stmt->bind_param("si", $nombre, $id);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $stmt->close();
                $this->cerrarBD();
                return json_encode([
                    'opcion' => 1,
                    'mensaje' => 'Ya existe este tipo con opción "Todos"'
                ]);
            }
            $stmt->close();
        }

        if (in_array($tipo, [1, 2, 3])) {
            // Validar nombre + tipo_objetivo
            $sql = "SELECT 1 FROM tipo_reporte WHERE nombre_reporte = ? AND tipo_objetivo = ? AND id_tipo_reporte != ?";
            $stmt = $this->con->prepare($sql);
            $stmt->bind_param("sii", $nombre, $tipo, $id);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $stmt->close();
                $this->cerrarBD();
                return json_encode([
                    'opcion' => 1,
                    'mensaje' => 'Ya existe este tipo en ese objetivo específico'
                ]);
            }
            $stmt->close();

            // Validar que no exista como tipo 4 con mismo nombre
            $sql = "SELECT 1 FROM tipo_reporte WHERE nombre_reporte = ? AND tipo_objetivo = 4 AND id_tipo_reporte != ?";
            $stmt = $this->con->prepare($sql);
            $stmt->bind_param("si", $nombre, $id);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $stmt->close();
                $this->cerrarBD();
                return json_encode([
                    'opcion' => 1,
                    'mensaje' => 'Este tipo ya existe como "Todos" y no puede duplicarse'
                ]);
            }
            $stmt->close();
        }

        // Actualizar registro
        $sqlUpdate = "UPDATE tipo_reporte SET nombre_reporte = ?, tipo_objetivo = ? WHERE id_tipo_reporte = ?";
        $stmtUpdate = $this->con->prepare($sqlUpdate);
        $stmtUpdate->bind_param("sii", $nombre, $tipo, $id);

        if ($stmtUpdate->execute()) {
            $stmtUpdate->close();
            $this->cerrarBD();
            return json_encode([
                'opcion' => 0,
                'mensaje' => 'Tipo de reporte actualizado correctamente'
            ]);
        } else {
            $error = $this->con->error;
            $stmtUpdate->close();
            $this->cerrarBD();
            return json_encode([
                'opcion' => 1,
                'mensaje' => 'Error al actualizar los datos: ' . $error
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
            $this->cerrarBD();
            return;
        }

        while ($row = $result->fetch_assoc()) {
            $id = (int)$row['id_tipo_reporte'];
            $nombre = htmlspecialchars($row['nombre_reporte'], ENT_QUOTES, 'UTF-8');
            $tipo = (int)$row['tipo_objetivo'];
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
                case 4:
                    $nombreTipo = "Todas";
                    break;
                default:
                    $nombreTipo = "Indefinido";
                    break;
            }

            echo '<tr>';
            echo "<td>{$id}</td>";
            echo "<td>{$nombre}</td>";
            echo "<td>{$nombreTipo}</td>";
            echo '<td class="text-center">';
            echo '<button type="button" onclick="modificarDatos(' . $id . ', \'' . addslashes($nombre) . '\', ' . $tipo . ')" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modificarModal">';
            echo '<i class="fa-solid fa-pen"></i> Editar';
            echo '</button> ';
            echo '<button type="button" class="btn btn-danger btn-sm btnEliminar" data-id="' . $id . '" data-nombre="' . htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8') . '" data-bs-toggle="modal" data-bs-target="#miModal">';
            echo '<i class="fa-solid fa-eraser"></i> Eliminar';
            echo '</button>';
            echo '</td>';
            echo '</tr>';
        }

        $this->cerrarBD();
    }
}
?>