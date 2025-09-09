<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

include_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/obtenerLink/obtenerLink.php';

$urlBase = getBaseUrl();

if (!isset($_SESSION['id_rol']) || !isset($_SESSION['correo'])) {
  header("Location: {$urlBase}index.php");
  exit;
}

include $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/usuaria/navbar.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Chat</title>
  <link rel="stylesheet" href="<?= $urlBase ?>css/stylesChat.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
  <link
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    rel="stylesheet" />
  <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
  <script src="<?= $urlBase ?>peticiones(js)/chat.js" defer></script>
</head>

<body>
  <div id="contenedor-chat">
    <div class="chat-list" id="chat-list"></div>

    <div id="chat-box" class="chat-box">
      <div class="chat-mensajes"></div>
      <form id="formulario" enctype="multipart/form-data" autocomplete="off">
        <input type="hidden" id="id_usuaria" name="id_usuaria" value="<?= htmlspecialchars($_SESSION['id_usuaria'] ?? '') ?>" />
        <input type="hidden" id="id_receptor" name="id_receptor" value="" />
        <input type="text" id="mensaje" name="mensaje" placeholder="Escribe tu mensaje" />

        <label for="archivo" class="btn-subir-imagen" title="Enviar imagen">
          <i class="fa-solid fa-image"></i>
        </label>
        <input type="file" id="archivo" name="imagen" accept="image/*" style="display:none" />

        <button type="submit" title="Enviar">
          <i class="fa-solid fa-paper-plane"></i>
        </button>
      </form>
    </div>
  </div>
</body>

</html>