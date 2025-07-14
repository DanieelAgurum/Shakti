<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/modelo/EspecialistaModelo.php';

class EspecialistaControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new EspecialistaModelo();
    }

    // Retorna la lista completa de especialistas (id_rol = 2)
    public function listarEspecialistas() {
        return $this->modelo->obtenerTodos();
    }

    // Obtiene el chatId determinista entre dos usuarios (especialista y usuaria)
    public function getChatId($id1, $id2) {
        return $this->modelo->generarChatId($id1, $id2);
    }

    // Validación si especialista puede responder a chat con usuaria
    public function puedeResponderChat($idEspecialista, $idUsuaria) {
        // Por ahora, solo devuelve el chatId, la validación real depende de Firebase o reglas de negocio
        return $this->getChatId($idEspecialista, $idUsuaria);
    }

    // En EspecialistaControlador.php

public function obtenerUsuariasPorIds(array $ids) {
    return $this->modelo->obtenerUsuariasPorIds($ids);
}

}
