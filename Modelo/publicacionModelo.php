<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Modelo/notificacionesModelo.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Controlador/api_key.php';

class PublicacionModelo
{
    private $conn;
    private $buscar;

    public function conectar()
    {
        if (!($this->conn instanceof PDO)) {
            try {
                $this->conn = new PDO("mysql:host=localhost;dbname=shakti", "root", "", [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
            } catch (PDOException $e) {
                die("Error de conexión: " . $e->getMessage());
            }
        }
    }

    private function moderarContenidoIA(string $contenido): bool
    {
        $apiKey = OPENAI_API_KEY;

        $prompt = "Eres un moderador de contenido en una red social. Analiza el siguiente texto y responde solo 'true' si es apropiado
        o 'false' si contiene lenguaje ofensivo, violencia, discriminación o spam:
        \"$contenido\"";

        $data = [
            "model" => "gpt-5-nano",
            "messages" => [
                ["role" => "system", "content" => "Eres un moderador de contenido."],
                ["role" => "user", "content" => $prompt]
            ],
            "temperature" => 0
        ];

        $ch = curl_init("https://api.openai.com/v1/chat/completions");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer $apiKey"
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $result = curl_exec($ch);
        curl_close($ch);

        if (!$result) {
            error_log("Error en la petición a OpenAI");
            return false;
        }

        $response = json_decode($result, true);
        $respuesta = strtolower(trim($response['choices'][0]['message']['content'] ?? 'false'));

        return $respuesta === 'true';
    }


    public function guardar(string $titulo, string $contenido, int $anonima, int $id_usuaria): bool
    {
        $this->conectar();

        // Moderación IA
        if (!$this->moderarContenidoIA($contenido)) {
            $_SESSION['sweet_alert'] = [
                'icon' => 'warning',
                'title' => 'Lenguaje inapropiado',
                'text' => 'Evitemos palabras ofensivas. Gracias.'
            ];
            return false;
        }

        try {
            $sql = "INSERT INTO publicacion (titulo, contenido, fecha_publicacion, anonima, id_usuarias)
                VALUES (:titulo, :contenido, NOW(), :anonima, :id_usuaria)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':titulo', $titulo);
            $stmt->bindParam(':contenido', $contenido);
            $stmt->bindParam(':anonima', $anonima);
            $stmt->bindParam(':id_usuaria', $id_usuaria);

            $exito = $stmt->execute();

            if ($exito) {
                $_SESSION['sweet_alert'] = [
                    'icon' => 'success',
                    'title' => 'Publicación guardada',
                    'text' => 'Tu publicación se ha guardado correctamente.'
                ];
            }

            return $exito;
        } catch (PDOException $e) {
            error_log("Error al guardar publicación: " . $e->getMessage());
            $_SESSION['sweet_alert'] = [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al guardar la publicación.'
            ];
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

    public function inicializar($buscar)
    {
        $this->buscar = $buscar;
    }

    public function buscar()
    {
        $this->conectar();

        if (!empty($this->buscar)) {
            // Escapamos la cadena de búsqueda con PDO->quote() que añade comillas y escapa caracteres
            $busquedaSegura = $this->conn->quote('%' . $this->buscar . '%');

            $sql = "SELECT p.contenido, p.id_publicacion, p.titulo, u.id, u.nickname, u.foto, u.id_rol 
                FROM publicacion p 
                JOIN usuarias u ON p.id_usuarias = u.id 
                WHERE u.id_rol IN (2,3) AND (
                    p.contenido LIKE $busquedaSegura OR 
                    p.titulo LIKE $busquedaSegura OR 
                    u.nickname LIKE $busquedaSegura
                )";

            try {
                $consulta = $this->conn->query($sql);

                if ($consulta && $consulta->rowCount() > 0) {
                    while ($publicacion = $consulta->fetch()) {
                        $this->imprimirPublicacion($publicacion);
                    }
                } else {
                    echo "<p>No se encontraron resultados para '" . htmlspecialchars($this->buscar) . "'</p>";
                }
            } catch (PDOException $e) {
                echo "<p>Error en la búsqueda: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
        } else {
            $this->todos();
        }
    }

    public function todos()
    {
        $this->conectar();

        $sql = "SELECT p.*, u.nickname, u.foto 
            FROM publicacion p 
            INNER JOIN usuarias u ON p.id_usuarias = u.id 
            WHERE u.id_rol IN (2,3) 
            ORDER BY p.fecha_publicacion DESC";

        $stmt = $this->conn->query($sql);
        $publicaciones = $stmt->fetchAll();

        if (!empty($publicaciones)) {
            foreach ($publicaciones as $publicacion) {
                $this->imprimirPublicacion($publicacion);
            }
        } else {
            echo "<p>No hay publicaciones.</p>";
        }
    }

    private function imprimirPublicacion($publicacion)
    {

        echo '<article class="instagram-post">
        <header class="post-header">
        <div class="profile-info">
            <img src="' . (!empty($publicacion['foto']) ? 'data:image/*;base64,' . base64_encode($publicacion['foto']) : 'https://cdn1.iconfinder.com/data/icons/avatar-3/512/Secretary-512.png') . '" alt="Foto" class="profile-pic" />
            <div class="profile-details">
                <span class="username">' . htmlspecialchars(ucwords(strtolower($publicacion['nickname']))) . '</span>
            </div>
        </div>
        <div class="dropdown">
            <button class="btn btn-link p-0 shadow-none btn-like" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-three-dots-vertical text-black fs-5"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-start">
                <li>
                    <a class="dropdown-item" href="#" type="button" data-bs-toggle="modal" data-bs-target="#modalReportar" 
                       onclick="rellenarDatosReporte(\'' . htmlspecialchars(ucwords(strtolower($publicacion['nickname']))) . '\', \'' . $publicacion['id_publicacion'] . '\')">
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
    </div>
</article>';
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

        // Validación con IA antes de actualizar
        if (!$this->moderarContenidoIA($contenido)) {
            $_SESSION['sweet_alert'] = [
                'icon' => 'warning',
                'title' => 'Lenguaje inapropiado',
                'text' => 'Evitemos palabras ofensivas. Gracias.'
            ];
            return false;
        }

        // Intentar actualizar en la base de datos
        try {
            $sql = "UPDATE publicacion SET titulo = :titulo, contenido = :contenido WHERE id_publicacion = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':titulo', $titulo);
            $stmt->bindParam(':contenido', $contenido);
            $stmt->bindParam(':id', $id);

            $exito = $stmt->execute();

            if ($exito) {
                $_SESSION['sweet_alert'] = [
                    'icon' => 'success',
                    'title' => 'Publicación actualizada',
                    'text' => 'La publicación se ha actualizado correctamente.'
                ];
            }

            return $exito;
        } catch (PDOException $e) {
            error_log("Error al actualizar publicación: " . $e->getMessage());
            $_SESSION['sweet_alert'] = [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al actualizar la publicación.'
            ];
            return false;
        }
    }

    public function borrar(int $id, int $id_usuaria): bool
    {
        $this->conectar();

        try {
            $sqlLikes = "DELETE FROM likes_publicaciones WHERE id_publicacion = ?";
            $stmtLikes = $this->conn->prepare($sqlLikes);
            $stmtLikes->bindParam("i", $id);
            $stmtLikes->execute();

            $sqlComentarios = "DELETE FROM comentarios WHERE id_publicacion = ?";
            $stmtComentarios = $this->conn->prepare($sqlComentarios);
            $stmtComentarios->bindParam("i", $id);
            $stmtComentarios->execute();

            $sqlPublicacion = "DELETE FROM publicacion WHERE id_publicacion = ? AND id_usuarias = ?";
            $stmtPublicacion = $this->conn->prepare($sqlPublicacion);
            $stmtPublicacion->bindParam("ii", $id, $id_usuaria);

            return $stmtPublicacion->execute();
        } catch (PDOException $e) {
            error_log("Error al borrar publicación con verificación: " . $e->getMessage());
            return false;
        }
    }

    public function borrarSinVerificar(int $id_publicacion): bool
    {
        $this->conectar();

        try {
            $sqlLikes = "DELETE FROM likes_publicaciones WHERE id_publicacion = ?";
            $stmtLikes = $this->conn->prepare($sqlLikes);
            $stmtLikes->bindParam("i", $id_publicacion);
            $stmtLikes->execute();

            $sqlComentarios = "DELETE FROM comentarios WHERE id_publicacion = ?";
            $stmtComentarios = $this->conn->prepare($sqlComentarios);
            $stmtComentarios->bindParam("i", $id_publicacion);
            $stmtComentarios->execute();

            $sqlPublicacion = "DELETE FROM publicacion WHERE id_publicacion = ?";
            $stmtPublicacion = $this->conn->prepare($sqlPublicacion);
            $stmtPublicacion->bindParam("i", $id_publicacion);

            return $stmtPublicacion->execute();
        } catch (PDOException $e) {
            error_log("Error al borrar publicación con verificación: " . $e->getMessage());
            return false;
        }
    }

    public function cerrarConexion()
    {
        $this->conn = null;
    }
}
