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

    public function moderarContenidoIA(string $contenido): string
    {
        $apiKey = OPENAI_API_KEY;
        $modeloTexto = "gpt-4.1-mini";

        $promptBase = <<<EOT
Eres un filtro de seguridad de mensajes. 
Valida si el siguiente texto es true o false para enviarse.  

Criterios:  
- true si contiene lenguaje sexual explícito, agravios, insultos u odio hacia la persona receptora.  
- false si es un mensaje respetuoso, neutro o emocional sin ofensas.  

Responde SOLO con una palabra:  
"true" o "false".  

Texto del usuario: 
$contenido
EOT;

        $ch = curl_init("https://api.openai.com/v1/responses");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $apiKey",
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            "model" => $modeloTexto,
            "input" => $promptBase
        ]));

        $result = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($result, true);
        $respuesta = $data['output'][0]['content'][0]['text'] ?? "false";

        return strtolower(trim($respuesta));
    }

    public function detectarDoxxingIA(string $contenido): string
    {
        $apiKey = OPENAI_API_KEY;
        $modeloTexto = "gpt-4.1-mini";

        $promptBase = <<<EOT
Eres un sistema especializado en detección de doxxing (exposición de información personal). 
Tu tarea es analizar el siguiente texto y determinar si el usuario está revelando información 
personal sensible propia o de otra persona.

Debes considerar que los usuarios pueden escribir datos personales de forma directa, parcial o implícita. 
También debes detectar variaciones, abreviaturas o intentos de disfrazar información (por ejemplo, 
“mi cel es ocho uno siete...” o “correo: juanperez arroba gmail punto com”).

Considera doxxing si el texto incluye o intenta compartir cualquiera de los siguientes tipos de información:
- **Identidad real**: nombres y apellidos reales, combinaciones de nombre completo o seudónimos que coincidan con nombres comunes.
- **Ubicación física**: direcciones exactas, calles, colonias, municipios, ciudades, códigos postales o cualquier referencia específica que permita ubicar a una persona.
- **Datos de contacto**: números telefónicos (reales o escritos con palabras), correos personales o laborales, identificadores de mensajería o redes sociales.
- **Identificadores personales**: CURP, RFC, NSS, matrícula, número de cuenta, número de empleado o cualquier código identificable.
- **Redes o plataformas**: nombres de usuario o enlaces a cuentas personales (como @usuario, perfiles de Facebook, Instagram, TikTok, etc.).
- **Instituciones personales**: escuelas, universidades, lugares de trabajo o cualquier organización directamente asociada con la persona.
- **Sitios personales**: blogs, páginas personales, portafolios, dominios o subdominios vinculados con el usuario.
- **Información técnica o financiera**: direcciones IP, datos bancarios, tarjetas, cuentas o cualquier dato financiero.

Evalúa con precaución el contexto. Si el texto solo menciona temas genéricos (por ejemplo, “trabajo en una empresa” o “vivo en una ciudad grande”), **no lo consideres doxxing**.
Si tienes duda o el texto es ambiguo, responde "doxxing".

Responde **solo con una palabra exacta**, sin explicaciones:
- `"doxxing"` → si detectas cualquier dato personal o intento de revelarlo.
- `"false"` → si el texto es seguro y no contiene información personal identificable.

Texto del usuario:
$contenido
EOT;

        $ch = curl_init("https://api.openai.com/v1/responses");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $apiKey",
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            "model" => $modeloTexto,
            "input" => $promptBase
        ]));

        $result = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($result, true);
        $respuesta = $data['output'][0]['content'][0]['text'] ?? "false";

        return strtolower(trim($respuesta));
    }


    public function guardar(string $titulo, string $contenido, int $anonima, int $id_usuaria): bool
    {
        $this->conectar();

        $validarTitulo = $this->moderarContenidoIA($titulo);
        $validarContenido = $this->moderarContenidoIA($contenido);

        if ($validarTitulo === "true" || $validarContenido === "true") {
            // Detenemos el guardado y mostramos alerta
            $_SESSION['sweet_alert'] = [
                'icon' => 'warning',
                'title' => 'Lenguaje inapropiado',
                'text' => 'Evitemos palabras ofensivas. Gracias.'
            ];
            return false;
        }

        $detectarDoxxingTitulo = $this->detectarDoxxingIA($titulo);
        $detectarDoxxingContenido = $this->detectarDoxxingIA($contenido);
        if ($detectarDoxxingTitulo === "doxxing" || $detectarDoxxingContenido === "doxxing") {
            $_SESSION['sweet_alert'] = [
                'icon' => 'warning',
                'title' => 'Información personal detectada',
                'text' => 'Evitemos compartir información personal o sensible. Gracias.'
            ];
            return false;
        }

        // Guardar publicación
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
    public function ultimoInsertId(): ?int
    {
        return $this->conn ? (int)$this->conn->lastInsertId() : null;
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

        $validarTitulo = $this->moderarContenidoIA($titulo);
        $validarContenido = $this->moderarContenidoIA($contenido);

        if ($validarTitulo === "true" || $validarContenido === "true") {
            // Detenemos el guardado y mostramos alerta
            $_SESSION['sweet_alert'] = [
                'icon' => 'warning',
                'title' => 'Lenguaje inapropiado',
                'text' => 'Evitemos palabras ofensivas. Gracias.'
            ];
            return false;
        }

        $detectarDoxxingTitulo = $this->detectarDoxxingIA($titulo);
        $detectarDoxxingContenido = $this->detectarDoxxingIA($contenido);
        if ($detectarDoxxingTitulo === "doxxing" || $detectarDoxxingContenido === "doxxing") {
            $_SESSION['sweet_alert'] = [
                'icon' => 'warning',
                'title' => 'Información personal detectada',
                'text' => 'Evitemos compartir información personal o sensible. Gracias.'
            ];
            return false;
        }

        try {
            $sql = "UPDATE publicacion 
                SET titulo = :titulo, contenido = :contenido 
                WHERE id_publicacion = :id";
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
            $stmtLikes->bindParam(1, $id, PDO::PARAM_INT);
            $stmtLikes->execute();

            $sqlComentarios = "DELETE FROM comentarios WHERE id_publicacion = ?";
            $stmtComentarios = $this->conn->prepare($sqlComentarios);
            $stmtComentarios->bindParam(1, $id, PDO::PARAM_INT);
            $stmtComentarios->execute();

            $sqlPublicacion = "DELETE FROM publicacion WHERE id_publicacion = ? AND id_usuarias = ?";
            $stmtPublicacion = $this->conn->prepare($sqlPublicacion);
            $stmtPublicacion->bindParam(1, $id, PDO::PARAM_INT);
            $stmtPublicacion->bindParam(2, $id_usuaria, PDO::PARAM_INT);

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
