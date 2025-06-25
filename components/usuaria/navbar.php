<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

  <style>
    /* Estilo personalizado para navbar */
    .custom-navbar {
      background-color: #d7c1f5; /* lila claro */
    }

    .custom-navbar .nav-link,
    .custom-navbar .navbar-brand {
      color: #4b0082; /* morado oscuro */
      transition: color 0.3s ease;
    }

    .custom-navbar .nav-link:hover,
    .custom-navbar .nav-link:focus,
    .custom-navbar .navbar-brand:hover,
    .custom-navbar .navbar-brand:focus {
      color: white;
      text-decoration: none;
    }

    /* Para que el contenido no quede debajo de la navbar fixed */
    body {
      padding-top: 56px; /* altura típica navbar bootstrap */
    }
  </style>
</head>

<body>

  <nav class="navbar navbar-expand-lg navbar-light shadow-sm custom-navbar fixed-top">
    <div class="container">
      <a class="navbar-brand" href="/Shakti/index.php">SHAKTI</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto align-items-center">
          <li class="nav-item">
            <a class="nav-link" href="/Shakti/index.php">Inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Nosotros</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Servicios</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Contacto</a>
          </li>
          <li class="nav-item ms-3">
            <a class="nav-link fs-4" href="/Shakti/Vista/login.php" title="Iniciar sesión" aria-label="Iniciar sesión">
              <i class="bi bi-person-circle"></i>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Aquí tu contenido principal -->

  <!-- Bootstrap JS Bundle (Popper + Bootstrap JS) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
