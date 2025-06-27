<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['id_rol']) || $_SESSION['id_rol'] != 2) {
    header("Location: /Shakti/index.php");
    exit;
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

<nav class="navbar navbar-expand-lg custom-navbar fixed-top shadow-sm bg-white">
  <div class="container">
    <a class="navbar-brand" href="/Shakti/index.php">SHAKTI - Especialista</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarEspecialista"
      aria-controls="navbarEspecialista" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarEspecialista">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item"><a class="nav-link" href="/Shakti/vista/especialista/inicioes.php">Inicio</a></li>
        <li class="nav-item"><a class="nav-link" href="/Shakti/especialista/casos.php">Casos</a></li>
        <li class="nav-item"><a class="nav-link" href="/Shakti/especialista/perfil.php">Mi perfil</a></li>

        <li class="nav-item dropdown ms-3">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle me-1"></i>
            <?php echo htmlspecialchars($_SESSION['nombre_rol'] . " " . $_SESSION['nombre']); ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="/Shakti/especialista/perfil.php">Mi perfil</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <form action="/Shakti/Controlador/loginCtrl.php" method="post" class="m-0 p-0">
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
