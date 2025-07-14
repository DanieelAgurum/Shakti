<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/controlador/EspecialistaControlador.php';

$rolUsuario = $_SESSION['id_rol'];
$idUsuario = $_SESSION['id'];

$especialistaControlador = new EspecialistaControlador();

if ($rolUsuario == 2) {
    $usuariosChat = []; // Se llena desde Firebase dinámicamente
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
  <link rel="stylesheet" href="/Shakti/css/chat.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2 -->
</head>

<body>
  <div class="container-fluid mt-4">
    <div class="row">
      <div class="col-md-4" id="lista-especialistas">
        <h5><?= htmlspecialchars($tituloLista) ?></h5>

        <?php if ($rolUsuario != 2 && !empty($usuariosChat)) : ?>
          <?php foreach ($usuariosChat as $usuario) : ?>
            <div class="card mb-3 card-chat-item" onclick="seleccionarUsuario(<?= (int)$usuario['id'] ?>)">
              <div class="row g-0 h-100 align-items-center">
                <div class="col-4 d-flex align-items-center justify-content-center">
                  <img 
                    src="/Shakti/verFoto.php?id=<?= (int)$usuario['id'] ?>" 
                    class="img-thumbnail perfil-img rounded-circle"
                    alt="<?= htmlspecialchars($usuario['nombre']) ?>" 
                    onerror="this.onerror=null;this.src='/Shakti/assets/img/default.png';"
                    style="width: 80px; height: 80px; object-fit: cover;"
                  />
                </div>
                <div class="col-8">
                  <div class="card-body py-2">
                    <h6 class="card-title mb-1"><?= ucwords(strtolower(htmlspecialchars($usuario['nombre']))) ?></h6>
                    <p class="card-text mb-1 text-truncate"><?= ucfirst(htmlspecialchars($usuario['descripcion'] ?? '')) ?></p>
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
  </script>
</body>
</html>
