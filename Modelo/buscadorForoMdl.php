<?php
class buscadorForoMdl
{
    private $con;
    private $buscar;

    // Conectar a la base de datos si no hay conexión previa
    public function conectarBD()
    {
        if (!$this->con) {
            $this->con = mysqli_connect("localhost", "root", "", "shakti");
            if (!$this->con) {
                die("Problemas con la conexión a la base de datos: " . mysqli_connect_error());
            }
        }
        return $this->con;
    }

    // Inicializa el texto de búsqueda
    public function inicializar($buscar)
    {
        $this->buscar = $buscar;
    }

    public function buscardor($limit = 10, $offset = 0)
    {
        $this->conectarBD();
        $idUsuaria = $_SESSION['id_usuaria'] ?? null;

        if (!empty($this->buscar)) {
            $sql = "SELECT p.titulo, p.contenido, p.id_publicacion, u.id, u.nickname, u.foto, u.id_rol 
                FROM publicacion p 
                JOIN usuarias u ON p.id_usuarias = u.id 
                WHERE u.id_rol = 1 AND (
                    p.contenido LIKE ? OR 
                    p.titulo LIKE ? OR 
                    u.nickname LIKE ?)
                LIMIT ? OFFSET ?";

            $stmt = $this->con->prepare($sql);
            if (!$stmt) {
                die("Error en la preparación de la consulta: " . $this->con->error);
            }
            $like = "%" . $this->buscar . "%";
            $stmt->bind_param("sssii", $like, $like, $like, $limit, $offset);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                while ($publicacion = $result->fetch_assoc()) {
                    $this->imprimirPublicacion($publicacion, $idUsuaria);
                }
            } else {
                if ($offset === 0) {
                    echo "<p>No se encontraron resultados para '" . htmlspecialchars($this->buscar) . "'</p>";
                }
            }
            $stmt->close();
        } else {
            $this->todos($limit, $offset);
        }
    }

    public function todos($limit = 10, $offset = 0)
    {
        
        $this->conectarBD();
        $idUsuaria = $_SESSION['id_usuaria'] ?? null;

        $sql = "SELECT p.titulo, p.contenido, p.id_publicacion, u.id, u.nickname, u.foto, u.id_rol 
            FROM publicacion p 
            JOIN usuarias u ON p.id_usuarias = u.id 
            WHERE u.id_rol = 1
            LIMIT $limit OFFSET $offset";

        $consulta = mysqli_query($this->con, $sql);

        if ($consulta && mysqli_num_rows($consulta) > 0) {
            while ($publicacion = mysqli_fetch_assoc($consulta)) {
                $this->imprimirPublicacion($publicacion, $idUsuaria);
            }
        } else {
            if ($offset === 0) {
                echo "<p>No hay publicaciones.</p>";
            }
        }
    }

    // Función para imprimir la publicación junto con likes y comentarios
    private function imprimirPublicacion($publicacion, $idUsuaria)
    {
        $idPublicacion = (int)$publicacion['id_publicacion'];

        // Contar likes
        $likesConsulta = mysqli_query($this->con, "SELECT COUNT(*) AS total FROM likes_publicaciones WHERE id_publicacion = $idPublicacion");
        $likes = ($likesConsulta && $row = mysqli_fetch_assoc($likesConsulta)) ? $row['total'] : 0;

        // Verificar si usuaria ya dio like
        $yaDioLike = false;
        if ($idUsuaria) {
            $verificarLike = mysqli_query($this->con, "SELECT 1 FROM likes_publicaciones WHERE id_usuaria = $idUsuaria AND id_publicacion = $idPublicacion");
            $yaDioLike = ($verificarLike && mysqli_num_rows($verificarLike) > 0);
        }

        $btnClass = $yaDioLike ? 'btn-danger' : 'btn-outline-danger';
        $iconClass = $yaDioLike ? 'bi-suit-heart-fill' : 'bi-suit-heart';

        // Obtener comentarios (requiere archivo de modelo Comentario)
        require_once __DIR__ . '/../modelo/comentariosModelo.php';
        $comentarioModelo = new Comentario();
        $allCom = $comentarioModelo->obtenerComentariosPorPublicacion($idPublicacion);

        // Separar comentarios raíz y respuestas
        $comRaiz = [];
        $comHijos = [];
        foreach ($allCom as $c) {
            $idPadre = $c['id_padre'] ?? null;
            if (is_null($idPadre)) {
                $comRaiz[$c['id_comentario']] = $c;
            } else {
                $comHijos[$idPadre][] = $c;
            }
        }

        // Función recursiva para renderizar comentarios y respuestas
        if (!function_exists('renderComentarios')) {
            function renderComentarios($comentarios, $hijos)
            {
                foreach ($comentarios as $c) {
                    $id_comentario = (int)($c['id_comentario'] ?? 0);
                    $nombre = htmlspecialchars($c['nombre'] ?? 'Anónimo');
                    $contenido = nl2br(htmlspecialchars($c['comentario'] ?? ''));
                    $fecha = !empty($c['fecha']) ? date('d M Y H:i', strtotime($c['fecha'])) : 'Sin fecha';

                    echo "<div class='comentario-raiz bg-light rounded' id='comentario-$id_comentario'>
                            <strong>{$nombre}:</strong> {$contenido}<br>
                            <small class='text-muted'>{$fecha}</small>
                            <button class='btn btn-sm btn-link btn-responder likes-count' data-id='{$id_comentario}'>Responder</button>";

                    if (isset($hijos[$id_comentario])) {
                        $totalHijos = count($hijos[$id_comentario]);
                        echo "<button class='btn btn-sm btn-outline-secondary ver-respuestas' data-id='{$id_comentario}'>
                                Ver respuestas ({$totalHijos})
                              </button>";
                        echo "<div class='p-2 d-none' id='respuestas-{$id_comentario}'>";
                        renderComentarios($hijos[$id_comentario], $hijos);
                        echo "</div>";
                    }
                    echo "</div>";
                }
            }
        }

        $comentariosTotales = $comentarioModelo->contarComentariosPorPublicacion($idPublicacion);

        // Mostrar la publicación
        echo '<article class="instagram-post">
            <header class="post-header">
                <div class="profile-info">
                    <img src="' . (!empty($publicacion['foto']) ? 'data:image/*;base64,' . base64_encode($publicacion['foto']) : 'https://cdn1.iconfinder.com/data/icons/avatar-3/512/Secretary-512.png') . '" alt="Foto" class="profile-pic" />
                    <div class="profile-details">
                        <span class="username">' . htmlspecialchars(ucwords(strtolower($publicacion['nickname']))) . '</span>
                        <p class="post-title text-end small mb-2">' . htmlspecialchars($publicacion['titulo']) . '</p>
                    </div>
                </div>
                <div class="dropdown">
                    <button class="btn btn-link p-0 shadow-none btn-like" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots-vertical text-black fs-5"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-start">
                        <li>
                            <a class="dropdown-item" href="#" type="button" data-bs-toggle="modal" data-bs-target="#modalReportar" 
                               onclick="rellenarDatosReporte(\'' . htmlspecialchars(ucwords(strtolower($publicacion['nickname']))) . '\', \'' . $idPublicacion . '\')">
                               Reportar
                            </a>
                        </li>
                    </ul>
                </div>
            </header>

            <div class="post-content">
                <p class="ps-3 pt-2">' . nl2br(htmlspecialchars($publicacion['contenido'])) . '</p>
            </div>

            <div class="post-actions">
                <div class="d-flex gap-2">
                    <button class="btn btn-sm ' . $btnClass . ' btn-like" data-id="' . $idPublicacion . '">
                        <i class="bi ' . $iconClass . ' heart-icon"></i> Me gusta
                        <span class="badge bg-danger likes-count">' . $likes . '</span>
                    </button>
                    <button class="btn btn-sm btn-outline-primary btn-toggle-comments" data-id="' . $idPublicacion . '">
                        <i class="bi bi-chat"></i> Comentarios
                        <span class="badge bg-primary comentarios-count" id="comentarios-count-' . $idPublicacion . '">' . $comentariosTotales . '</span>
                    </button>
                </div>
            </div>

            <div class="comments-section mt-3 d-none" id="comments-' . $idPublicacion . '">
                <div class="existing-comments mb-3">';
        if ($allCom) renderComentarios($comRaiz, $comHijos);
        else echo "<p class='text-muted'>Aún no hay comentarios.</p>";
        echo '</div>

                <form class="comment-form" data-id-publicacion="' . $idPublicacion . '">
                    <div class="input-group">
                        <input type="text" name="comentario" class="form-control form-control-sm" placeholder="Escribe un comentario..." required />
                        <input type="hidden" name="opcion" value="1">
                        <input type="hidden" name="id_publicacion" value="' . $idPublicacion . '">
                        <input type="hidden" name="id_padre" value="">
                        <button class="btn btn-sm btn-primary" type="submit">Enviar</button>
                    </div>
                </form>
            </div>
        </article>';
    }

    // Cerrar conexión (opcional)
    public function cerrarBD()
    {
        if ($this->con) {
            mysqli_close($this->con);
            $this->con = null;
        }
    }

    // Método para verificar si una columna existe en una tabla
    private function columnaExiste($tabla, $columna)
    {
        $this->conectarBD();
        $tabla = mysqli_real_escape_string($this->con, $tabla);
        $columna = mysqli_real_escape_string($this->con, $columna);
        $sql = "SHOW COLUMNS FROM `$tabla` LIKE '$columna'";
        $resultado = mysqli_query($this->con, $sql);
        return ($resultado && mysqli_num_rows($resultado) > 0);
    }
}
