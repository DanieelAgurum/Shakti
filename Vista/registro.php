<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/obtenerLink/obtenerLink.php';
$urlBase = getBaseUrl();

// Redirigir si ya está logueado
if (isset($_SESSION['id_rol'])) {
    switch ($_SESSION['id_rol']) {
        case 1: header("Location: " . $urlBase . "Vista/usuaria/perfil.php"); exit;
        case 2: header("Location: " . $urlBase . "Vista/especialista/perfil.php"); exit;
        case 3: header("Location: " . $urlBase . "Vista/admin/"); exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Registro - Shakti</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="d-flex flex-column min-vh-100 bg-light">

  <!-- Navbar -->
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/usuaria/navbar.php'; ?>

  <!-- Contenedor registro -->
  <main class="flex-grow-1 d-flex justify-content-center align-items-center py-5">
    <div class="auth-container p-4" style="max-width:480px; width:100%;">
      <div class="auth-header text-center mb-4">
        <h1 class="h3 fw-bold text-secondary">Crear cuenta</h1>
      </div>

      <form class="auth-form" id="registroForm" action="<?= $urlBase ?>Controlador/UsuariasControlador.php" method="post" novalidate>

        <!-- Nombre -->
        <div class="mb-3 position-relative">
          <label for="nombre" class="form-label">Nombre</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
            <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Ingrese su(s) nombre(s)" required>
          </div>
          <small class="error" id="errorNombre"></small>
        </div>

        <!-- Apellidos -->
        <div class="mb-3 position-relative">
          <label for="apellidos" class="form-label">Apellidos</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person-badge-fill"></i></span>
            <input type="text" class="form-control" name="apellidos" id="apellidos" placeholder="Ingrese sus apellidos" required>
          </div>
          <small class="error" id="errorApellidos"></small>
        </div>

        <!-- Nickname -->
        <div class="mb-3 position-relative">
          <label for="nickname" class="form-label">Nombre de usuario</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-card-text"></i></span>
            <input type="text" class="form-control" name="nickname" id="nickname" placeholder="Ingrese su nombre de usuario" required>
          </div>
          <small class="error" id="errorNickname"></small>
        </div>

        <!-- Correo -->
        <div class="mb-3 position-relative">
          <label for="correo" class="form-label">Correo electrónico</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
            <input type="email" class="form-control" name="correo" id="correo" placeholder="Ingrese su correo electrónico" required>
          </div>
          <small class="error" id="errorCorreo"></small>
        </div>

        <!-- Contraseña -->
        <div class="mb-3 position-relative">
          <label for="contraseña" class="form-label">Contraseña</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
            <input type="password" class="form-control" name="contraseña" id="contraseña" placeholder="Ingrese su contraseña" required>
          </div>
          <small class="error" id="errorContraseña"></small>
        </div>

        <!-- Confirmar contraseña -->
        <div class="mb-3 position-relative">
          <label for="conContraseña" class="form-label">Confirmar contraseña</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
            <input type="password" class="form-control" name="conContraseña" id="conContraseña" placeholder="Confirme su contraseña" required>
          </div>
          <small class="error" id="errorConContraseña"></small>
        </div>

        <!-- Fecha de nacimiento -->
        <div class="mb-3 position-relative">
          <label for="fecha_nac" class="form-label">Fecha de nacimiento</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-calendar-fill"></i></span>
            <input type="date" class="form-control" name="fecha_nac" id="fecha_nac" required>
          </div>
          <small class="error" id="errorFecha_nac"></small>
        </div>

        <!-- Checkbox de términos y condiciones -->
        <div class="mb-3 form-check">
          <input class="form-check-input" type="checkbox" id="terminosCheck" required>
          <label class="form-check-label" for="terminosCheck">Acepto términos y condiciones</label>
          <small class="error" id="errorTerminos"></small>
        </div>

        <!-- Checkbox para especialista -->
        <div class="mb-3 form-check">
          <input class="form-check-input" type="checkbox" id="especialistaCheck" onchange="actualizarRol()">
          <label class="form-check-label" for="especialistaCheck">Registrar como especialista</label>
        </div>

        <!-- Campos ocultos -->
        <input type="hidden" name="rol" id="rol" value="1">
        <input type="hidden" name="opcion" value="1">

        <!-- Botón enviar -->
        <div class="d-grid">
          <button type="submit" class="btn btn-purple w-100 shadow-sm fw-semibold">Crear cuenta</button>
        </div>
      </form>

      <div class="auth-footer text-center mt-3">
        <a href="<?= $urlBase ?>Vista/login.php">¿Ya tienes una cuenta? Inicia sesión</a>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/usuaria/footer.php'; ?>

  <!-- Scripts -->
  <script src="<?= $urlBase ?>validacionRegistro/validacion.js"></script>
  <script src="<?= $urlBase ?>peticiones(js)/mandarMetricas.js.php?vista=<?= urlencode(basename($_SERVER['PHP_SELF'])) ?>"></script>

  <style>
    /* Contenedor */
    .auth-container {
      background: #f9f9f9;
      border-radius: 1.5rem;
      box-shadow: 8px 8px 20px rgba(0,0,0,0.05),
                  -8px -8px 20px rgba(255,255,255,0.8);
      padding: 2rem;
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

    /* Responsive */
    @media (max-width: 576px) {
      .auth-container {
        padding: 1.5rem;
      }
    }
  </style>
</body>
</html>
