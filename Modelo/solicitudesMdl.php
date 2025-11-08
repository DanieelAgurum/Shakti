<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class SolicitudesMdl
{
    private $con;

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
    // Especialistas 
    public function obtenerEspecialistas($buscador = null, $limit = 10, $offset = 0)
    {
        $conn = $this->conectarBD();
        $cards = '';

        if ($buscador && trim($buscador) !== '') {
            $like = "%" . trim($buscador) . "%";
            $sql = "SELECT u.id, u.nombre, u.apellidos, u.correo, u.foto, u.descripcion, 
                       u.telefono, u.estatus, u.nickname, u.id_rol, s.servicio
                FROM usuarias u
                LEFT JOIN servicios_especialistas s ON u.id = s.id_usuaria
                WHERE u.id_rol = 2 
                AND u.estatus = 1
                  AND (u.nickname LIKE ? OR u.nombre LIKE ?)
                LIMIT ? OFFSET ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssii", $like, $like, $limit, $offset);
        } else {
            $sql = "SELECT u.id, u.nombre, u.apellidos, u.correo, u.foto, u.descripcion, 
                       u.telefono, u.estatus, u.nickname, u.id_rol, s.servicio
                FROM usuarias u
                LEFT JOIN servicios_especialistas s ON u.id = s.id_usuaria
                WHERE u.id_rol = 2 
                AND u.estatus = 1
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
            <div class="card testimonial-card specialist-card shadow-custom rounded-4 border-0 overflow-hidden">
                <div class="card-up gradient-header"></div>
                <div class="avatar mx-auto position-relative specialist-avatar">
                    <img src="' . $src . '" class="rounded-circle border border-white border-5 img-avatar" width="150" height="150" alt="Especialista">
                </div>
                <div class="card-body text-center specialist-body">
                    <h4 class="card-title specialist-name font-weight-bold">' . ucwords($nombre . ' ' . $apellidos) . '</h4>
                    <p style="max-height: 70px; overflow-y: auto;" class="descripcion-scroll">' . ucwords($descripcion) . '</p>
                    <hr class="divider-line mx-auto">
                    <button type="button" class="btn btn-outline-secondary mt-2" data-bs-toggle="modal" data-bs-target="#modalEspecialista' . $id . '">
                        <i class="bi bi-eye-fill"></i> Ver perfil
                    </button>
                    <a href="/shakti/Vista/chat?especialistas=' . $this->cifrarAES($id) . '" class="btn btn-outline-primary mt-2">
                        <i class="bi bi-envelope-paper-heart"></i> Mensaje</a>
                </div>
            </div>
        </div>';
            include $_SERVER['DOCUMENT_ROOT'] . '/shakti/Vista/modales/especialistas.php';
        }

        echo $cards;
        $stmt->close();
    }


    private function cifrarAES($id)
    {
        $clave = hash('sha256', 'xN7$wA9!tP3@zLq6VbE2#mF8jR1&yC5Q', true);
        $ci = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $cifrado = openssl_encrypt($id, 'aes-256-cbc', $clave, 0, $ci);
        return strtr(base64_encode($ci . $cifrado), '+/=', '-_,');
    }
}
