<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/controlador/EspecialistaControlador.php';

$rolUsuario = $_SESSION['id_rol'];
$idUsuario = $_SESSION['id'];

$especialistaControlador = new EspecialistaControlador();

if ($rolUsuario == 2) {
    
    $usuariosChat = [];
    $tituloLista = "Usuarias con chat activo";
} else {

    $usuariosChat = $especialistaControlador->listarEspecialistas();
    $tituloLista = "Especialistas";
}

include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/usuaria/navbar.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Chat - <?= htmlspecialchars($tituloLista) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
  <style>
    #lista-especialistas {
      max-height: 80vh;
      overflow-y: auto;
    }
    #chat-area {
      max-height: 80vh;
      border-left: 1px solid #ddd;
      overflow-y: auto;
      padding: 1rem;
    }
    .mensaje {
      max-width: 75%;
      padding: 0.5rem 1rem;
      border-radius: 20px;
      margin-bottom: 0.5rem;
      word-wrap: break-word;
    }
    .mensaje.usuario {
      background-color: #007bff;
      color: white;
      margin-left: auto;
    }
    .mensaje.especialista {
      background-color: #e9ecef;
      color: black;
      margin-right: auto;
    }
    #lista-especialistas::-webkit-scrollbar {
      width: 8px;
    }
    #lista-especialistas::-webkit-scrollbar-thumb {
      background-color: rgba(0, 0, 0, 0.1);
      border-radius: 4px;
    }
  </style>
</head>

<body>
  <div class="container-fluid mt-4">
    <div class="row">
      <div class="col-md-4" id="lista-especialistas">
        <h5><?= htmlspecialchars($tituloLista) ?></h5>

        <?php if (!empty($usuariosChat)) : ?>
          <?php foreach ($usuariosChat as $usuario) : ?>
            <div class="card mb-3" style="max-width: 540px; cursor: pointer;" onclick="seleccionarUsuario(<?= (int)$usuario['id'] ?>)">
              <div class="row g-0 align-items-center">
                <div class="col-md-4">
                  <img src="<?= htmlspecialchars($usuario['foto'] ?? '/path/to/default.png') ?>" class="img-fluid rounded-start img-thumbnail" alt="<?= htmlspecialchars($usuario['nombre']) ?>" />
                </div>
                <div class="col-md-8">
                  <div class="card-body">
                    <h6 class="card-title mb-1"><?= ucwords(strtolower(htmlspecialchars($usuario['nombre']))) ?></h6>
                    <p class="card-text mb-1"><?= ucfirst(htmlspecialchars($usuario['descripcion'] ?? '')) ?></p>
                    <small class="text-muted">Activo</small>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else : ?>
          <p class="text-muted">No hay chats activos para mostrar.</p>
        <?php endif; ?>
      </div>

      <div class="col-md-8" id="chat-area">
        <h5>Chat</h5>
        <div id="mensajes" style="height: 70vh; overflow-y: auto; border: 1px solid #ddd; padding: 1rem; border-radius: 8px; background: #f8f9fa;">
          <p class="text-muted">Selecciona un usuario para empezar a chatear.</p>
        </div>
        <form id="form-chat" class="mt-3" style="display:none;">
          <div class="input-group">
            <input type="text" id="mensaje-input" class="form-control" placeholder="Escribe un mensaje..." autocomplete="off" />
            <button type="submit" class="btn btn-primary">Enviar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    window.usuarioActual = {
      id: "<?= (int)$_SESSION['id'] ?>",
      rol: "<?= (int)$_SESSION['id_rol'] ?>",
      nombre: "<?= addslashes($_SESSION['nombre']) ?>"
    };
  </script>

  <script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/11.10.0/firebase-app.js";
    import { getDatabase } from "https://www.gstatic.com/firebasejs/11.10.0/firebase-database.js";

    const firebaseConfig = {
      apiKey: "AIzaSyANqVJvYR4AzFR4XM9qY2DNi8pv3VFmLF0",
      authDomain: "shakti-b4ace.firebaseapp.com",
      projectId: "shakti-b4ace",
      storageBucket: "shakti-b4ace.appspot.com",
      messagingSenderId: "346097573264",
      appId: "1:346097573264:web:fbd683dd475f8d3d8aa715",
      databaseURL: "https://shakti-b4ace-default-rtdb.firebaseio.com"
    };

    const app = initializeApp(firebaseConfig);
    const db = getDatabase(app);
    window.firebaseApp = app;
    window.firebaseDB = db;
  </script>

  <script type="module" src="/Shakti/assets/chat.js"></script>

  <script>
    function seleccionarUsuario(id) {
      if (window.seleccionarEspecialista) {
        window.seleccionarEspecialista(id);
      }
    }

    // 🧠 Autocarga primer chat si es especialista
    <?php if ($rolUsuario == 2 && !empty($usuariosChat)) : ?>
    window.addEventListener('DOMContentLoaded', () => {
      seleccionarUsuario(<?= (int)$usuariosChat[0]['id'] ?>);
    });
    <?php endif; ?>
  </script>
</body>
</html>
