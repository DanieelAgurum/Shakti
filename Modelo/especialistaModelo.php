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
        $sql = "SELECT id, nombre, descripcion, foto FROM usuarias WHERE id_rol = 2";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Obtener usuaria o especialista por id (útil para mostrar datos al listar chats desde Firebase)
    public function obtenerPorId($id) {
        $sql = "SELECT id, nombre, descripcion, foto, id_rol FROM usuarias WHERE id = :id LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // Generar un chatId determinista entre dos usuarios (especialista y usuaria)
    public function generarChatId($idUsuario1, $idUsuario2) {
        return ($idUsuario1 < $idUsuario2) 
            ? "{$idUsuario1}_{$idUsuario2}" 
            : "{$idUsuario2}_{$idUsuario1}";
    }

// En EspecialistaModelo.php
public function obtenerUsuariasPorIds(array $ids) {
    if (empty($ids)) return [];

    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $sql = "SELECT id, nombre, descripcion, foto FROM usuarias WHERE id IN ($placeholders)";
    $stmt = $this->conexion->prepare($sql);
    $stmt->execute($ids);

    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}

}
