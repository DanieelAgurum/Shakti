<?php
require_once '../Modelo/ConfirmarCorreo.php';
include '../obtenerLink/obtenerLink.php';

$token = $_GET['token'] ?? '';
$urlBase = getBaseUrl();

if (!empty($token)) {
    $confirmar = new ConfirmarCorreo();
    
    // Inicializamos con algo temporal; la clase solo necesita urlBase y token aquí
    $confirmar->inicializar('', '', $urlBase, 0);

    // Verificar la cuenta usando el token
    $confirmar->verificarCuenta($token);
} else {
    header("Location: " . $urlBase . "/Vista/login.php?status=error&message=" . urlencode("Token no proporcionado"));
    exit;
}
