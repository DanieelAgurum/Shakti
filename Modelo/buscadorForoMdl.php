<?php
class buscadorForoMdl
{
    private $con;
    private $buscar;

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

    public function inicializar($buscar)
    {
        $this->buscar = $buscar;
    }

    public function buscar()
    {
        $this->conectarBD();

        if (!empty($this->buscar)) {
            $busquedaSegura = mysqli_real_escape_string($this->con, $this->buscar);

            $sql = "SELECT p.contenido AS contenido, p.id_publicacion, p.titulo, u.id, u.nickname, u.foto as foto, u.id_rol as id_rol FROM publicacion p JOIN usuarias u ON p.id_usuarias = u.id WHERE id_rol = 1 AND p.contenido LIKE '%$busquedaSegura%' OR p.titulo = '%$busquedaSegura%'";
            $consulta = mysqli_query($this->con, $sql);

            if ($consulta && mysqli_num_rows($consulta) > 0) {
                while ($publicacion = mysqli_fetch_assoc($consulta)) {
                    echo '<article class="instagram-post">
                        <header class="post-header">
                            <div class="profile-info">
                            <img src="' . (!empty($publicacion['foto']) ? 'data:image/*;base64,' . base64_encode($publicacion['foto']) : 'https://cdn1.iconfinder.com/data/icons/avatar-3/512/Secretary-512.png') . '" alt="Foto" class="profile-pic" />
                                <div class="profile-details">
                                    <span class="username">' . htmlspecialchars(ucwords(strtolower($publicacion['nickname']))) . '</span>
                                </div>
                            </div>

                            <div class="dropdown">
                                <button class="btn btn-link p-0 shadow-none" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots-vertical text-black fs-5"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-start">
                                    <li><a class="dropdown-item" href="#">Editar</a></li>
                                    <li><a class="dropdown-item" href="#">Eliminar</a></li>
                                    <li><a class="dropdown-item" href="#">Compartir</a></li>
                                </ul>
                            </div>
                        </header>

                        <div class="post-content">
                            <p class="ps-3 pt-2">' . nl2br(htmlspecialchars($publicacion['contenido'])) . '</p>
                        </div>

                        <div class="post-actions">
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-danger btn-like" data-id="' . $publicacion['id_publicacion'] . '">
                                    <i class="bi bi-suit-heart-fill"></i> Me gusta
                                    <span class="badge bg-danger likes-count">0</span>
                                </button>
                                <button class="btn btn-sm btn-outline-secondary btn-toggle-comments" data-id="' . $publicacion['id_publicacion'] . '">
                                    <i class="bi bi-chat"></i> Comentarios
                                </button>
                            </div>
                        </div>

                        <div class="comments-section mt-3 d-none" id="comments-' . $publicacion['id_publicacion'] . '">
                            <div class="existing-comments mb-3">
                                <p class="text-muted">Aún no hay comentarios.</p>
                            </div>
                            <form class="comment-form" data-id="' . $publicacion['id_publicacion'] . '">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm" placeholder="Escribe un comentario..." required />
                                    <button class="btn btn-sm btn-primary" type="submit">Enviar</button>
                                </div>
                            </form>
                        </div>
                    </article>';
                }
            } else {
                echo "<p>No se encontraron resultados para '" . htmlspecialchars($this->buscar) . "'</p>";
            }
        } else {
            $this->todos();
        }
    }

    public function todos()
    {
        $this->conectarBD();
        $sql = "SELECT p.contenido AS contenido, p.id_publicacion, u.id, u.nickname, u.foto as foto, u.id_rol as id_rol FROM publicacion p JOIN usuarias u ON p.id_usuarias = u.id WHERE id_rol = 1";
        $consulta = mysqli_query($this->con, $sql);

        if ($consulta && mysqli_num_rows($consulta) > 0) {
            while ($publicacion = mysqli_fetch_assoc($consulta)) {
                echo '<article class="instagram-post">
                        <header class="post-header">
                            <div class="profile-info">
                            <img src="' . (!empty($publicacion['foto']) ? 'data:image/*;base64,' . base64_encode($publicacion['foto']) : 'https://cdn1.iconfinder.com/data/icons/avatar-3/512/Secretary-512.png') . '" alt="Foto" class="profile-pic" />
                                <div class="profile-details">
                                    <span class="username">' . htmlspecialchars(ucwords(strtolower($publicacion['nickname']))) . '</span>
                                </div>
                            </div>

                            <div class="dropdown">
                                <button class="btn btn-link p-0 shadow-none" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots-vertical text-black fs-5"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-start">
                                    <li><a class="dropdown-item" href="#">Editar</a></li>
                                    <li><a class="dropdown-item" href="#">Eliminar</a></li>
                                    <li><a class="dropdown-item" href="#">Compartir</a></li>
                                </ul>
                            </div>
                        </header>

                        <div class="post-content">
                            <p class="ps-3 pt-2">' . nl2br(htmlspecialchars($publicacion['contenido'])) . '</p>
                        </div>

                        <div class="post-actions">
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-danger btn-like" data-id="' . $publicacion['id_publicacion'] . '">
                                    <i class="bi bi-suit-heart-fill"></i> Me gusta
                                    <span class="badge bg-danger likes-count">0</span>
                                </button>
                                <button class="btn btn-sm btn-outline-secondary btn-toggle-comments" data-id="' . $publicacion['id_publicacion'] . '">
                                    <i class="bi bi-chat"></i> Comentarios
                                </button>
                            </div>
                        </div>

                        <div class="comments-section mt-3 d-none" id="comments-' . $publicacion['id_publicacion'] . '">
                            <div class="existing-comments mb-3">
                                <p class="text-muted">Aún no hay comentarios.</p>
                            </div>
                            <form class="comment-form" data-id="' . $publicacion['id_publicacion'] . '">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm" placeholder="Escribe un comentario..." required />
                                    <button class="btn btn-sm btn-primary" type="submit">Enviar</button>
                                </div>
                            </form>
                        </div>
                    </article>';
            }
        } else {
            echo "<p>No hay publicaciones.</p>";
        }
    }

    public function cerrarBD()
    {
        if ($this->con) {
            mysqli_close($this->con);
        }
    }
}
