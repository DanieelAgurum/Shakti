<?php

class reportesMdl
{
    private $nickname;
    private $publicacion;
    private $tipo;
    private $id_reporto;
    public $con;

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

    public function inicializar($nickname, $publicacion, $tipo, $id_reporto)
    {
        $this->nickname = $nickname;
        $this->publicacion = $publicacion;
        $this->tipo = $tipo;
        $this->id_reporto = $id_reporto;
    }

    public function agregarReporte()
    {
        // Conectar a la base de datos
        $this->conectarBD();

        if (empty($this->nickname) || empty($this->publicacion) || empty($this->tipo) || empty($this->id_reporto)) {
            return json_encode([
                'opcion' => 1,
                'mensaje' => 'Seleccione una opción válida.'
            ]);
        }

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

        $sqlVerificar = "SELECT id_reporte FROM reportar WHERE id_usuaria = ? AND id_publicacion = ?";
        $stmtVerificar = $this->con->prepare($sqlVerificar);
        $stmtVerificar->bind_param("ii", $this->id_reporto, $this->publicacion);
        $stmtVerificar->execute();
        $verificacion = $stmtVerificar->get_result();

        if ($verificacion->num_rows > 0) {
            return json_encode([
                'opcion' => 1,
                'mensaje' => 'Ya reportaste este contenido.'
            ]);
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
                $nombre = $reporte['nombre_usuaria'];
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
                <td><button class="btn btn-danger btn-sm btnEliminar" data-idpub="{$idPublicacion}">Eliminar</button></td>
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
    
}
