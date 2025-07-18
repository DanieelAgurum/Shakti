<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/modelo/Conexion.php';

class EspecialistaModelo
{
    private $conexion;

    public function __construct()
    {
        $db = new ConectarDB();
        $this->conexion = $db->open();
    }

    // Obtener todos los especialistas (id_rol = 2)
    public function obtenerTodos()
    {
        $sql = "SELECT id, nombre, descripcion FROM usuarias WHERE id_rol = 2";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener especialista por ID
    public function obtenerPorId($id)
    {
        $sql = "SELECT id, nombre, descripcion, id_rol FROM usuarias WHERE id = :id LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            $resultado['foto'] = "/Shakti/controlador/ver_foto.php?id={$resultado['id']}";
        }

        return $resultado;
    }

    // Obtener múltiples especialistas por un array de IDs
    public function obtenerUsuariasPorIds(array $ids)
    {
        if (empty($ids)) return [];

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "SELECT id, nombre, descripcion FROM usuarias WHERE id IN ($placeholders)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute($ids);

        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($resultados as &$usuario) {
            $usuario['foto'] = "/Shakti/controlador/ver_foto.php?id={$usuario['id']}";
        }

        return $resultados;
    }

    // Generar ID de chat determinista
    public function generarChatId($idUsuario1, $idUsuario2)
    {
        return ($idUsuario1 < $idUsuario2)
            ? "{$idUsuario1}_{$idUsuario2}"
            : "{$idUsuario2}_{$idUsuario1}";
    }

    // Obtener especialistas paginados (PDO)
    public function obtenerEspecialistasPaginados($offset, $limite)
    {
        $sql = "SELECT id, nombre, apellidos, correo, foto, descripcion, telefono, estatus, nickname 
                FROM usuarias 
                WHERE estatus = 1 AND id_rol = 2 
                LIMIT :offset, :limite";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Contar total de especialistas
    public function contarTotalEspecialistas()
    {
        $sql = "SELECT COUNT(*) as total FROM usuarias WHERE estatus = 1 AND id_rol = 2";
        $stmt = $this->conexion->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    // Mostrar especialistas directamente (solo en pruebas o vistas simples)
    public function mostrarEspecialistas()
    {
        $sql = "SELECT id, nombre, apellidos, correo, foto, descripcion, telefono, estatus, nickname 
                FROM usuarias 
                WHERE estatus = 1 AND id_rol = 2";
        $stmt = $this->conexion->query($sql);

        echo '<div class="container"><div class="row" id="resultados">';

        foreach ($stmt as $row) {
            $foto = $row['foto'];
            $src = $foto ? 'data:image/jpeg;base64,' . base64_encode($foto) : 'https://cdn1.iconfinder.com/data/icons/avatar-3/512/Secretary-512.png';

            echo '
            <div class="col-md-4 mb-4">
                <div class="card testimonial-card animate__animated animate__backInUp animacion">
                    <div class="card-up aqua-gradient"></div>
                    <div class="avatar mx-auto white">
                        <img src="' . $src . '" class="rounded-circle" width="150" height="150" alt="Especialista">
                    </div>
                    <div class="card-body text-center">
                        <h4 class="card-title font-weight-bold">' . ucwords(htmlspecialchars($row['nombre'] . ' ' . $row['apellidos'])) . '</h4>
                        <p style="max-height: 70px; overflow-y: auto;" class="descripcion-scroll">' . ucwords(htmlspecialchars($row['descripcion'])) . '</p>
                        <hr>
                        <button type="button" class="btn btn-outline-secondary mt-2" data-bs-toggle="modal" data-bs-target="#modalEspecialista' . $row['id'] . '">
                            <i class="bi bi-eye-fill"></i> Ver perfil
                        </button>
                        <button type="button" class="btn btn-outline-primary mt-2" data-bs-toggle="modal" data-bs-target="#modalEspecialista' . $row['id'] . '">
                            <i class="bi bi-envelope-paper-heart"></i> Mensaje
                        </button>
                    </div>
                </div>
            </div>';

            include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/Vista/modales/especialistas.php';
        }

        if ($stmt->rowCount() == 0) {
            echo '<div class="col-md-12 text-center text-muted">No se encontraron especialistas.</div>';
        }

        echo '</div></div>';
    }
}
