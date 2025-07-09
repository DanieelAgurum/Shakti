<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class PublicacionModelo
{
    private $conn;

    public function conectar()
    {
        if (!$this->conn) {
            try {
                $this->conn = new PDO("mysql:host=localhost;dbname=shakti", "root", "", [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
            } catch (PDOException $e) {
                die("❌ Error de conexión: " . $e->getMessage());
            }
        }
    }

    public function guardar(string $titulo, string $contenido, int $id_usuaria): bool
    {
        $this->conectar();
        try {
            $sql = "INSERT INTO publicacion (titulo, contenido, fecha_publicacion, id_usuarias)
                    VALUES (:titulo, :contenido, NOW(), :id_usuaria)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':titulo', $titulo);
            $stmt->bindParam(':contenido', $contenido);
            $stmt->bindParam(':id_usuaria', $id_usuaria);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al guardar publicación: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerTodasConNickname(): array
    {
        $this->conectar();
        try {
            $sql = "SELECT p.*, u.nickname 
                    FROM publicacion p
                    JOIN usuarias u ON p.id_usuarias = u.id
                    ORDER BY p.fecha_publicacion DESC";
            $stmt = $this->conn->query($sql);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error al obtener publicaciones con nickname: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerPublicacionesAdmin(): void
    {
        $this->conectar();
        try {
            $sql = "SELECT p.*, u.nickname, u.foto 
                    FROM publicacion p 
                    INNER JOIN usuarias u ON p.id_usuarias = u.id 
                    WHERE u.id_rol IN (2,3) 
                    ORDER BY p.fecha_publicacion DESC";
            $stmt = $this->conn->query($sql);
            $publicaciones = $stmt->fetchAll();

            if (empty($publicaciones)) {
                echo '<p class="text-center mt-4">No hay publicaciones por ahora.</p>';
                return;
            } else {

                foreach ($publicaciones as $pub) {
                    $foto = !empty($pub['foto'])
                        ? "data:image/*;base64," . base64_encode($pub['foto'])
                        : 'https://cdn1.iconfinder.com/data/icons/avatar-3/512/Secretary-512.png';

                    $nickname = htmlspecialchars($pub['nickname'] ?? '');
                    $contenido = nl2br(htmlspecialchars($pub['contenido']));
                    $idPub = (int)$pub['id_publicacion'];
                    echo <<<HTML
                    <article class="instagram-post">
                        <header class="post-header">
                            <div class="profile-info">
                                <img src="$foto" alt="Foto" class="profile-pic">
                                <div class="profile-details">
                                    <span class="username">$nickname</span>
                                </div>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-link p-0 shadow-none" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots-vertical text-black fs-5"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-start">
                                    <!-- <li><a class="dropdown-item" href="#">Eliminar</a></li> -->
                                    <li>
                                        <a class="dropdown-item" href="#" type="button" data-bs-toggle="modal" data-bs-target="#modalReportar" onclick="rellenarDatosReporte('$nickname', $idPub)"> Reportar</a>
                                    </li>
                                </ul>
                            </div>
                        </header>
                        <div class="post-content">
                            <p class="ps-3 pt-2">$contenido</p>
                        </div>
                        <div class="post-actions">
                        </div>
                        </article>
                    HTML;
                }
            }
        } catch (PDOException $e) {
            error_log("Error al obtener publicaciones admin: " . $e->getMessage());
            echo '<p class="text-center text-danger">Error al cargar publicaciones.</p>';
        }
    }

    public function obtenerTodas(): array
    {
        $this->conectar();
        try {
            $sql = "SELECT * FROM publicacion ORDER BY fecha_publicacion DESC";
            $stmt = $this->conn->query($sql);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error al obtener todas las publicaciones: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerPorUsuaria(int $id_usuaria): array
    {
        $this->conectar();
        try {
            $sql = "SELECT * FROM publicacion WHERE id_usuarias = :id_usuaria ORDER BY fecha_publicacion DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_usuaria', $id_usuaria);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error al obtener publicaciones por usuaria: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerPorId(int $id_publicacion): ?array
    {
        $this->conectar();
        try {
            $sql = "SELECT * FROM publicacion WHERE id_publicacion = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id_publicacion);
            $stmt->execute();
            $resultado = $stmt->fetch();
            return $resultado ?: null;
        } catch (PDOException $e) {
            error_log("Error al obtener publicación por ID: " . $e->getMessage());
            return null;
        }
    }

    public function actualizar(int $id, string $titulo, string $contenido): bool
    {
        $this->conectar();
        try {
            $sql = "UPDATE publicacion SET titulo = :titulo, contenido = :contenido WHERE id_publicacion = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':titulo', $titulo);
            $stmt->bindParam(':contenido', $contenido);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al actualizar publicación: " . $e->getMessage());
            return false;
        }
    }

    public function borrar(int $id, int $id_usuaria): bool
    {
        $this->conectar();
        try {
            $sql = "DELETE FROM publicacion WHERE id_publicacion = :id AND id_usuarias = :id_usuaria";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':id_usuaria', $id_usuaria);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al borrar publicación con verificación: " . $e->getMessage());
            return false;
        }
    }

    public function borrarSinVerificar(int $id_publicacion): bool
    {
        $this->conectar();
        try {
            $sql = "DELETE FROM publicacion WHERE id_publicacion = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id_publicacion);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al borrar publicación sin verificación: " . $e->getMessage());
            return false;
        }
    }

    public function cerrarConexion()
    {
        $this->conn = null;
    }
}
