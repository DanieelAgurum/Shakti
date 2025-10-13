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
        $this->nickname = trim($nickname);
    }

    private function prepararYEjecutar($sql, $params = [], $types = "")
    {
        $conn = $this->conectarBD();
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            return false;
        }
        if (!empty($params) && $types) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        return $stmt;
    }

    public function enviarSolicitud()
    {
        $nickname_yo = $_SESSION['nickname'] ?? null;
        $nickname_amigo = $this->nickname;

        if (!$nickname_yo || !$nickname_amigo) {
            echo "error";
            return;
        }

        // 1. Revisar si existe usuaria
        $sql = "SELECT 1 FROM usuarias WHERE nickname = ?";
        $stmt = $this->prepararYEjecutar($sql, [$nickname_amigo], "s");
        if (!$stmt) {
            echo "error";
            return;
        }

        $resultado = $stmt->get_result();
        if (!$resultado || $resultado->num_rows === 0) {
            echo "no_existe";
            $stmt->close();
            return;
        }
        $stmt->close();

        // 2. Revisar si ya existe solicitud/amigos
        $sql = "SELECT 1 FROM amigos WHERE nickname_enviado = ? AND nickname_amigo = ?";
        $stmt = $this->prepararYEjecutar($sql, [$nickname_yo, $nickname_amigo], "ss");
        $resultado = $stmt->get_result();
        if ($resultado && $resultado->num_rows > 0) {
            echo "ya_existe";
            $stmt->close();
            return;
        }
        $stmt->close();

        // 3. Insertar solicitud
        $sql = "INSERT INTO amigos (nickname_enviado, nickname_amigo, estado, enviado) VALUES (?, ?, 'pendiente', current_timestamp())";
        $stmt = $this->prepararYEjecutar($sql, [$nickname_yo, $nickname_amigo], "ss");
        if ($stmt && $stmt->affected_rows > 0) {
            echo "enviada";
        } else {
            echo "no_enviada";
        }
        $stmt->close();
    }
    public function aceptarSolicitud($nickname)
    {
        $conn = $this->conectarBD();

        // 1. Verificar si existe solicitud pendiente
        $sqlVerificar = "SELECT 1 FROM amigos 
                     WHERE nickname_enviado = ? 
                       AND nickname_amigo = ? 
                       AND estado = 'pendiente'";
        $stmtVerificar = $conn->prepare($sqlVerificar);
        $stmtVerificar->bind_param("ss", $nickname, $_SESSION['nickname']);
        $stmtVerificar->execute();
        $resultado = $stmtVerificar->get_result();

        if ($resultado && $resultado->num_rows > 0) {
            $stmtVerificar->close();

            // 2. Si existe, actualizar el estado
            $sqlActualizar = "UPDATE amigos SET estado = 'aceptado' 
                          WHERE nickname_enviado = ? 
                            AND nickname_amigo = ? 
                            AND estado = 'pendiente'";
            $stmtActualizar = $this->prepararYEjecutar($sqlActualizar, [$nickname, $_SESSION['nickname']], "ss");

            if ($stmtActualizar && $stmtActualizar->affected_rows > 0) {
                echo "aceptado";
            } else {
                echo "error_actualizar";
            }
            if ($stmtActualizar) $stmtActualizar->close();
        } else {
            echo "no_existe";
            $stmtVerificar->close();
        }
    }
    public function rechazarSolicitud($nickname)
    {
        $conn = $this->conectarBD();

        // 1. Verificar si existe solicitud pendiente
        $sqlVerificar = "SELECT 1 FROM amigos 
                     WHERE nickname_enviado = ? 
                       AND nickname_amigo = ? 
                       AND estado = 'pendiente'";
        $stmtVerificar = $conn->prepare($sqlVerificar);
        $stmtVerificar->bind_param("ss", $nickname, $_SESSION['nickname']);
        $stmtVerificar->execute();
        $resultado = $stmtVerificar->get_result();

        if ($resultado && $resultado->num_rows > 0) {
            $stmtVerificar->close();

            // 2. Si existe, eliminar
            $sqlEliminar = "DELETE FROM amigos 
                        WHERE nickname_enviado = ? 
                          AND nickname_amigo = ? 
                          AND estado = 'pendiente'";
            $stmtEliminar = $this->prepararYEjecutar($sqlEliminar, [$nickname, $_SESSION['nickname']], "ss");

            if ($stmtEliminar && $stmtEliminar->affected_rows > 0) {
                echo "rechazo";
            } else {
                echo "error_eliminar";
            }
            if ($stmtEliminar) $stmtEliminar->close();
        } else {
            echo "no_existe";
            $stmtVerificar->close();
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
                WHERE a.nickname_amigo = ? AND a.estado = 'pendiente'
                ORDER BY a.enviado DESC
                LIMIT 25";

        $stmt = $this->prepararYEjecutar($sql, [$usuarioPrincipal], "s");
        if (!$stmt) {
            echo '<div class="solicitud-vacia"><p>Error en la consulta</p></div>';
            return;
        }

        $resultado = $stmt->get_result();
        if (!$resultado || $resultado->num_rows === 0) {
            echo '<div class="solicitud-vacia"><p>Sin solicitudes</p></div>';
            return;
        }

        while ($fila = $resultado->fetch_assoc()) {
            $fotoUrl = !empty($fila['foto'])
                ? 'data:image/jpeg;base64,' . base64_encode($fila['foto'])
                : "https://cdn1.iconfinder.com/data/icons/avatar-3/512/Secretary-512.png";

            echo '
            <div class="solicitud-card">
                <div class="solicitud-info" data-soli-nickname="' . htmlspecialchars($fila['nickname']) . '">
                    <img src="' . htmlspecialchars($fotoUrl) . '" alt="Foto usuario" class="solicitud-img" loading="lazy">
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
        $stmt->close();
    }
    public function obtenerUsuarios()
    {
        $usuarios = '';
        $usuarioPrincipal = $_SESSION['nickname'] ?? null;
        $buscador = trim($_GET['buscador'] ?? '');

        if (!$usuarioPrincipal) {
            echo '<div class="solicitud-vacia"><p>No hay usuario en sesión</p></div>';
            return;
        }

        if ($buscador !== '') {
            $sql = "SELECT u.nickname, u.nombre, u.foto, a.estado, a.nickname_enviado, a.nickname_amigo
                FROM usuarias u
                LEFT JOIN amigos a ON (
                    (a.nickname_enviado = u.nickname AND a.nickname_amigo = ?) 
                    OR (a.nickname_amigo = u.nickname AND a.nickname_enviado = ?)
                )
                WHERE (u.nickname LIKE ? OR u.nombre LIKE ?)
                LIMIT 25";
            $like = "%{$buscador}%";
            $stmt = $this->prepararYEjecutar($sql, [$usuarioPrincipal, $usuarioPrincipal, $like, $like], "ssss");
        } else {
            $sql = "SELECT u.nickname, u.nombre, u.foto,
                       MAX(a.estado) AS estado, 
                       MAX(a.nickname_enviado) AS nickname_enviado, 
                       MAX(a.nickname_amigo) AS nickname_amigo
                FROM usuarias u
                LEFT JOIN amigos a ON (
                    (a.nickname_enviado = u.nickname AND a.nickname_amigo = ?) 
                    OR (a.nickname_amigo = u.nickname AND a.nickname_enviado = ?)
                )
                WHERE u.nickname <> ?
                GROUP BY u.nickname, u.nombre, u.foto
                LIMIT 50";
            $stmt = $this->prepararYEjecutar($sql, [$usuarioPrincipal, $usuarioPrincipal, $usuarioPrincipal], "sss");
        }

        if (!$stmt) {
            echo '<div class="solicitud-vacia"><p>No se pudo obtener registro alguno</p></div>';
            return;
        }

        $resultado = $stmt->get_result();
        if (!$resultado || $resultado->num_rows === 0) {
            echo '<div class="solicitud-vacia"><p>Sin usuarios</p></div>';
            return;
        }

        while ($fila = $resultado->fetch_assoc()) {
            $fotoUrl = !empty($fila['foto'])
                ? 'data:image/jpeg;base64,' . base64_encode($fila['foto'])
                : "https://cdn1.iconfinder.com/data/icons/avatar-3/512/Secretary-512.png";

            $usuarios .= '<div class="usuario-card">
            <img src="' . htmlspecialchars($fotoUrl) . '" alt="Foto usuario" class="usuario-img" loading="lazy">
            <p class="usuario-nombre">' . htmlspecialchars($fila['nickname']) . '</p>
            <div data-soli-usuario-nickname="' . htmlspecialchars($fila['nickname']) . '">';

            if ($fila['nickname'] === $usuarioPrincipal) {
                // Es el mismo usuario
                $usuarios .= '<p class="text-muted small">Este eres tú</p>';
            } elseif ($fila['estado'] === "aceptado") {
                // Ya son amigos
                $usuarios .= '<button type="button" class="btn btn-secondary btn-agregado" data-nickname="' . htmlspecialchars($fila['nickname']) . '">
                            Agregado <i class="bi bi-person-check"></i>
                          </button>';
            } elseif ($fila['nickname_enviado'] === $usuarioPrincipal && $fila['estado'] === "pendiente") {
                // Yo envié solicitud
                $usuarios .= '<button type="button" class="btn btn-warning btn-cancelar" data-nickname="' . htmlspecialchars($fila['nickname']) . '">
                            Cancelar Solicitud <i class="bi bi-x-circle"></i>
                          </button>';
            } elseif ($fila['nickname_amigo'] === $usuarioPrincipal && $fila['estado'] === "pendiente") {
                // Me enviaron solicitud
                $usuarios .= '<button class="btn btn-banner-rojo margin-boton-botones" data-nickname="' . htmlspecialchars($fila['nickname']) . '">Rechazar</button>
                          <button class="btn btn-banner-azul" data-nickname="' . htmlspecialchars($fila['nickname']) . '">Aceptar</button>';
            } else {
                // No hay relación
                $usuarios .= '<button type="button" class="btn btn-banner-azul btn-agregar" data-nickname="' . htmlspecialchars($fila['nickname']) . '">
                            Agregar Amigo <i class="bi bi-person-add"></i>
                          </button>';
            }

            $usuarios .= '</div></div>';
        }

        echo $usuarios;
        $stmt->close();
    }
    public function cancelarSolicitud($nicknameAmigo)
    {
        $miNickname = $_SESSION['nickname'] ?? null;
        if (!$miNickname) {
            echo "error";
            return;
        }

        $sql = "DELETE FROM amigos WHERE nickname_enviado = ? AND nickname_amigo = ? AND estado = 'pendiente'";
        $stmt = $this->prepararYEjecutar($sql, [$miNickname, $nicknameAmigo], "ss");

        if ($stmt && $stmt->affected_rows > 0) {
            echo "cancelado";
        } else {
            echo "no_existe";
        }
        if ($stmt) $stmt->close();
    }
    public function eliminarAmigo($nickname)
    {
        $conn = $this->conectarBD();

        // 1. Verificar si existe amistad aceptada
        $sqlVerificar = "SELECT 1 FROM amigos 
                     WHERE (
                         (nickname_enviado = ? AND nickname_amigo = ?) 
                         OR 
                         (nickname_enviado = ? AND nickname_amigo = ?)
                     )
                     AND estado = 'aceptado'";
        $stmtVerificar = $conn->prepare($sqlVerificar);
        $stmtVerificar->bind_param("ssss", $nickname, $_SESSION['nickname'], $_SESSION['nickname'], $nickname);
        $stmtVerificar->execute();
        $resultado = $stmtVerificar->get_result();

        if ($resultado && $resultado->num_rows > 0) {
            $stmtVerificar->close();

            // 2. Eliminar si está aceptado
            $sqlEliminar = "DELETE FROM amigos 
                        WHERE (
                            (nickname_enviado = ? AND nickname_amigo = ?) 
                            OR 
                            (nickname_enviado = ? AND nickname_amigo = ?)
                        )
                        AND estado = 'aceptado'";
            $stmtEliminar = $conn->prepare($sqlEliminar);
            $stmtEliminar->bind_param("ssss", $nickname, $_SESSION['nickname'], $_SESSION['nickname'], $nickname);
            $stmtEliminar->execute();

            if ($stmtEliminar && $stmtEliminar->affected_rows > 0) {
                echo "eliminado";
            } else {
                echo "error_eliminar";
            }

            if ($stmtEliminar) $stmtEliminar->close();
        } else {
            echo "no_existe";
            $stmtVerificar->close();
        }
    }
    // Especialistas 
    public function obtenerEspecialistas($buscador = null, $limit = 10, $offset = 0)
    {
        $conn = $this->conectarBD();
        $cards = '';

        if ($buscador && trim($buscador) !== '') {
            $like = "%" . trim($buscador) . "%";
            $sql = "SELECT u.id, u.nombre, u.apellidos, u.descripcion, u.foto
                FROM usuarias u
                WHERE u.id_rol = 2 
                  AND u.estatus = 1 
                  AND (u.nickname LIKE ? OR u.nombre LIKE ?)
                LIMIT ? OFFSET ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssii", $like, $like, $limit, $offset);
        } else {
            $sql = "SELECT u.id, u.nombre, u.apellidos, u.correo, u.foto, u.descripcion, 
                       u.telefono, u.estatus, u.nickname, s.servicio
                FROM usuarias u
                LEFT JOIN servicios_especialistas s ON u.id = s.id_usuaria
                WHERE u.estatus = 1 AND u.id_rol = 2
                LIMIT ? OFFSET ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $limit, $offset);
        }

        if (!$stmt) {
            echo '<div class="alert alert-danger text-center">Error en la consulta</div>';
            return;
        }

        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 0) {
            echo '<div class="col-12 text-center">No se encontraron especialistas</div>';
            return;
        }

        while ($row = $resultado->fetch_assoc()) {
            $id = htmlspecialchars($row['id']);
            $src = !empty($row['foto'])
                ? 'data:image/jpeg;base64,' . base64_encode($row['foto'])
                : 'https://cdn1.iconfinder.com/data/icons/avatar-3/512/Doctor-512.png';

            $nombre = htmlspecialchars($row['nombre'] ?? '', ENT_QUOTES, 'UTF-8');
            $apellidos = htmlspecialchars($row['apellidos'] ?? '', ENT_QUOTES, 'UTF-8');
            $descripcion = htmlspecialchars($row['descripcion'] ?? '', ENT_QUOTES, 'UTF-8');

            $cards .= '<div class="col-md-4 mb-4">
            <div class="card testimonial-card ">
                <div class="card-up"></div>
                <div class="avatar mx-auto white">
                    <img src="' . $src . '" class="rounded-circle" width="150" height="150" alt="Especialista">
                </div>
                <div class="card-body text-center">
                    <h4 class="card-title font-weight-bold">' . ucwords($nombre . ' ' . $apellidos) . '</h4>
                    <p style="max-height: 70px; overflow-y: auto;" class="descripcion-scroll">' . ucwords($descripcion) . '</p>
                    <hr>
                    <button type="button" class="btn btn-outline-secondary mt-2" data-bs-toggle="modal" data-bs-target="#modalEspecialista' . $id . '">
                        <i class="bi bi-eye-fill"></i> Ver perfil
                    </button>
                    <a href="/Vista/chat.php" class="btn btn-outline-primary mt-2">
                        <i class="bi bi-envelope-paper-heart"></i> Mensaje</a>
                </div>
            </div>
        </div>';
            include $_SERVER['DOCUMENT_ROOT'] . '/shakti/Vista/modales/especialistas.php';
        }

        echo $cards;
        $stmt->close();
    }
}
