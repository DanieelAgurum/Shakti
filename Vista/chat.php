<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Controlador/especialistaControlador.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/obtenerLink/obtenerLink.php';

$urlBase = getBaseUrl();

if (!isset($_SESSION['id_rol']) || !isset($_SESSION['correo'])) {
  header("Location: {$urlBase}index.php");
  exit;
}

$rolUsuario = $_SESSION['id_rol'];
$idUsuario = $_SESSION['id'];

if ($rolUsuario != 1 && $rolUsuario != 2) {
  header("Location: {$urlBase}");
  exit;
}

if ($rolUsuario == 3) {
  header("Location: {$urlBase}Vista/admin");
  exit;
}

$especialistaControlador = new EspecialistaControlador();
$tituloLista = isset($tituloLista) ? $tituloLista : 'Conversaciones';
$rolUsuario = $_SESSION['id_rol'];
$idUsuario = $_SESSION['id'];

$especialistaControlador = new EspecialistaControlador();

if ($rolUsuario == 2) {
  $usuariosChat = [];
} else {
  $usuariosChat = $especialistaControlador->listarEspecialistas();
}

include $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/usuaria/navbar.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <title>Chat - <?= htmlspecialchars($tituloLista) ?></title>
  <link rel="stylesheet" href="<?= $urlBase ?>css/chat.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    .chat-container {
      border: 2px solid #a442b2;
      border-radius: 12px;
      background-color: #fff;
      padding: 1rem;
      box-shadow: 0 0 8px rgba(164, 66, 178, 0.1);
      height: 80vh;
      display: flex;
      flex-direction: column;
    }

    #mensajes {
      flex: 1;
      overflow-y: auto;
      margin-bottom: 1rem;
    }

    #form-chat {
      display: none;
    }

    .card-chat-item {
      border: 1px solid #a442b2;
      transition: border 0.3s, box-shadow 0.3s;
    }

    .card-chat-item.active {
      border: 2px solid #a442b2;
      box-shadow: 0 0 5px rgba(164, 66, 178, 0.4);
    }

    .perfil-img {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 50%;
      border: 2px solid #a442b2;
    }

    @media (max-width: 768px) {

      #lista-especialistas,
      #chat-area {
        margin-bottom: 2rem;
      }

      .perfil-img {
        width: 60px;
        height: 60px;
      }

      .chat-container {
        height: 60vh;
      }
    }
  </style>
</head>

<body>
  <div class="container-fluid mt-4">
    <div class="row flex-column flex-md-row">
      <!-- Lista de especialistas/usuarias -->
      <div class="col-12 col-md-4 mb-3" id="lista-especialistas">
        <h5><?= htmlspecialchars($tituloLista) ?></h5>

        <?php if ($rolUsuario != 2 && !empty($usuariosChat)) : ?>
          <?php foreach ($usuariosChat as $usuario) : ?>
            <div class="card mb-3 card-chat-item"
              id="usuario-<?= (int)$usuario['id'] ?>"
              onclick="seleccionarUsuario(<?= (int)$usuario['id'] ?>)">
              <div class="row g-0 h-100 align-items-center">
                <div class="col-4 d-flex align-items-center justify-content-center">
                  <img
                    src="/verFoto.php?id=<?= (int)$usuario['id'] ?>"
                    class="img-thumbnail perfil-img"
                    alt="<?= htmlspecialchars($usuario['nombre']) ?>"
                    onerror="this.onerror=null;this.src='/assets/img/default.png';" />
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

      <!-- Área de chat -->
      <div class="col-12 col-md-8" id="chat-area">
        <h5>Chat</h5>
        <div class="chat-container">
          <!-- Mensajes -->
          <div id="mensajes">
            <p class="text-muted">Selecciona un usuario para empezar a chatear.</p>
          </div>

          <!-- Formulario de mensaje -->
          <form id="form-chat">
            <div class="input-group">
              <input type="text" id="mensaje-input" class="form-control" placeholder="Escribe un mensaje..." autocomplete="off" />
              <button type="submit" class="btn btn-primary">Enviar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script>
    window.usuarioActual = {
      id: "<?= (int)$_SESSION['id'] ?>",
      rol: "<?= (int)$_SESSION['id_rol'] ?>",
      nombre: "<?= addslashes($_SESSION['nombre']) ?>"
    };

    function seleccionarUsuario(id) {
      if (window.seleccionarEspecialista) {
        window.seleccionarEspecialista(id);
      }

      // Remover clase activa de otros usuarios
      document.querySelectorAll('.card-chat-item').forEach(item => {
        item.classList.remove('active');
      });

      // Agregar clase activa al seleccionado
      const seleccionado = document.getElementById(`usuario-${id}`);
      if (seleccionado) {
        seleccionado.classList.add('active');
      }

      // Mostrar formulario y limpiar el área de mensajes
      document.getElementById('form-chat').style.display = 'block';
      document.getElementById('mensajes').innerHTML = "<p class='text-muted'>Cargando mensajes...</p>";
    }
  </script>

  <script type="module" src="<?= $urlBase ?>peticiones(js)/firebaseInit.js"></script>
  <script type="module" src="<?= $urlBase ?>assets/chat.js"></script>
</body>

</html>