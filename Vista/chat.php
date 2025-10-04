<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

include_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/obtenerLink/obtenerLink.php';
$urlBase = getBaseUrl();

// Validar sesión
if (!isset($_SESSION['id_rol']) || !isset($_SESSION['correo'])) {
  header("Location: {$urlBase}index.php");
  exit;
}

// Navbar
include $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/usuaria/navbar.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chat</title>

  <!-- Estilos -->
  <link rel="stylesheet" href="<?= $urlBase ?>css/stylesChat.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- Scripts -->
  <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
  <script src="<?= $urlBase ?>peticiones(js)/chat.js"></script>
</head>

<body>
  <div id="contenedor-chat">
    <!-- Lista de chats -->
    <aside class="chat-list">
      <!-- Chat fijo: Ian Bot -->
      <div class="chat-activo" data-id-amigo="0">
        <img src="<?= $urlBase ?>img/ianbot.png" alt="Ian Bot">
        <div class="info-chat">
          <strong>Ian Bot</strong>
          <small></small>
        </div>
      </div>
      <!-- Encabezado -->
      <div class="chat-header">
        <h3>Chats</h3>
      </div>
      <!-- Contenedor para chats dinámicos -->
      <div id="chat-list"></div>
    </aside>

    <!-- Caja de mensajes -->
    <section id="chat-box" class="chat-box">
      <div class="chat-mensajes"></div>
      <form id="formulario" enctype="multipart/form-data" autocomplete="off">
        <input type="hidden" id="id_usuaria" name="id_usuaria"
          value="<?= htmlspecialchars($_SESSION['id_usuaria'] ?? '') ?>">
        <input type="hidden" id="id_receptor" name="id_receptor" value="">
        <input type="text" id="mensaje" name="mensaje" placeholder="Escribe tu mensaje">

        <!-- Botón para subir imagen -->
        <label for="archivo" class="btn-subir-imagen" title="Enviar imagen">
          <i class="fa-solid fa-image"></i>
        </label>
        <input type="file" id="archivo" name="imagen" accept="image/*" style="display: none;">

        <!-- Botón enviar -->
        <button type="submit" title="Enviar">
          <i class="fa-solid fa-paper-plane"></i>
        </button>
      </form>
    </section>
  </div>
</body>

</html>