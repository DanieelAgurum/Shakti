<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/obtenerLink/obtenerLink.php';
require_once __DIR__ . '/conexion.php';

class GlosarioMdl extends ConectarDB
{
    private $icono;
    private $titulo;
    private $concepto;
    private $urlBase;

    public function __construct()
    {
        $this->urlBase = function_exists('getBaseUrl') ? getBaseUrl() : '';
    }

    public function inicializar($icono, $titulo, $concepto)
    {
        if (strpos($icono, 'fa-') === false) {
            $icono = '<i class="fa-solid fa-circle-exclamation" style="color: #005cfa;"></i>';
        }

        $this->icono = trim($icono);
        $this->titulo = trim($titulo);
        $this->concepto = trim($concepto);
    }

    public function agregarGlosario()
    {
        if (empty($this->titulo) || empty($this->concepto)) {
            return json_encode([
                'opcion' => 0,
                'mensaje' => 'Por favor, ingrese tanto el título como el concepto.'
            ]);
        }

        try {
            $conexion = $this->open();
            if (!$conexion) {
                return json_encode([
                    'opcion' => 0,
                    'mensaje' => 'No se pudo conectar a la base de datos.'
                ]);
            }

            $verificar = $conexion->prepare("SELECT COUNT(*) FROM glosario WHERE titulo = :titulo");
            $verificar->bindParam(':titulo', $this->titulo);
            $verificar->execute();
            $existe = $verificar->fetchColumn();

            if ($existe > 0) {
                $this->close();
                return json_encode([
                    'opcion' => 0,
                    'mensaje' => 'Ese título ya existe en el glosario.'
                ]);
            }

            $sql = "INSERT INTO glosario (icono, titulo, concepto) VALUES (:icono, :titulo, :concepto)";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':icono', $this->icono);
            $stmt->bindParam(':titulo', $this->titulo);
            $stmt->bindParam(':concepto', $this->concepto);
            $stmt->execute();

            $this->close();

            return json_encode([
                'opcion' => 1,
                'mensaje' => 'Glosario agregado correctamente.'
            ]);
        } catch (PDOException $e) {
            return json_encode([
                'opcion' => 0,
                'mensaje' => 'Error al agregar glosario: ' . $e->getMessage()
            ]);
        }
    }

    public function modificarGlosario($id, $icono, $titulo, $concepto)
    {
        $icono = trim($icono);
        $titulo = trim($titulo);
        $concepto = trim($concepto);

        if (empty($titulo) || empty($concepto)) {
            return json_encode([
                'opcion' => 0,
                'mensaje' => 'Por favor, ingrese tanto el título como el concepto.'
            ]);
        }

        if (strpos($icono, 'fa-') === false) {
            $icono = '<i class="fa-solid fa-circle-exclamation" style="color: #005cfa;"></i>';
        }

        try {
            $conexion = $this->open();
            if (!$conexion) {
                return json_encode([
                    'opcion' => 0,
                    'mensaje' => 'No se pudo conectar a la base de datos.'
                ]);
            }

            // Verificar duplicado
            $verificar = $conexion->prepare("SELECT COUNT(*) FROM glosario WHERE titulo = :titulo AND id_glosario != :id");
            $verificar->bindParam(':titulo', $titulo);
            $verificar->bindParam(':id', $id, PDO::PARAM_INT);
            $verificar->execute();
            $existe = $verificar->fetchColumn();

            if ($existe > 0) {
                $this->close();
                return json_encode([
                    'opcion' => 0,
                    'mensaje' => 'Ese título ya existe en otro término del glosario.'
                ]);
            }

            // Actualizar
            $sql = "UPDATE glosario SET icono = :icono, titulo = :titulo, concepto = :concepto WHERE id_glosario = :id";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':icono', $icono);
            $stmt->bindParam(':titulo', $titulo);
            $stmt->bindParam(':concepto', $concepto);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $resultado = $stmt->execute();

            $this->close();

            if ($resultado) {
                return json_encode([
                    'opcion' => 1,
                    'mensaje' => 'Glosario modificado correctamente.'
                ]);
            } else {
                return json_encode([
                    'opcion' => 0,
                    'mensaje' => 'No se pudo actualizar el glosario. Intente nuevamente.'
                ]);
            }
        } catch (PDOException $e) {
            return json_encode([
                'opcion' => 0,
                'mensaje' => 'Error al modificar glosario: ' . $e->getMessage()
            ]);
        }
    }


    public function eliminarGlosario($id)
    {
        try {
            $conexion = $this->open();
            if (!$conexion) {
                header("Location: " . $this->urlBase . "/Vista/admin/glosario.php?estado=error");
                exit;
            }

            $sql = "DELETE FROM glosario WHERE id_glosario = :id";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $resultado = $stmt->execute();

            $this->close();

            $estado = $resultado ? "eliminado" : "error";
            header("Location: " . $this->urlBase . "/Vista/admin/glosario.php?estado={$estado}");
            exit;
        } catch (PDOException $e) {
            header("Location: " . $this->urlBase . "/Vista/admin/glosario.php?estado=error");
            exit;
        }
    }

    public function verGlosario()
    {
        try {
            $conexion = $this->open();
            $sql = "SELECT * FROM glosario ORDER BY id_glosario DESC";
            $consulta = $conexion->prepare($sql);
            $consulta->execute();

            $contador = 1;
            while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
                $id = $fila['id_glosario'];
                $titulo_js = json_encode($fila['titulo']);
                $concepto_js = json_encode($fila['concepto']);
                $icono_js = json_encode($fila['icono']);

                echo '<tr>';
                echo "<td>{$contador}</td>";
                echo '<td>' . $fila['icono'] . '</td>';
                echo "<td>" . htmlspecialchars($fila['titulo']) . "</td>";
                echo "<td>" . $fila['concepto'] . "</td>";
                echo '<td class="text-center">';
                echo "<button type='button' onclick='modificarDatos({$id}, {$icono_js}, {$titulo_js}, {$concepto_js})' class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#modificarModal'>";
                echo '<i class="fa-solid fa-pen"></i> Editar';
                echo '</button> ';
                echo '<button type="button" class="btn btn-danger btn-sm btnEliminar" data-id="' . $id . '" data-titulo="' . htmlspecialchars($fila['titulo'], ENT_QUOTES, 'UTF-8') . '" data-bs-toggle="modal" data-bs-target="#miModal">';
                echo '<i class="fa-solid fa-eraser"></i> Eliminar';
                echo '</button>';
                echo '</td>';
                echo '</tr>';
                $contador++;
            }

            $this->close();
        } catch (PDOException $e) {
            echo '<tr><td colspan="5">Error al cargar el glosario: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
        }
    }

    public function mostrarGlosario()
    {
        try {
            $conexion = $this->open();
            $sql = "SELECT * FROM glosario ORDER BY id_glosario DESC";
            $consulta = $conexion->prepare($sql);
            $consulta->execute();

            while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
                echo '<div class="col">
                        <div class="card border-0 shadow-sm h-100 animate__animated animate__fadeInLeft">
                            <div class="card-body">
                                <h5 class="card-title">';
                echo $fila['icono'] . "  ";
                echo htmlspecialchars($fila['titulo']);
                echo '</h5>';
                echo $fila['concepto'];
                echo '</div>
                        </div>
                    </div>';
            }

            $this->close();
        } catch (PDOException $e) {
            echo '<p>Error al mostrar el glosario: ' . htmlspecialchars($e->getMessage()) . '</p>';
        }
    }
}
