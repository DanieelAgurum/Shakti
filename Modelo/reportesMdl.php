<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/obtenerLink/obtenerLink.php';

class reportesMdl
{
    private $nickname;
    private $publicacion;
    private $tipo;
    private $id_reporto;
    private $tipoRep;
    public $con;
    private $urlBase;

    public function base()
    {
        $this->urlBase = function_exists('getBaseUrl') ? getBaseUrl() : '';
    }

    public function conectarBD()
    {
        $this->con = new mysqli("localhost", "root", "", "shakti");

        if ($this->con->connect_error) {
            echo json_encode([
                'success' => false,
                'message' => 'Error en la conexión a la base de datos: ' . $this->con->connect_error
            ]);
            exit;
        }
    }

    public function inicializar($nickname, $publicacion, $tipo, $id_reporto, $tipoRep)
    {
        $this->nickname = $nickname;
        $this->publicacion = $publicacion;
        $this->tipo = $tipo;
        $this->id_reporto = $id_reporto;
        $this->tipoRep = $tipoRep;
    }

    public function agregarReporte()
    {
        $this->conectarBD();

        if (empty($this->nickname) || empty($this->publicacion) || empty($this->tipo) || empty($this->id_reporto)) {
            return json_encode([
                'opcion' => 1,
                'mensaje' => 'Seleccione una opción válida.'
            ]);
        }

        $id_reportada = null;

        if ($this->tipoRep == 3) {
            $sqlAutor = "SELECT id_usuarias FROM publicacion WHERE id_publicacion = ?";
            $stmtAutor = $this->con->prepare($sqlAutor);
            $stmtAutor->bind_param("i", $this->publicacion);
            $stmtAutor->execute();
            $resultado = $stmtAutor->get_result();

            if ($resultado->num_rows === 0) {
                return json_encode([
                    'opcion' => 1,
                    'mensaje' => 'La publicación no existe.'
                ]);
            }

            $row = $resultado->fetch_assoc();
            $id_reportada = $row['id_usuarias'];

            if ($id_reportada == $this->id_reporto) {
                return json_encode([
                    'opcion' => 1,
                    'mensaje' => 'No puedes reportar tu propia publicación.'
                ]);
            }
        }

        if ($this->tipoRep == 2) {
            $sqlEspecialista = "SELECT id FROM usuarias WHERE nickname = ?";
            $stmtEspecialista = $this->con->prepare($sqlEspecialista);
            $stmtEspecialista->bind_param("s", $this->nickname);
            $stmtEspecialista->execute();
            $resultado = $stmtEspecialista->get_result();

            $row = $resultado->fetch_assoc();
            $id_reportada = $row['id'];

            if ($resultado->num_rows === 0) {
                return json_encode([
                    'opcion' => 1,
                    'mensaje' => 'El especialista no existe.'
                ]);
            }
        }


        $sqlVerificar = "SELECT id_reporte FROM reportar WHERE id_usuaria = ? AND id_publicacion = ?";
        $stmtVerificar = $this->con->prepare($sqlVerificar);
        $stmtVerificar->bind_param("ii", $this->id_reporto, $this->publicacion);
        $stmtVerificar->execute();
        $verificacion = $stmtVerificar->get_result();

        if ($verificacion->num_rows > 0) {
            if ($this->tipoRep == 2) {
                return json_encode([
                    'opcion' => 1,
                    'mensaje' => 'Ya reportaste a este especialista.'
                ]);
            } else if ($this->tipoRep == 3) {
                return json_encode([
                    'opcion' => 3,
                    'mensaje' => 'Ya reportaste este contenido.'
                ]);
            }
        }

        $sqlInsertar = "INSERT INTO reportar (id_tipo_reporte, id_usuaria, id_reportada, id_publicacion, fecha) 
                    VALUES (?, ?, ?, ?, NOW())";
        $stmtInsertar = $this->con->prepare($sqlInsertar);
        $stmtInsertar->bind_param("iiii", $this->tipo, $this->id_reporto, $id_reportada, $this->publicacion);

        if ($stmtInsertar->execute()) {
            return json_encode([
                'opcion' => 0,
                'mensaje' => 'Reporte enviado correctamente.'
            ]);
        } else {
            return json_encode([
                'opcion' => 1,
                'mensaje' => 'Error al enviar el reporte.'
            ]);
        }
    }

    public function verReportes()
    {
        $this->conectarBD();

        // Consultamos los reportes agrupados por publicación y usuaria reportada
        $sql = "SELECT u.id AS id_usuaria, u.nombre AS nombre_usuaria, r.id_publicacion
            FROM reportar r
            JOIN usuarias u ON r.id_reportada = u.id
            GROUP BY u.id, u.nombre, r.id_publicacion
            ORDER BY MAX(r.fecha) DESC";
        $consulta = $this->con->query($sql);

        // Consultamos motivos por usuaria y publicación
        $sqlMotivos = "SELECT r.id_reportada, r.id_publicacion, tp.nombre_reporte, tp.tipo_objetivo, COUNT(*) AS total
                   FROM reportar r
                   JOIN tipo_reporte tp ON r.id_tipo_reporte = tp.id_tipo_reporte
                   GROUP BY r.id_reportada, r.id_publicacion, tp.nombre_reporte, tp.tipo_objetivo";
        $consultaMotivos = $this->con->query($sqlMotivos);

        // Array para motivos agrupados por usuaria y publicación
        $motivosPorUsuariaPublicacion = [];

        // Array para guardar tipo por usuaria y publicación
        $tiposPorUsuariaPublicacion = [];

        while ($fila = $consultaMotivos->fetch_assoc()) {
            $idUsuaria = $fila['id_reportada'];
            $idPublicacion = $fila['id_publicacion'];
            $motivo = $fila['nombre_reporte'];
            $total = $fila['total'];
            $tipoReporte = $fila['tipo_objetivo'];

            if (!isset($motivosPorUsuariaPublicacion[$idUsuaria][$idPublicacion])) {
                $motivosPorUsuariaPublicacion[$idUsuaria][$idPublicacion] = [];
                $tiposPorUsuariaPublicacion[$idUsuaria][$idPublicacion] = $tipoReporte;
            }

            $motivosPorUsuariaPublicacion[$idUsuaria][$idPublicacion][] = "{$motivo} ({$total})";
        }

        $num = 1;
        if ($consulta->num_rows > 0) {
            while ($reporte = $consulta->fetch_assoc()) {
                $idUsuaria = $reporte['id_usuaria'];
                $nombre = ucwords(strtolower($reporte['nombre_usuaria']));
                $idPublicacion = $reporte['id_publicacion'];

                // Obtener contenido de la publicación
                $contenido = $this->obtenerContenidoPublicacion($idPublicacion);
                if (!$contenido) {
                    $contenido = "Sin contenido disponible";
                }

                $tipoReporte = $tiposPorUsuariaPublicacion[$idUsuaria][$idPublicacion] ?? 0;

                // Traducir tipo
                switch ($tipoReporte) {
                    case 1:
                        $tipoTexto = "Contenido";
                        break;
                    case 2:
                        $tipoTexto = "Usuario";
                        break;
                    case 3:
                        $tipoTexto = "Posts";
                        break;
                    default:
                        $tipoTexto = "Indefinido";
                        break;
                }

                // Motivos formateados
                $motivos = isset($motivosPorUsuariaPublicacion[$idUsuaria][$idPublicacion])
                    ? implode(', ', $motivosPorUsuariaPublicacion[$idUsuaria][$idPublicacion])
                    : 'Sin motivos';

                echo <<<HTML
                    <tr>
                        <td>{$num}</td>
                        <td>{$nombre}</td>
                        <td>{$contenido}</td>
                        <td>{$tipoTexto}</td>
                        <td>{$motivos}</td>
                        <td>   
                            <button type="button" class="btn btn-danger btn-sm btnEliminar" data-id="{$idPublicacion}" data-nombre="{$nombre}" data-contenido="{$tipoTexto}" data-bs-toggle="modal" data-bs-target="#miModal">                        
                                <i class="fa-solid fa-eraser"></i> Eliminar
                            </button>
                        </td>
                    </tr>
                 HTML;
                $num++;
            }
        } else {
            echo "<tr><td colspan='5'>No hay reportes.</td></tr>";
        }
    }

    public function obtenerContenidoPublicacion($id_publicacion)
    {
        $sql = "SELECT contenido FROM publicacion WHERE id_publicacion = ?";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("i", $id_publicacion);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 0) {
            return null;
        }

        $fila = $resultado->fetch_assoc();
        return $fila['contenido'];
    }

    public function eliminarReporte($id_publicacion, $tipo)
    {
        $this->base(); // para urlBase
        $this->conectarBD();

        // Eliminar comentarios asociados
        $sqlComentarios = "DELETE FROM comentarios WHERE id_publicacion = ?";
        $stmtComentarios = $this->con->prepare($sqlComentarios);
        $stmtComentarios->bind_param("i", $id_publicacion);
        $stmtComentarios->execute();

        $estado = "eliminado" . $tipo;

        // Eliminar reportes asociados
        $sqlReportes = "DELETE FROM reportar WHERE id_publicacion = ?";
        $stmtReportes = $this->con->prepare($sqlReportes);
        $stmtReportes->bind_param("i", $id_publicacion);
        $stmtReportes->execute();

        // Eliminar publicación
        $sqlPublicacion = "DELETE FROM publicacion WHERE id_publicacion = ?";
        $stmtPublicacion = $this->con->prepare($sqlPublicacion);
        $stmtPublicacion->bind_param("i", $id_publicacion);
        $resultado = $stmtPublicacion->execute();

        if ($resultado) {
            header("Location: " . $this->urlBase . "/Vista/admin/reportes.php?estado={$estado}");
            exit;
        } else {
            header("Location: " . $this->urlBase . "/Vista/admin/reportes.php?estado=error");
            exit;
        }
    }
}
