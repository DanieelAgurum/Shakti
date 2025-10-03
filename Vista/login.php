<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/obtenerLink/obtenerLink.php';
$urlBase = getBaseUrl();

// Redirigir si ya está logueado
if (isset($_SESSION['id_rol'])) {
    switch ($_SESSION['id_rol']) {
        case 1: header("Location: " . $urlBase . "usuaria/perfil.php"); exit;
        case 2: header("Location: " . $urlBase . "especialista/perfil.php"); exit;
        case 3: header("Location: " . $urlBase . "admin/"); exit;
    }
}

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
  <main class="flex-grow-1 d-flex justify-content-center align-items-center">
    <div class="card p-4" style="max-width:420px; width:100%; border-radius:1.5rem; background:#f9f9f9; box-shadow:8px 8px 20px rgba(0,0,0,0.05), -8px -8px 20px rgba(255,255,255,0.8);">
      <h3 class="text-center mb-4" style="color:#5a2a83;">Iniciar sesión</h3>

      <form id="formLogin" action="<?= $urlBase ?>Controlador/loginCtrl.php" method="POST" novalidate>
        <input type="hidden" name="opcion" value="1">

        <!-- Correo -->
        <div class="mb-3 position-relative">
          <label class="form-label">Correo electrónico</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
            <input type="email" name="correo" class="form-control" placeholder="correo@ejemplo.com" required>
          </div>
          <small class="error" id="errorCorreo"></small>
        </div>

        <!-- Contraseña -->
        <div class="mb-3 position-relative">
          <label class="form-label">Contraseña</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
            <input type="password" name="contraseña" id="contraseña" class="form-control" placeholder="********" required>
            <button type="button" class="btn btn-outline-secondary" id="togglePassword" style="border-radius:0 50px 50px 0;">
              <i class="bi bi-eye-fill"></i>
            </button>
          </div>
          <small class="error" id="errorContraseña"></small>
        </div>

        <button type="submit" class="btn btn-purple w-100 mb-2">Ingresar</button>
      </form>

      <hr>

      <!-- Botón Google -->
      <a href="#" id="btnGoogleLogin" class="btn btn-google w-100 mb-3">
        <i class="bi bi-google"></i> Iniciar sesión con Google
      </a>

      <!-- Link a registro -->
      <div class="text-center">
        <a href="<?= $urlBase ?>Vista/registro.php">¿No tienes cuenta? Crea una cuenta</a>
      </div>
    </div>
  </main>
  <!-- Footer -->
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/usuaria/footer.php'; ?>
  <!-- Scripts -->
   <script>const urlBase = "<?= $urlBase ?>";</script>
  <script src="<?= $urlBase ?>peticiones(js)/auth.js"></script>
  <script src="<?= $urlBase ?>validacionRegistro/validacion.js"></script>
  <script src="<?= $urlBase ?>peticiones(js)/mandarMetricas.js.php?vista=<?= urlencode(basename($_SERVER['PHP_SELF'])) ?>"></script>
  <style>
    /* Contenedor */
    .auth-container, .card {
      background: #f9f9f9;
      border-radius: 1.5rem;
      box-shadow: 8px 8px 20px rgba(0,0,0,0.05),
                  -8px -8px 20px rgba(255,255,255,0.8);
    }

    /* Inputs estilo inset */
    input.form-control {
      border-radius: 50px;
      border: none;
      padding: 0.7rem 1rem;
      background: #f0f0f0;
      box-shadow: inset 5px 5px 10px rgba(0,0,0,0.05),
                  inset -5px -5px 10px rgba(255,255,255,0.7);
      transition: all 0.3s ease;
    }
    input.form-control:focus {
      outline: none;
      box-shadow: inset 5px 5px 12px rgba(0,0,0,0.1),
                  inset -5px -5px 12px rgba(255,255,255,0.8);
    }

    /* Botón morado */
    .btn-purple {
      border-radius: 50px;
      background: #5a2a83;
      color: #fff;
      font-weight: 600;
      padding: 0.7rem 1.5rem;
      box-shadow: 5px 5px 15px rgba(0,0,0,0.1),
                  -5px -5px 15px rgba(255,255,255,0.2);
      transition: all 0.3s ease;
    }
    .btn-purple:hover {
      transform: translateY(-2px);
      box-shadow: 5px 5px 20px rgba(0,0,0,0.15),
                  -5px -5px 20px rgba(255,255,255,0.25);
    }

    /* Botón Google */
    .btn-google {
      border-radius: 50px;
      background: #db4437;
      color: #fff;
      font-weight: 600;
      padding: 0.7rem 1.5rem;
      box-shadow: 5px 5px 15px rgba(0,0,0,0.1),
                  -5px -5px 15px rgba(255,255,255,0.2);
      transition: all 0.3s ease;
      text-align:center;
    }
    .btn-google:hover {
      transform: translateY(-2px);
      box-shadow: 5px 5px 20px rgba(0,0,0,0.15),
                  -5px -5px 20px rgba(255,255,255,0.25);
    }

    /* Labels */
    .form-label {
      font-weight: 500;
      color: #5a2a83;
    }

    /* Iconos input */
    .input-group-text {
      background: #f0f0f0;
      border-radius: 50px 0 0 50px;
      border: none;
      color: #5a2a83;
    }

    /* Errores */
    .error {
      color: #dc3545;
      font-size: 0.85rem;
      margin-top: 0.2rem;
      display: block;
    }

    /* Responsive */
    @media (max-width: 576px) {
      .auth-container {
        padding: 1.5rem;
      }
    }
  </style>
</body>
</html>
