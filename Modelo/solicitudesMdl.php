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

    public function agregarAmigo()
    {
        $nickname_yo = $_SESSION['nickname'];
        $nickname_amigo = $this->nickname;

        $sql = "SELECT * FROM usuarias WHERE nickname = ?";
        $stmt = $this->conectarBD()->prepare($sql);
        $stmt->bind_param("s", $this->nickname);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado && $resultado->num_rows > 0) {
            $sql = "INSERT INTO amigos (nickname_enviado, nickname_amigo, estado, enviado) 
                VALUES (?, ?, 'pendiente', current_timestamp())";
            $stmt = $this->conectarBD()->prepare($sql);
            $stmt->bind_param("ss", $nickname_yo, $nickname_amigo);

            if ($stmt->execute()) {
                echo "enviada";
            } else {
                echo "no_enviada";
            }
        } else {
            echo "no_existe";
        }
    }

    public function aceptarSolicitud()
    {
        echo $this->nickname;
    }

    public function obtenerSolicitudes()
    {
        $usuarioPrincipal = $_SESSION['nickname'] ?? null;
        $solicitudes = '';

        if (!$usuarioPrincipal) {
            echo '<div class="solicitud-vacia"><p>No hay usuario en sesión</p></div>';
            return;
        }

        $sql = "
            SELECT a.id_amigos, u.nickname, u.nombre, u.foto
            FROM amigos a
            JOIN usuarias u ON a.nickname_enviado = u.nickname
            WHERE a.nickname_amigo = ?
              AND a.estado = 'pendiente'
            ORDER BY a.enviado DESC
            LIMIT 25";

        $stmt = $this->conectarBD()->prepare($sql);
        $stmt->bind_param("s", $usuarioPrincipal);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado && $resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $fotoUrl = !empty($fila['foto'])
                    ? 'data:image/jpeg;base64,' . base64_encode($fila['foto'])
                    : "https://cdn1.iconfinder.com/data/icons/avatar-3/512/Secretary-512.png";

                $solicitudes .= '
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
</div>
';
            }
        } else {
            $solicitudes = '<div class="solicitud-vacia"><p>Sin solicitudes</p></div>';
        }

        echo $solicitudes;
    }

    public function obtenerUsuarios()
    {
        $usuarios = '';
        $usuarioPrincipal = $_SESSION['nickname'] ?? null;
        $buscador = $_GET['buscador'] ?? '';
        $buscador = trim($buscador);

        if ($buscador !== '') {
            $sql = "
        SELECT u.nickname, u.nombre, u.foto, a.estado, a.nickname_enviado, a.nickname_amigo
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
            $sql = "
        SELECT u.nickname, u.nombre, u.foto, a.estado, a.nickname_enviado, a.nickname_amigo
        FROM usuarias u
        LEFT JOIN amigos a 
          ON (
               (a.nickname_enviado = u.nickname AND a.nickname_amigo = ?) 
            OR (a.nickname_amigo = u.nickname AND a.nickname_enviado = ?)
          )
        WHERE u.nickname != ?
        LIMIT 25";
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
                    // Es el mismo usuario
                    $usuarios .= '<p class="text-muted small">Este eres tú</p>';
                } elseif ($fila['nickname_enviado'] === $usuarioPrincipal && $fila['estado'] === "pendiente") {
                    // Yo envié la solicitud → mostrar cancelar
                    $usuarios .= '
        <button type="button" class="btn btn-warning btn-cancelar" data-nickname="' . htmlspecialchars($fila['nickname']) . '">
            Cancelar Solicitud <i class="bi bi-x-circle"></i>
        </button>';
                } elseif ($fila['nickname_amigo'] === $usuarioPrincipal && $fila['estado'] === "pendiente") {
                    // El otro me envió → mostrar aceptar/rechazar
                    $usuarios .= '
        <button class="btn btn-banner-rojo margin-boton-botones" data-nickname="' . htmlspecialchars($fila['nickname']) . '">Rechazar</button>
        <button class="btn btn-banner-azul btn-agregado" data-nickname="' . htmlspecialchars($fila['nickname']) . '">Aceptar</button>';
                } else {
                    // No hay solicitud → mostrar agregar
                    $usuarios .= '
        <button type="button" class="btn btn-banner-azul btn-agregar" data-nickname="' . htmlspecialchars($fila['nickname']) . '">
            Agregar Amigo <i class="bi bi-person-add"></i>
        </button>';
                }

                $usuarios .= '</div>'; // cerrar div de acciones
                $usuarios .= '</div>'; // cerrar usuario-card
            }
        } else {
            $usuarios = '<div class="solicitud-vacia"><p>Sin usuarios</p></div>';
        }

        echo $usuarios;
    }
}
