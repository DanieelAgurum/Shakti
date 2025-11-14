<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

include_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/obtenerLink/obtenerLink.php';
$urlBase = getBaseUrl();

if (isset($_SESSION['id_rol'])) {
  switch ($_SESSION['id_rol']) {
    case 1:
      header("Location: " . $urlBase . "vista/usuaria/perfil");
      exit;
    case 2:
      header("Location: " . $urlBase . "vista/especialista/perfil");
      exit;
    case 3:
      header("Location: " . $urlBase . "vista/admin/");
      exit;
  }
}

$message = $_GET['message'] ?? '';
$status = $_GET['status'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Iniciar sesión - Shakti</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="d-flex flex-column min-vh-100 bg-light">

  <!-- Navbar -->
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/usuaria/navbar.php'; ?>

  <!-- Contenido principal -->
  <main class="flex-grow-1 d-flex justify-content-center align-items-center mt-3 mb-3">
    <div class="card p-4 shadow-sm border-0" style="max-width:420px; width:100%; border-radius:1.5rem; background:#f9f9f9;">
      <h3 class="text-center mb-4" style="color:#5a2a83;">Iniciar sesión</h3>

      <form id="formLogin" action="<?= $urlBase ?>Controlador/loginCtrl.php" method="POST" novalidate>
        <input type="hidden" name="opcion" value="1">

        <!-- Correo -->
        <div class="mb-3">
          <label class="form-label">Correo electrónico</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
            <input type="email" name="correo" class="form-control" placeholder="Ingresa tu correo electrónico" required>
          </div>
          <small class="error" id="errorCorreo"></small>
        </div>

        <!-- Contraseña -->
        <div class="mb-3">
          <label class="form-label">Contraseña</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
            <input type="password" name="contraseña" id="contraseña" class="form-control" placeholder="Ingresa tu contraseña" required>
            <button type="button" class="btn btn-outline-secondary" id="togglePassword" style="border-radius:0 50px 50px 0;">
              <i class="bi bi-eye-fill"></i>
            </button>
          </div>
          <div class="text-end mt-1">
            <a href="#"
              data-bs-toggle="modal"
              data-bs-target="#exampleModal"
              style="text-decoration: none; color: #6f42c1; font-weight: 500;"
              onmouseover="this.style.textDecoration='underline'"
              onmouseout="this.style.textDecoration='none'">
              ¿Olvidaste tu contraseña?
            </a>
          </div>
          <small class="error" id="errorContraseña"></small>
        </div>

        <button type="submit" class="btn btn-purple w-100 mb-3">Ingresar</button>
      </form>

      <!-- Botón Google -->
      <a href="#" id="btnGoogleLogin" class="btn btn-google w-100 mb-3">
        <i class="bi bi-google"></i> Iniciar sesión con Google
      </a>

      <!-- Link a registro -->
      <div class="text-center">
        <a href="<?= $urlBase ?>Vista/registro.php">¿No tienes cuenta? Crea una cuenta</a>
      </div>
    </div>


    <div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered custom-config-modal">
        <div class="modal-content">
          <div class="modal-header border-0">
            <h5 class="modal-title text-secondary">¿Quieres recuperar tu contraseña?</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>

          <div class="modal-body">
            <form id="formRecuperar" class="d-grid gap-3">
              <input type="email"
                name="correo"
                id="recuperarEmail"
                class="form-control modal-input-email"
                placeholder="Ingresar correo electrónico"
                required>
              <button type="submit"
                id="btnEnviarRecuperacion"
                class="btn btn-purple w-100">
                Enviar
              </button>
            </form>
          </div>

        </div>
      </div>
    </div>

  </main>

  <!-- SweetAlert (mensajes de error o éxito) -->
  <script>
    <?php if (!empty($message)) : ?>
      Swal.fire({
        icon: "<?= $status === 'error' ? 'error' : 'success' ?>",
        title: "<?= $status === 'error' ? 'Error' : 'Éxito' ?>",
        text: <?= json_encode(urldecode($message)) ?>,
        timer: 10000,
        timerProgressBar: true,
        showConfirmButton: true,
        confirmButtonText: "Aceptar",
        confirmButtonColor: "#4682B4"
      });

      // 🔹 Limpia la URL después de mostrar el mensaje
      if (window.history.replaceState) {
        const cleanURL = window.location.protocol + "//" + window.location.host + window.location.pathname;
        window.history.replaceState(null, '', cleanURL);
      }
    <?php endif; ?>
  </script>

  <!-- Footer -->
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/usuaria/footer.php'; ?>

  <!-- Scripts -->
  <script>
    const urlBase = "<?= $urlBase ?>";
  </script>
  <script src="<?= $urlBase ?>peticiones(js)/auth.js"></script>
  <script src="<?= $urlBase ?>peticiones(js)/deshabilitarModalCambiarContra.js"></script>
  <script src="<?= $urlBase ?>validacionRegistro/validacion.js"></script>
  <script src="<?= $urlBase ?>peticiones(js)/mandarMetricas.js.php?vista=<?= urlencode(basename($_SERVER['PHP_SELF'])) ?>"></script>

  <!-- Estilos -->
  <style>
    /* Inputs */
    input.form-control {
      border-radius: 50px;
      border: none;
      padding: 0.7rem 1rem;
      background: #f0f0f0;
      box-shadow: inset 5px 5px 10px rgba(0, 0, 0, 0.05),
        inset -5px -5px 10px rgba(255, 255, 255, 0.7);
      transition: all 0.3s ease;
    }

    input.form-control:focus {
      outline: none;
      box-shadow: inset 5px 5px 12px rgba(0, 0, 0, 0.1),
        inset -5px -5px 12px rgba(255, 255, 255, 0.8);
    }

    /* Botón principal */
    .btn-purple {
      border-radius: 50px;
      background: #4682B4;
      color: #fff;
      font-weight: 600;
      padding: 0.7rem 1.5rem;
      transition: all 0.3s ease;
    }

    .btn-purple:hover {
      transform: translateY(-2px);
      box-shadow: 0px 0px 10px rgba(2, 150, 236, 0.4);
    }

    /* Botón Google */
    .btn-google {
      border-radius: 50px;
      background: #db4437;
      color: #fff;
      font-weight: 600;
      padding: 0.7rem 1.5rem;
      text-align: center;
      transition: all 0.3s ease;
    }

    .btn-google:hover {
      background: #c1351d;
      color: #fff;
      transform: translateY(-2px);
    }

    /* Etiquetas */
    .form-label {
      font-weight: 500;
      color: #4682B4;
    }

    /* Iconos */
    .input-group-text {
      background: #f0f0f0;
      border-radius: 50px 0 0 50px;
      border: none;
      color: #4682B4;
    }

    /* Errores */
    .error {
      color: #dc3545;
      font-size: 0.85rem;
      margin-top: 0.2rem;
      display: block;
    }

    .modal-input-email {
      border-radius: 0.5rem !important;
      box-shadow: none !important;
      border: 1px solid #ccc !important;
    }

    .modal-input-email:focus {
      box-shadow: 0 0 0 0.25rem rgba(70, 130, 180, 0.25) !important;
    }
  </style>
</body>

</html>