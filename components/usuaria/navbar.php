<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/obtenerLink/obtenerLink.php';
$urlBase = getBaseUrl();

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (isset($_SESSION['correo']) && $_SESSION['id_rol'] == 3) {
  header("Location: {$urlBase}Vista/admin/index.php");
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
  <link rel="stylesheet" href="<?php echo $urlBase ?>css/estilos.css" />

  <!-- Scripts Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
</head>

<body>
  <nav class="navbar navbar-expand-lg custom-navbar fixed-top shadow-sm bg-white">
    <div class="container">
      <a class="navbar-brand" href="<?= $urlBase ?>index.php">SHAKTI</a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarEspecialista"
        aria-controls="navbarEspecialista" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarEspecialista">
        <ul class="navbar-nav ms-auto align-items-center">
          <li class="nav-item">
            <a class="nav-link" href="<?= $urlBase ?>Vista/usuaria/foro.php">Foro</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="<?= $urlBase ?>Vista/contacto.php">Contáctanos</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="<?= $urlBase ?>Vista/<?php
                                                            switch ($_SESSION['id_rol'] ?? 0) {
                                                              case 1:
                                                              case 2:
                                                                echo 'usuaria/alzalaVoz.php';
                                                                break;
                                                              case 3:
                                                                echo 'admin/';
                                                                break;
                                                              default:
                                                                echo 'login.php';
                                                                break;
                                                            }
                                                            ?>">Alza la voz</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="<?= $urlBase ?>Vista/<?php
                                                            switch ($_SESSION['id_rol'] ?? 0) {
                                                              case 1:
                                                              case 2:
                                                                echo 'usuaria/publicaciones.php';
                                                                break;
                                                              case 3:
                                                                echo 'admin/';
                                                                break;
                                                              default:
                                                                echo 'login.php';
                                                                break;
                                                            }
                                                            ?>">Publicaciones</a>
          </li>
          
          <?php if (isset($_SESSION['id_rol']) && $_SESSION['id_rol'] == 1): ?>
            <li class="nav-item">
              <a class="nav-link" href="<?= $urlBase ?>Vista/usuaria/especialistas.php">Especialistas</a>
            </li>
          <?php endif; ?>


          <!-- Menú desplegable de usuario -->
          <li class="nav-item dropdown ms-3">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-person-circle me-1"></i>
              <?= isset($_SESSION['nickname']) ? ucwords(strtolower($_SESSION['nickname'])) : '' ?>
            </a>

            <ul class="dropdown-menu dropdown-menu-end">
              <?php if (isset($_SESSION['correo'])): ?>
                <li>
                  <a class="dropdown-item" href="<?= $urlBase ?>Vista/<?php
                                                                      switch ($_SESSION['id_rol'] ?? 0) {
                                                                        case 1:
                                                                          echo 'usuaria/perfil.php';
                                                                          break;
                                                                        case 2:
                                                                          echo 'especialista/perfil.php';
                                                                          break;
                                                                        default:
                                                                          echo 'login.php';
                                                                          break;
                                                                      }
                                                                      ?>">
                    Mi perfil <i class="bi bi-person-circle me-1"></i>
                  </a>
                </li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="#">Notificaciones <i class="bi bi-bell-fill"></i></a></li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="#">Configuración <i class="bi bi-gear-fill"></i></a></li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li>
                  <form action="<?= $urlBase ?>Controlador/loginCtrl.php" method="post" class="m-0 p-0">
                    <input type="hidden" name="opcion" value="2">
                    <button type="submit" class="dropdown-item text-danger">
                      Cerrar sesión <i class="bi bi-door-open-fill"></i>
                    </button>
                  </form>
                </li>
              <?php else: ?>
                <li>
                  <a class="dropdown-item" href="<?= $urlBase ?>Vista/login.php">
                    Iniciar sesión <i class="bi bi-box-arrow-in-right"></i>
                  </a>
                </li>
              <?php endif; ?>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</body>

</html>