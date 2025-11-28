<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Modelo/conexion.php';
session_start();

if (isset($_SESSION['id'])) {
    // Conectar BD
    $db = new ConectarDB();
    $conn = $db->open();
    // Destruir sesión
    session_destroy();
}

// Redirigir al login
header("Location: ../Vista/login");
exit;
