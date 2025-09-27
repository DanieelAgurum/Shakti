<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class SolicitudesMdl
{
    private $con;
    private $nickname;

    private function conectarBD()
    {
        if (!$this->con) {
            $this->con = new mysqli("localhost", "root", "", "shakti");
            if ($this->con->connect_error) {
                die("Error de conexión: " . $this->con->connect_error);
            }
            $this->con->set_charset("utf8mb4");
        }
        return $this->con;
    }

    public function inicializar($nickname)
    {
        $this->nickname = $nickname;
    }

    public function enviarSolicitud()
    {
        $nickname_yo = $_SESSION['nickname'];
        $nickname_amigo = $this->nickname;

        $conn = $this->conectarBD();

        // 1. Revisar si existe usuaria
        $sql = "SELECT 1 FROM usuarias WHERE nickname = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $nickname_amigo);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if (!$resultado || $resultado->num_rows === 0) {
            echo "no_existe";
            $stmt->close();
            return;
        }
        $stmt->close();

        // 2. Revisar si ya existe solicitud/amigos
        $sql = "SELECT 1 FROM amigos WHERE nickname_enviado = ? AND nickname_amigo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $nickname_yo, $nickname_amigo);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado && $resultado->num_rows > 0) {
            echo "ya_existe";
            $stmt->close();
            return;
        }
        $stmt->close();

        // 3. Insertar solicitud
        $sql = "INSERT INTO amigos (nickname_enviado, nickname_amigo, estado, enviado) 
            VALUES (?, ?, 'pendiente', current_timestamp())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $nickname_yo, $nickname_amigo);

        if ($stmt->execute()) {
            echo "enviada";
        } else {
            echo "no_enviada";
        }

        $stmt->close();
    }

    public function aceptarSolicitud()
    {
        echo $this->nickname;
    }

    public function rechazarSolicitud($nickname)
    {
        $sql = "SELECT * 
            FROM amigos 
            WHERE nickname_enviado = ?
              AND nickname_amigo = ? 
              AND estado = 'pendiente'";

        $stmt = $this->conectarBD()->prepare($sql);
        $stmt->bind_param('ss', $nickname, $_SESSION['nickname']);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            // Aquí puedes decidir: borrar o actualizar
            // Ejemplo borrar:
            $delete = "DELETE FROM amigos WHERE nickname_enviado = ? AND nickname_amigo = ? AND estado = 'pendiente'";
            $stmtDelete = $this->conectarBD()->prepare($delete);
            $stmtDelete->bind_param('ss', $nickname, $_SESSION['nickname']);
            $stmtDelete->execute();
            echo "rechazo";
        } else {
            echo "No existe solicitud pendiente para cancelar.";
        }
    }


    public function obtenerSolicitudes()
    {
        $usuarioPrincipal = $_SESSION['nickname'] ?? null;

        if (!$usuarioPrincipal) {
            echo '<div class="solicitud-vacia"><p>No hay usuario en sesión</p></div>';
            return;
        }

        $sql = "SELECT u.nickname, u.nombre, u.foto
            FROM amigos a
            JOIN usuarias u ON a.nickname_enviado = u.nickname
            WHERE a.nickname_amigo = ?
              AND a.estado = 'pendiente'
            ORDER BY a.enviado DESC
            LIMIT 25";

        $stmt = $this->conectarBD()->prepare($sql);
        if (!$stmt) {
            echo '<div class="solicitud-vacia"><p>Error en la consulta</p></div>';
            return;
        }

        $stmt->bind_param("s", $usuarioPrincipal);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if (!$resultado || $resultado->num_rows === 0) {
            echo '<div class="solicitud-vacia"><p>Sin solicitudes</p></div>';
        } else {
            while ($fila = $resultado->fetch_assoc()) {
                $fotoUrl = !empty($fila['foto'])
                    ? 'data:image/jpeg;base64,' . base64_encode($fila['foto'])
                    : "https://cdn1.iconfinder.com/data/icons/avatar-3/512/Secretary-512.png";

                echo '
            <div class="solicitud-card">
                <div class="solicitud-info" data-soli-nickname="' . htmlspecialchars($fila['nickname']) . '">
                    <img src="' . htmlspecialchars($fotoUrl) . '" 
                         alt="Foto usuario" 
                         class="solicitud-img" 
                         loading="lazy">
                    <div class="solicitud-detalle">
                        <p class="solicitud-nombre">' . htmlspecialchars($fila['nickname']) . '</p>
                        <div class="solicitud-acciones">  
                            <button class="btn btn-banner-rojo" data-nickname="' . htmlspecialchars($fila['nickname']) . '">Rechazar</button>
                            <button class="btn btn-banner-azul" data-nickname="' . htmlspecialchars($fila['nickname']) . '">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>';
            }
        }

        $stmt->close();
        $this->conectarBD()->close();
    }

    public function obtenerUsuarios()
    {
        $usuarios = '';
        $usuarioPrincipal = $_SESSION['nickname'] ?? null;
        $buscador = $_GET['buscador'] ?? '';
        $buscador = trim($buscador);

        if ($buscador !== '') {
            // 🔹 Cuando hay búsqueda
            $sql = "SELECT u.nickname, u.nombre, u.foto, a.estado, a.nickname_enviado, a.nickname_amigo
        FROM usuarias u
        LEFT JOIN amigos a 
          ON (
               (a.nickname_enviado = u.nickname AND a.nickname_amigo = ?) 
            OR (a.nickname_amigo = u.nickname AND a.nickname_enviado = ?)
          )
        WHERE (u.nickname LIKE ? OR u.nombre LIKE ?)
        LIMIT 25";
            $stmt = $this->conectarBD()->prepare($sql);
            $like = "%{$buscador}%";
            $stmt->bind_param("ssss", $usuarioPrincipal, $usuarioPrincipal, $like, $like);
        } else {
            // 🔹 Cuando NO hay búsqueda → listar todos los usuarios excepto yo
            $sql = "SELECT u.nickname, u.nombre, u.foto,
        MAX(a.estado) AS estado, 
        MAX(a.nickname_enviado) AS nickname_enviado, 
        MAX(a.nickname_amigo) AS nickname_amigo
    FROM usuarias u
    LEFT JOIN amigos a 
        ON (
            (a.nickname_enviado = u.nickname AND a.nickname_amigo = ?) 
         OR (a.nickname_amigo = u.nickname AND a.nickname_enviado = ?)
        )
    WHERE u.nickname <> ?
    GROUP BY u.nickname, u.nombre, u.foto
    LIMIT 50
";
            $stmt = $this->conectarBD()->prepare($sql);
            $stmt->bind_param("sss", $usuarioPrincipal, $usuarioPrincipal, $usuarioPrincipal);

            $stmt = $this->conectarBD()->prepare($sql);
            $stmt->bind_param("sss", $usuarioPrincipal, $usuarioPrincipal, $usuarioPrincipal);
        }

        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado && $resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $fotoUrl = !empty($fila['foto'])
                    ? 'data:image/jpeg;base64,' . base64_encode($fila['foto'])
                    : "https://cdn1.iconfinder.com/data/icons/avatar-3/512/Secretary-512.png";

                $usuarios .= '
<div class="usuario-card">
    <img src="' . htmlspecialchars($fotoUrl) . '" alt="Foto usuario" class="usuario-img" loading="lazy">
    <p class="usuario-nombre">' . htmlspecialchars($fila['nickname']) . '</p>';

                // Contenedor de acciones
                $usuarios .= '<div data-soli-usuario-nickname="' . htmlspecialchars($fila['nickname']) . '">';

                if ($fila['nickname'] === $usuarioPrincipal) {
                    $usuarios .= '<p class="text-muted small">Este eres tú</p>';
                } elseif ($fila['nickname_enviado'] === $usuarioPrincipal && $fila['estado'] === "pendiente") {
                    $usuarios .= '
        <button type="button" class="btn btn-warning btn-cancelar" data-nickname="' . htmlspecialchars($fila['nickname']) . '">
            Cancelar Solicitud <i class="bi bi-x-circle"></i>
        </button>';
                } elseif ($fila['nickname_amigo'] === $usuarioPrincipal && $fila['estado'] === "pendiente") {
                    $usuarios .= '
        <button class="btn btn-banner-rojo margin-boton-botones" data-nickname="' . htmlspecialchars($fila['nickname']) . '">Rechazar</button>
        <button class="btn btn-banner-azul btn-agregado" data-nickname="' . htmlspecialchars($fila['nickname']) . '">Aceptar</button>';
                } else {
                    $usuarios .= '
        <button type="button" class="btn btn-banner-azul btn-agregar" data-nickname="' . htmlspecialchars($fila['nickname']) . '">
            Agregar Amigo <i class="bi bi-person-add"></i>
        </button>';
                }

                $usuarios .= '</div>';
                $usuarios .= '</div>';
            }
        } else {
            $usuarios = '<div class="solicitud-vacia"><p>Sin usuarios</p></div>';
        }

        echo $usuarios;
    }

    public function cancelarSolicitud($nicknameAmigo)
    {
        $miNickname = $_SESSION['nickname'];
        $sql = "SELECT * 
            FROM amigos 
            WHERE nickname_enviado = ? 
              AND nickname_amigo = ? 
              AND estado = 'pendiente'";

        $stmt = $this->conectarBD()->prepare($sql);
        $stmt->bind_param('ss', $miNickname, $nicknameAmigo);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            // Aquí puedes decidir: borrar o actualizar
            // Ejemplo borrar:
            $delete = "DELETE FROM amigos WHERE nickname_enviado = ? AND nickname_amigo = ? AND estado = 'pendiente'";
            $stmtDelete = $this->conectarBD()->prepare($delete);
            $stmtDelete->bind_param('ss', $miNickname, $nicknameAmigo);
            $stmtDelete->execute();
            echo "cancelado";
        } else {
            echo "No existe solicitud pendiente para cancelar.";
        }
    }
}
