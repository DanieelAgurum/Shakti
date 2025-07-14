<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/modelo/Conexion.php';

class EspecialistaModelo {
    private $conexion;

    public function __construct() {
        $db = new ConectarDB();
        $this->conexion = $db->open();
    }

    // Obtener todos los especialistas (id_rol = 2)
    public function obtenerTodos() {
        $sql = "SELECT id, nombre, descripcion FROM usuarias WHERE id_rol = 2";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Obtener usuaria o especialista por id o id2
    public function obtenerPorId($id) {
        $sql = "SELECT id, nombre, descripcion, id_rol FROM usuarias WHERE id = :id LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($resultado) {
            $resultado['foto'] = "/Shakti/controlador/ver_foto.php?id={$resultado['id']}";
        }

        return $resultado;
    }

    // Obtener múltiples usuarias o especialistas por IDs
    public function obtenerUsuariasPorIds(array $ids) {
        if (empty($ids)) return [];

        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        $sql = "SELECT id, nombre, descripcion FROM usuarias WHERE id IN ($placeholders)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute($ids);

        $resultados = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Agregar URL de foto para cada resultado
        foreach ($resultados as &$usuario) {
            $usuario['foto'] = "/Shakti/controlador/ver_foto.php?id={$usuario['id']}";
        }

        return $resultados;
    }

    // Generar un chatId determinista entre dos usuarios (especialista y usuaria)
    public function generarChatId($idUsuario1, $idUsuario2) {
        return ($idUsuario1 < $idUsuario2) 
            ? "{$idUsuario1}_{$idUsuario2}" 
            : "{$idUsuario2}_{$idUsuario1}";
    }
}
