<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/obtenerLink/obtenerLink.php';
$urlBase = getBaseUrl();

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Panel Administrador - Shakti</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

  <!-- CSS personalizado -->
  <link rel="stylesheet" href="/Shakti/css/estilos.css" />
</head>

<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg custom-navbar fixed-top shadow-sm">
    <div class="container">
      <a class="navbar-brand" href="/Shakti/index.php">SHAKTI - Admin</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAdmin"
        aria-controls="navbarAdmin" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarAdmin">
        <ul class="navbar-nav ms-auto align-items-center">
          <li class="nav-item">
            <a class="nav-link" href="/Shakti/vista/admin/index.php">Inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/Shakti/admin/contenido.php">Contenido</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/Shakti/admin/especialistas.php">Especialistas</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/Shakti/admin/dashboard.php">Dashboard</a>
          </li>

          <!-- Dropdown con nombre y logout -->
          <li class="nav-item dropdown ms-3">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-person-circle me-1"></i>
              <?php echo htmlspecialchars($_SESSION['nombre_rol'] . " " . $_SESSION['nombre']); ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="/Shakti/vista/admin/perfilad.php">Mi perfil</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li>
                <form action="<?php echo $urlBase ?>" method="post" class="m-0 p-0">
                  <input type="hidden" name="opcion" value="2" />
                  <button type="submit" class="dropdown-item text-danger">Cerrar sesión</button>
                </form>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>