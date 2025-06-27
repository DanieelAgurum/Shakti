<?php
include '../../obtenerLink/obtenerLink.php';
ob_start();
$urlBase = getBaseUrl();


if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
if (!isset($_SESSION['id_rol']) || $_SESSION['id_rol'] != 2) {
  header("Location:" . $urlBase);
  exit;
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Panel - Shakti</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

  <!-- CSS personalizado -->
  <link rel="stylesheet" href="/Shakti/css/estilos.css" />

  <!-- Sripts B -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" integrity="sha384-7qAoOXltbVP82dhxHAUje59V5r2YsVfBafyUDxEdApLPmcdhBPg1DKg1ERo0BZlK" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" integrity="sha384-7qAoOXltbVP82dhxHAUje59V5r2YsVfBafyUDxEdApLPmcdhBPg1DKg1ERo0BZlK" crossorigin="anonymous"></script>
</head>

<nav class="navbar navbar-expand-lg custom-navbar fixed-top shadow-sm bg-white">
  <div class="container">
    <a class="navbar-brand" href="<?php echo $urlBase ?>/index.php">SHAKTI <?php ?></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarEspecialista"
      aria-controls="navbarEspecialista" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarEspecialista">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item"><a class="nav-link" href="<?php echo $urlBase ?>/index.php">Inicio</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo $urlBase ?>/Vista/contacto.php">Contactanos</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo $urlBase ?>/Vista/especialista/perfil.php">Mi perfil</a></li>

        <li class="nav-item dropdown ms-3">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle me-1"></i>
            <?php echo isset($_SESSION['nickname']) ? htmlspecialchars($_SESSION['nickname']) : " "; ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="<?php echo $urlBase ?>/Vista/especialista/perfil.php">Mi perfil <i class="bi bi-person-circle me-1"></i></a></li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="#">Notificaciones <i class="bi bi-bell-fill"></i></a></li>
            <li>
              <hr class="dropdown-divider">
            </li>
             <li><a class="dropdown-item" href="#">Configuración  <i class="bi bi-gear-fill"></i></a></li>
            <li>
              <hr class="dropdown-divider">
            </li>
           
            <li>
              <form action="<?php echo $urlBase ?>/Controlador/loginCtrl.php" method="post" class="m-0 p-0">
                <input type="hidden" name="opcion" value="2" />
                <button type="submit" class="dropdown-item text-danger">Cerrar sesión <i class="bi bi-door-open-fill"></i></button>
              </form>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>