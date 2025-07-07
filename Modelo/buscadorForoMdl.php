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
        $idUsuaria = $_SESSION['id_usuaria'] ?? null;

        if (!empty($this->buscar)) {
            $busquedaSegura = mysqli_real_escape_string($this->con, $this->buscar);
            $sql = "SELECT p.contenido, p.id_publicacion, p.titulo, u.id, u.nickname, u.foto, u.id_rol 
                    FROM publicacion p 
                    JOIN usuarias u ON p.id_usuarias = u.id 
                    WHERE id_rol = 1 AND (p.contenido LIKE '%$busquedaSegura%' OR p.titulo LIKE '%$busquedaSegura%')";
            $consulta = mysqli_query($this->con, $sql);

            if ($consulta && mysqli_num_rows($consulta) > 0) {
                while ($publicacion = mysqli_fetch_assoc($consulta)) {
                    $this->imprimirPublicacion($publicacion, $idUsuaria);
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
        $idUsuaria = $_SESSION['id_usuaria'] ?? null;

        $sql = "SELECT p.contenido, p.id_publicacion, u.id, u.nickname, u.foto, u.id_rol 
                FROM publicacion p 
                JOIN usuarias u ON p.id_usuarias = u.id 
                WHERE id_rol = 1";
        $consulta = mysqli_query($this->con, $sql);

        if ($consulta && mysqli_num_rows($consulta) > 0) {
            while ($publicacion = mysqli_fetch_assoc($consulta)) {
                $this->imprimirPublicacion($publicacion, $idUsuaria);
            }
        } else {
            echo "<p>No hay publicaciones.</p>";
        }
    }

    private function imprimirPublicacion($publicacion, $idUsuaria)
    {
        $idPublicacion = $publicacion['id_publicacion'];

        $likesConsulta = mysqli_query($this->con, "SELECT COUNT(*) AS total FROM likes_publicaciones WHERE id_publicacion = $idPublicacion");
        $likes = ($likesConsulta && $row = mysqli_fetch_assoc($likesConsulta)) ? $row['total'] : 0;

        $yaDioLike = false;
        if ($idUsuaria) {
            $verificarLike = mysqli_query($this->con, "SELECT 1 FROM likes_publicaciones WHERE id_usuaria = $idUsuaria AND id_publicacion = $idPublicacion");
            $yaDioLike = mysqli_num_rows($verificarLike) > 0;
        }

        $btnClass = $yaDioLike ? 'btn-danger' : 'btn-outline-danger';

        echo '<article class="instagram-post">
                <header class="post-header">
                    <div class="profile-info">
                        <img src="' . (!empty($publicacion['foto']) ? 'data:image/*;base64,' . base64_encode($publicacion['foto']) : 'https://cdn1.iconfinder.com/data/icons/avatar-3/512/Secretary-512.png') . '" alt="Foto" class="profile-pic" />
                        <div class="profile-details">
                            <span class="username">' . htmlspecialchars(ucwords(strtolower($publicacion['nickname']))) . '</span>
                        </div>
                    </div>
                </header>

                <div class="post-content">
                    <p class="ps-3 pt-2">' . nl2br(htmlspecialchars($publicacion['contenido'])) . '</p>
                </div>

                <div class="post-actions">
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm ' . $btnClass . ' btn-like" data-id="' . $idPublicacion . '">
                            <i class="bi bi-suit-heart-fill"></i> Me gusta
                            <span class="badge bg-danger likes-count">' . $likes . '</span>
                        </button>
                        <button class="btn btn-sm btn-outline-secondary btn-toggle-comments" data-id="' . $idPublicacion . '">
                            <i class="bi bi-chat"></i> Comentarios
                        </button>
                    </div>
                </div>
            </article>';
    }

    public function cerrarBD()
    {
        if ($this->con) {
            mysqli_close($this->con);
        }
    }
}
