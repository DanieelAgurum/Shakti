<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/obtenerLink/obtenerLink.php';

class preguntasFrecuentesMdl
{
    private $pregunta;
    private $respuesta;
    private $conexion;
    private $urlBase;

    public function __construct()
    {
        $this->base();
    }

    public function base()
    {
        $this->urlBase = function_exists('getBaseUrl') ? getBaseUrl() : '';
    }
    public function conectarBD()
    {
        try {
            $this->conexion = new PDO(
                'mysql:host=localhost;dbname=shakti;charset=utf8mb4',
                'root',
                ''
            );

            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->conexion->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (PDOException $e) {
            echo json_encode([
                'opcion' => 0,
                'mensaje' => 'Error de conexión: ' . $e->getMessage()
            ]);
            exit;
        }
    }

    public function inicializar($pregunta, $respuesta)
    {
        $this->pregunta = trim($pregunta);
        $this->respuesta = trim($respuesta);
    }

    public function agregarPreguntaFrecuente()
    {
        if (empty($this->pregunta) || empty($this->respuesta)) {
            return json_encode([
                'opcion' => 0,
                'mensaje' => 'Los campos no pueden estar vacíos.'
            ]);
        }

        $this->conectarBD();

        // Verificar si la pregunta ya existe (consulta sensible a mayúsculas/minúsculas)
        $sqlVerificar = "SELECT COUNT(*) FROM preguntas_frecuentes WHERE pregunta = :pregunta";
        $stmtVerificar = $this->conexion->prepare($sqlVerificar);
        $stmtVerificar->bindParam(':pregunta', $this->pregunta);
        $stmtVerificar->execute();
        $existe = $stmtVerificar->fetchColumn();

        if ($existe > 0) {
            return json_encode([
                'opcion' => 0,
                'mensaje' => 'La pregunta ya existe.'
            ]);
        }

        // Insertar nueva pregunta
        $sqlAgregar = "INSERT INTO preguntas_frecuentes (pregunta, respuesta) VALUES (:pregunta, :respuesta)";
        $stmtAgregar = $this->conexion->prepare($sqlAgregar);
        $stmtAgregar->bindParam(':pregunta', $this->pregunta);
        $stmtAgregar->bindParam(':respuesta', $this->respuesta);

        if ($stmtAgregar->execute()) {
            return json_encode([
                'opcion' => 1,
                'mensaje' => 'ok'
            ]);
        } else {
            return json_encode([
                'opcion' => 0,
                'mensaje' => 'Inténtelo más tarde.'
            ]);
        }
    }

    public function modificarPregunta($id, $pregunta, $respuesta)
    {
        if (empty($pregunta) || empty($respuesta)) {
            return json_encode([
                'opcion' => 0,
                'mensaje' => 'Los campos no pueden estar vacíos.'
            ]);
        }

        $this->conectarBD();

        // Verificar si la nueva pregunta ya existe en otro registro (diferente al actual)
        $sqlVerificar = "SELECT COUNT(*) FROM preguntas_frecuentes WHERE pregunta = :pregunta AND id_preguntas != :id";
        $stmtVerificar = $this->conexion->prepare($sqlVerificar);
        $stmtVerificar->bindParam(':pregunta', $pregunta);
        $stmtVerificar->bindParam(':id', $id, PDO::PARAM_INT);
        $stmtVerificar->execute();
        $existe = $stmtVerificar->fetchColumn();

        if ($existe > 0) {
            return json_encode([
                'opcion' => 0,
                'mensaje' => 'Ya existe otra pregunta con ese contenido.'
            ]);
        }

        // Modificar la pregunta
        $sqlModificar = "UPDATE preguntas_frecuentes SET pregunta = :pregunta, respuesta = :respuesta WHERE id_preguntas = :id";
        $stmtModificar = $this->conexion->prepare($sqlModificar);
        $stmtModificar->bindParam(':pregunta', $pregunta);
        $stmtModificar->bindParam(':respuesta', $respuesta);
        $stmtModificar->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmtModificar->execute()) {
            return json_encode([
                'opcion' => 1,
                'mensaje' => 'ok'
            ]);
        } else {
            return json_encode([
                'opcion' => 0,
                'mensaje' => 'No se pudo actualizar. Inténtalo más tarde.'
            ]);
        }
    }

    public function eliminarPregunta($id)
    {
        $this->conectarBD();

        $sqlEliminar = "DELETE FROM preguntas_frecuentes WHERE id_preguntas = :id";
        $stmtEliminar = $this->conexion->prepare($sqlEliminar);
        $stmtEliminar->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmtEliminar->execute()) {
            header("Location: " . $this->urlBase . "/Vista/admin/preguntas_frecuentes?estado=eliminado");
            exit;
        } else {
            header("Location: " . $this->urlBase . "/Vista/admin/preguntas_frecuentes?estado=error");
            exit;
        }
    }

    public function verTodos()
    {
        $this->conectarBD();

        $sqlVer = "SELECT * FROM preguntas_frecuentes ORDER BY id_preguntas DESC";
        $stmtVer = $this->conexion->prepare($sqlVer);
        $stmtVer->execute();

        if ($stmtVer->rowCount() === 0) {
            echo '<tr><td colspan="4" class="text-center"><h4>Sin registros</h4></td></tr>';
            return;
        }
        $num = 1;
        while ($row = $stmtVer->fetch(PDO::FETCH_ASSOC)) {
            $id = (int)$row['id_preguntas'];
            $pregunta = addslashes($row['pregunta']);
            $respuesta = addslashes($row['respuesta']);

            echo '<tr>';
            echo '<td>' . htmlspecialchars($num, ENT_QUOTES, 'UTF-8') . '</td>';
            echo '<td>' . htmlspecialchars($row['pregunta'], ENT_QUOTES, 'UTF-8') . '</td>';
            echo '<td>' . htmlspecialchars($row['respuesta'], ENT_QUOTES, 'UTF-8') . '</td>';
            echo '<td class="text-center">';
            echo '<button type="button" onclick="modificarDatos(' . $id . ', \'' . $pregunta . '\', \'' . $respuesta . '\')" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modificarModal">';
            echo '<i class="fa-solid fa-pen"></i> Editar';
            echo '</button> ';
            echo '<button type="button" class="btn btn-danger btn-sm btnEliminar" 
        data-id="' . $id . '" 
        data-pregunta="' . htmlspecialchars($row['pregunta'], ENT_QUOTES, 'UTF-8') . '" 
        data-bs-toggle="modal" 
        data-bs-target="#miModal">';
            echo '<i class="fa-solid fa-eraser"></i> Eliminar';
            echo '</button>';
            echo '</td>';
            echo '</tr>';

            $num++;
        }
    }

    public function mostrarTodas()
    {
        $this->conectarBD();

        $sqlVer = "SELECT * FROM preguntas_frecuentes ORDER BY id_preguntas ASC";
        $stmtVer = $this->conexion->prepare($sqlVer);
        $stmtVer->execute();

        $preguntas = $stmtVer->fetchAll(PDO::FETCH_ASSOC);

        // Dividir las preguntas en grupos de 3 para cada carousel-item
        $grupos = array_chunk($preguntas, 3);
        $grupoIndex = 1;

        foreach ($grupos as $index => $grupo) {
            // Clase active solo para el primer item
            $activeClass = ($index === 0) ? "active" : "";

            echo '<div class="carousel-item ' . $activeClass . '">';
            echo '<div class="accordion accordion-flush" id="accordionGrupo' . $grupoIndex . '">';

            foreach ($grupo as $i => $row) {
                $pregunta = htmlspecialchars($row['pregunta'], ENT_QUOTES, 'UTF-8');
                $respuesta = htmlspecialchars($row['respuesta'], ENT_QUOTES, 'UTF-8');

                $headingId = "flush-heading" . $grupoIndex . chr(97 + $i);
                $collapseId = "flush-collapse" . $grupoIndex . chr(97 + $i);

                echo '<div class="accordion-item">';
                echo '  <h2 class="accordion-header" id="' . $headingId . '">';
                echo '    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#' . $collapseId . '" aria-expanded="false" aria-controls="' . $collapseId . '">';
                echo        $pregunta;
                echo '    </button>';
                echo '  </h2>';
                echo '  <div id="' . $collapseId . '" class="accordion-collapse collapse" data-bs-parent="#accordionGrupo' . $grupoIndex . '">';
                echo '    <div class="accordion-body">' . $respuesta . '</div>';
                echo '  </div>';
                echo '</div>';
            }

            echo '</div>';
            echo '</div>';

            $grupoIndex++;
        }
    }
}
