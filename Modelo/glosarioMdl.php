<?php
require_once 'Conexion.php';

class GlosarioMdl extends ConectarDB
{
    private $icono;
    private $titulo;
    private $concepto;

    public function inicializar($icono, $titulo, $concepto)
    {
        // Si no contiene "fa-" se asigna icono por defecto
        if (strpos($icono, 'fa-') === false) {
            $icono = 'fa-solid fa-car-front text-success fs-3';  // Solo clases, sin <i>
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

    public function modificarGlosario($id)
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

            // Verificar si el título existe en otro registro diferente al actual
            $verificar = $conexion->prepare("SELECT COUNT(*) FROM glosario WHERE titulo = :titulo AND id_glosario != :id");
            $verificar->bindParam(':titulo', $this->titulo);
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

            $sql = "UPDATE glosario SET icono = :icono, titulo = :titulo, concepto = :concepto WHERE id_glosario = :id";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':icono', $this->icono);
            $stmt->bindParam(':titulo', $this->titulo);
            $stmt->bindParam(':concepto', $this->concepto);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $this->close();

            return json_encode([
                'opcion' => 1,
                'mensaje' => 'Glosario modificado correctamente.'
            ]);
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
                return json_encode([
                    'opcion' => 0,
                    'mensaje' => 'No se pudo conectar a la base de datos.'
                ]);
            }

            $sql = "DELETE FROM glosario WHERE id_glosario = :id";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $this->close();

            return json_encode([
                'opcion' => 1,
                'mensaje' => 'Glosario eliminado correctamente.'
            ]);
        } catch (PDOException $e) {
            return json_encode([
                'opcion' => 0,
                'mensaje' => 'Error al eliminar glosario: ' . $e->getMessage()
            ]);
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
                $titulo_js = json_encode($fila['titulo']);
                $concepto_js = json_encode($fila['concepto']);

                echo '<tr>';
                echo "<td>{$contador}</td>";
                echo '<td>' . $fila['icono'] . '</td>';
                echo "<td>" . htmlspecialchars($fila['titulo']) . "</td>";
                echo "<td>" . $fila['concepto'] . "</td>";
                echo '<td class="text-center">';
                echo "<button type='button' onclick='modificarDatos({$id}, " . json_encode($fila['icono']) . ", {$titulo_js}, {$concepto_js})' class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#modificarModal'>";
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
        $conexion = $this->open();
        $sql = "SELECT * FROM glosario ORDER BY id_glosario DESC";
        $consulta = $conexion->prepare($sql);
        $consulta->execute();

        while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
            echo '<div class="col">
                    <div class="card border-0 shadow-sm h-100 animate__animated animate__fadeInLeft">
                        <div class="card-body">
                            <h5 class="card-title">';
            echo $fila['icono'];
            echo $fila['titulo'];
            echo '</h5> ';
            echo $fila['concepto'];
            echo '</div>
                    </div>
                </div>
            ';
        }
    }
}
