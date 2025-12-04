<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class buscadorForoMdl
{
    private $con;
    private $buscar;

    // Conectar a la base de datos si no hay conexión previa
    public function conectarBD()
    {
        if (!$this->con) {
            $this->con = mysqli_connect(
                "localhost",
                "root",
                "",
                "shakti"
            );

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
        $publicacionDestacada = null;

        $hashSeleccionado = isset($_GET['publicacion']) ? preg_replace('/[^a-f0-9]/i', '', $_GET['publicacion']) : null;
        $idDestacada = null;

        if ($hashSeleccionado && $offset === 0) {
            $stmtBuscar = $this->con->prepare("SELECT id_publicacion FROM publicacion");
            $stmtBuscar->execute();
            $resBuscar = $stmtBuscar->get_result();
            while ($row = $resBuscar->fetch_assoc()) {
                if (hash('sha256', $row['id_publicacion']) === $hashSeleccionado) {
                    $idDestacada = (int)$row['id_publicacion'];
                    break;
                }
            }
            $stmtBuscar->close();

            if ($idDestacada !== null) {
                $sqlDestacada = "SELECT p.titulo, p.contenido, p.anonima, p.id_publicacion, u.id, u.nickname, u.foto
                             FROM publicacion p 
                             JOIN usuarias u ON p.id_usuarias = u.id 
                             WHERE p.id_publicacion = ?";
                $stmtDest = $this->con->prepare($sqlDestacada);
                $stmtDest->bind_param("i", $idDestacada);
                $stmtDest->execute();
                $resultDest = $stmtDest->get_result();

                if ($resultDest && $resultDest->num_rows > 0) {
                    $publicacionDestacada = $resultDest->fetch_assoc();
                    $this->imprimirPublicacion($publicacionDestacada, $idUsuaria, true);
                }
                $stmtDest->close();
            }
        }

        $nuevoLimit = $limit;
        if ($idDestacada !== null && $offset === 0) {
            $nuevoLimit = $limit - 1;
        }

        if ($idDestacada !== null) {
            $sql = "SELECT p.titulo, p.contenido, p.anonima, p.id_publicacion, u.id, u.nickname, u.foto
                FROM publicacion p 
                JOIN usuarias u ON p.id_usuarias = u.id 
                WHERE p.id_publicacion != ?
                ORDER BY p.fecha_publicacion DESC
                LIMIT ? OFFSET ?";
            $stmt = $this->con->prepare($sql);
            $stmt->bind_param("iii", $idDestacada, $nuevoLimit, $offset);
        } else {
            $sql = "SELECT p.titulo, p.contenido, p.anonima, p.id_publicacion, u.id, u.nickname, u.foto
                FROM publicacion p 
                JOIN usuarias u ON p.id_usuarias = u.id 
                ORDER BY p.fecha_publicacion DESC
                LIMIT ? OFFSET ?";
            $stmt = $this->con->prepare($sql);
            $stmt->bind_param("ii", $nuevoLimit, $offset);
        }

        $stmt->execute();
        $consulta = $stmt->get_result();

        if ($consulta && $consulta->num_rows > 0) {
            while ($publicacion = $consulta->fetch_assoc()) {
                $this->imprimirPublicacion($publicacion, $idUsuaria);
            }
        }
        $stmt->close();
    }

    private function imprimirPublicacion($publicacion, $idUsuaria, $destacada = false)
    {
        $idPublicacion = (int)$publicacion['id_publicacion'];
        $esAnonima = isset($publicacion['anonima']) && $publicacion['anonima'] == '1';
        $nombreMostrar = $esAnonima ? 'Anónimo' : htmlspecialchars(ucwords(strtolower($publicacion['nickname'])));
        $fotoMostrar = $esAnonima
            ? '../../img/undraw_chill-guy-avatar_tqsm.svg'
            : (!empty($publicacion['foto']) ? 'data:image/*;base64,' . base64_encode($publicacion['foto']) : '../../img/undraw_chill-guy-avatar_tqsm.svg');

        $likesConsulta = mysqli_query($this->con, "SELECT COUNT(*) AS total FROM likes_publicaciones WHERE id_publicacion = $idPublicacion");
        $likes = ($likesConsulta && $row = mysqli_fetch_assoc($likesConsulta)) ? $row['total'] : 0;

        $yaDioLike = false;
        if ($idUsuaria) {
            $verificarLike = mysqli_query($this->con, "SELECT 1 FROM likes_publicaciones WHERE id_usuaria = $idUsuaria AND id_publicacion = $idPublicacion");
            $yaDioLike = ($verificarLike && mysqli_num_rows($verificarLike) > 0);
        }

        $btnClass = $yaDioLike ? 'btn-danger' : 'btn-outline-danger';
        $iconClass = $yaDioLike ? 'bi-suit-heart-fill' : 'bi-suit-heart';

        require_once __DIR__ . '/../Modelo/comentariosModelo.php';
        $comentarioModelo = new Comentario();
        $allCom = $comentarioModelo->obtenerComentariosPorPublicacion($idPublicacion);
        $comRaiz = [];
        $comHijos = [];
        foreach ($allCom as $c) {
            $idPadre = $c['id_padre'] ?? null;
            if (is_null($idPadre)) $comRaiz[$c['id_comentario']] = $c;
            else $comHijos[$idPadre][] = $c;
        }

        $comentariosTotales = $comentarioModelo->contarComentariosPorPublicacion($idPublicacion);
        $clasesContenedores = $destacada ? "post-destacado" : "instagram-post";

        echo '<article class="card ' . $clasesContenedores . ' animate__animated animate__fadeInLeft">
    <header class="post-header">
        <div class="profile-info">
            <img src="' . $fotoMostrar . '" alt="Foto" class="profile-pic" />
            <div class="profile-details">
                <span class="username">' . $nombreMostrar . '</span>
                <p class="post-title text-end small mb-2">' . htmlspecialchars($publicacion['titulo']) . '</p>
            </div>
        </div>
            <div class="dropdown">
                <button class="btn btn-link p-0 shadow-none btn-like" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Opciones" data-bs-toggle="tooltip">
                    <i class="bi bi-three-dots-vertical text-black fs-5"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-start">
                    <li>
                        <a class="dropdown-item" href="#" type="button" data-bs-toggle="modal" data-bs-target="#modalReportar"
                           onclick="rellenarDatosReporte(\'' . htmlspecialchars(ucwords(strtolower($publicacion['nickname']))) . '\', \'' . $idPublicacion . '\')">
                           <i class="bi bi-exclamation-triangle"></i> Reportar
                        </a>
                    </li>
                    <li>
                   <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalCompartir"
   onclick="setIdCompartir(' . $idPublicacion . ')">
    <i class="bi bi-share-fill"></i> Compartir
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
        if ($comRaiz) {
            foreach ($comRaiz as $comentario) {
                $id_comentario = $comentario['id_comentario'];
                $nombre = ($comentario['anonimo'] == 1)
                    ? 'Anónimo'
                    : htmlspecialchars($comentario['nombre'] ?? 'Anónimo');
                $contenido = nl2br(htmlspecialchars($comentario['comentario']));
                $fecha = !empty($comentario['fecha_comentario']) ? date('d M Y H:i', strtotime($comentario['fecha_comentario'])) : 'Sin fecha';
                $tiempoComentario = strtotime($comentario['fecha_comentario']);
                $esAutoraComentario = isset($comentario['id_usuaria']) && $comentario['id_usuaria'] == $idUsuaria;
                $puedeEditar = $esAutoraComentario && (time() - $tiempoComentario) <= 300;
                $respuestasCount = $comentarioModelo->contarRespuestasPorPadre($id_comentario);

                echo "<div class='comentario-raiz mb-2 p-2 bg-light rounded position-relative border' id='comentario-$id_comentario'>
                <strong>$nombre:</strong> $contenido<br>
                <small class='text-muted'>$fecha</small>
                <button class='btn btn-outline-primary btn-sm btn-responder' data-id='$id_comentario'>Responder</button>
                <div class='d-none' id='respuestas-$id_comentario'></div>";

                if ($puedeEditar) {
                    echo "<div class='dropdown position-absolute top-0 end-0 mt-2 me-2'>
              <button class='btn btn-sm btn-link p-0 text-dark' type='button' data-bs-toggle='dropdown' title='Opciones' data-bs-toggle='tooltip'>
                <i class='bi bi-three-dots-vertical fs-5'></i>
              </button>
              <ul class='dropdown-menu dropdown-menu-end'>
                <li>
                  <button class='dropdown-item btn btn-outline-success btn-sm btn-edit-comentario' data-id='$id_comentario'>
                    <i class='bi bi-pencil-square text-success'></i> Editar
                  </button>
                </li>
                <li>
                  <button class='dropdown-item btn btn-outline-danger btn-sm btn-eliminar-comentario' data-id='$id_comentario'>
                    <i class='bi bi-trash3 text-danger'></i> Eliminar
                  </button>
                </li>
              </ul>
            </div>

                      <form class='edit-comentario-form d-none mt-2' id='edit-form-$id_comentario'>
                          <input type='hidden' name='id_comentario' value='$id_comentario'>
                          <div class='input-group'>
                              <input type='text' class='form-control form-control-sm' name='nuevo_comentario' value='" . htmlspecialchars($contenido) . "' required>
                              <button type='submit' class='btn btn-sm btn-outline-success'>
                                <i class='bi bi-check2-circle'></i> Guardar
                              </button>
                          </div>
                      </form>";
                }
                if ($respuestasCount > 0) {
                    echo " <button class='btn btn-sm btn-outline-secondary ver-respuestas' data-id='$id_comentario' data-count='$respuestasCount'>
                        Ver respuestas ($respuestasCount)
                      </button>";
                }
                echo "</div>
                  <div class=' mt-2' id='form-responder-$id_comentario'></div>";
            }
        } else {
            echo "<p class='text-muted'>Aún no hay comentarios.</p>";
        }
        echo '</div>

        <form class="comment-form" data-id-publicacion="' . $idPublicacion . '">
            <input type="hidden" name="opcion" value="1">
            <input type="hidden" name="id_publicacion" value="' . $idPublicacion . '">
            <input type="hidden" name="id_padre" value="">
            <div class="input-group mb-2">
                <input type="text" name="comentario" class="form-control form-control-sm" placeholder="Escribe un comentario..." required>
                <button type="submit" class="btn btn-sm btn-outline-primary">Enviar <i class="bi bi-arrow-right-circle"></i></button>
            </div>
        </form>
    </div>
</article>';
    }
    public function cerrarBD()
    {
        if ($this->con) {
            mysqli_close($this->con);
            $this->con = null;
        }
    }
}
