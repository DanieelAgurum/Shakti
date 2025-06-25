<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Iniciar Sesión - Shakti</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

  <style>
    /* Colores */
    :root {
      --morado-base: #5a2a83;
      --fondo-suave: #fefcf7; /* Fondo claro, tipo crema */
    }

    body {
      background-color: #f8f0ff;
    }

    /* Estilo general para el body con flex para footer sticky */
    body.d-flex {
      min-height: 100vh;
      flex-direction: column;
    }

    main.flex-grow-1 {
      flex-grow: 1;
    }

    /* Contenedor del formulario centrado */
    .auth-container {
      max-width: 400px;
      width: 100%;
      padding: 2rem;
      border: 2px solid var(--morado-base); /* Borde morado */
      border-radius: 1rem;
      background-color: var(--fondo-suave); /* Interior claro */
      box-shadow: 0 8px 16px rgba(90, 42, 131, 0.25);
      backdrop-filter: blur(4px);
    }

    .btn-purple {
      background-color: #b288eb;
      color: white;
      border: none;
      transition: background-color 0.3s ease;
    }

    .btn-purple:hover,
    .btn-purple:focus {
      background-color: #8a53d6;
      color: white;
      box-shadow: 0 0 10px rgba(138, 83, 214, 0.7);
      outline: none;
    }
  </style>
</head>

<body class="d-flex flex-column">

  <?php require '../components/usuaria/navbar.php'; ?>

  <main class="flex-grow-1 d-flex align-items-center justify-content-center">
    <div class="auth-container">
      <div class="auth-header text-center mb-4">
        <img src="https://source.unsplash.com/random/80x80?logo" alt="Logo" class="mb-3 rounded-circle" />
        <h1 class="h3 fw-bold text-secondary">Bienvenido</h1>
      </div>

      <form class="needs-validation" novalidate>
        <div class="mb-3 position-relative">
          <label for="email" class="form-label">Correo electrónico</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
            <input type="email" class="form-control" id="email" placeholder="correo@dominio.com" required />
            <div class="invalid-feedback">Por favor, ingresa un correo válido.</div>
          </div>
        </div>

        <div class="mb-4 position-relative">
          <label for="password" class="form-label">Contraseña</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
            <input type="password" class="form-control" id="password" placeholder="••••••••" required />
            <div class="invalid-feedback">La contraseña es obligatoria.</div>
          </div>
        </div>

        <button type="submit" class="btn btn-purple w-100 shadow-sm fw-semibold">Ingresar</button>
      </form>

      <div class="auth-footer mt-4 text-center">
        <a href="#" class="d-block mb-2 text-decoration-none">¿Olvidaste tu contraseña?</a>
        <a href="registro.php" class="text-decoration-none">¿No tienes una cuenta? Regístrate</a>
      </div>
    </div>
  </main>

  <?php include '../components/usuaria/footer.php'; ?>

  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Bootstrap validation script
    (() => {
      'use strict';
      const forms = document.querySelectorAll('.needs-validation');
      Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
          if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
          }
          form.classList.add('was-validated');
        }, false);
      });
    })();
  </script>

</body>

</html>
