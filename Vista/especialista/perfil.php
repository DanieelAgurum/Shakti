<?php
include '../../Modelo/completarPerfil.php';
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (empty($_SESSION['correo']) || ($_SESSION['id_rol'] ?? null) != 2) {
  header("Location: ../../index.php");
  exit;
}

$idUsuaria = $_SESSION['id'] ?? null;

if (!$idUsuaria) {
  header("Location: ../../index.php");
  exit;
}

$cp = new Completar();
$documentos = $cp->mostrarDocumentos($idUsuaria);
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Perfil - Shakti</title>
  <link rel="stylesheet" href="../../css/estilos.css" />
  <link rel="stylesheet" href="../../css/registro.css" />
  <link rel="stylesheet" href="../../css/perfil.css" />
  <link rel="stylesheet" href="../../css/footer.css" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <?php include '../../components/especialista/navbar.php'; ?>
</head>

<body>
  <div class="container mt-5">
    <div class="main-body">
      <div class="row gutters-sm">
        <!-- Panel izquierdo -->
        <div class="col-md-4 mb-3">
          <div class="card">
            <div class="card-body">
              <div class="d-flex flex-column align-items-center text-center">
                <img src="https://cdn1.iconfinder.com/data/icons/avatar-3/512/Secretary-512.png" alt="Especialista" class="rounded-circle" width="150">
                <div class="mt-3">
                  <h4><?php echo ucwords(strtolower($_SESSION['nombre'] ?? '')); ?></h4>
                  <p class="text-secondary mb-1"><?php echo ucwords(strtolower($_SESSION['nombre_rol'] ?? '')); ?></p>
                  <p class="text-muted font-size-sm"><?php echo ucwords(strtolower($_SESSION['direccion'] ?? '')); ?></p>
                  <button class="btn btn-outline-primary">Mensaje</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Panel derecho -->
        <div class="col-md-8">
          <div class="card mb-3">
            <div class="card-body">
              <?php
              $fields = [
                'Nombre completo' => trim(($_SESSION['nombre'] ?? '') . ' ' . ($_SESSION['apellidos'] ?? '')),
                'Correo electrónico' => $_SESSION['correo'] ?? '',
                'Teléfono' => $_SESSION['telefono'] ?? '',
                'Dirección' => $_SESSION['direccion'] ?? '',
                'Estado de cuenta' => 'Activa'
              ];
              foreach ($fields as $label => $value): ?>
                <div class="row">
                  <div class="col-sm-3">
                    <h6 class="mb-0"><?php echo ucwords(strtolower($label)); ?></h6>
                  </div>
                  <div class="col-sm-9 text-secondary">
                    <?php echo ucwords(strtolower($value)); ?>
                  </div>
                </div>
                <hr>
              <?php endforeach; ?>

              <div class="d-flex justify-content-end gap-2 mb-3">
                <button type="button" class="btn btn-primary position-relative" data-bs-toggle="modal" data-bs-target="#completarPerfilModal">
                  Completar perfil
                </button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editarPerfilModal">
                  <i class="fa-solid fa-circle-plus"></i> Editar perfil
                </button>
              </div>
            </div>
          </div>

          <div class="row gutters-sm mb-3">
            <div class="col-sm-12 mb-6">
              <div class="card h-100">
                <div class="card-body">
                  <h6 class="d-flex align-items-center mb-3">
                    <i class="material-icons text-info mr-2">Documentos</i>
                  </h6>
                  <small>
                    <ul class="mb-0">
                      <?php if (!empty($documentos['id_oficial'])): ?>
                        <li>Identificación oficial</li>
                      <?php endif; ?>
                      <?php if (!empty($documentos['documento1'])): ?>
                        <li>Título profesional</li>
                      <?php endif; ?>
                      <?php if (!empty($documentos['documento2'])): ?>
                        <li>Cédula profesional o matrícula</li>
                      <?php endif; ?>
                      <?php if (!empty($documentos['documento3'])): ?>
                        <li>Certificados de diplomados o posgrados</li>
                      <?php endif; ?>
                      <?php if (!empty($documentos['documento4'])): ?>
                        <li>Constancias de práctica o experiencia laboral</li>
                      <?php endif; ?>
                    </ul>
                  </small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php if (isset($_GET['status']) && isset($_GET['message'])): ?>
    <script>
      Swal.fire({
        icon: '<?= $_GET['status'] === 'success' ? 'success' : 'error' ?>',
        title: '<?= $_GET['status'] === 'success' ? '¡Todo listo!' : 'Ups...' ?>',
        text: '<?= htmlspecialchars(urldecode($_GET["message"]), ENT_QUOTES, "UTF-8") ?>',
        confirmButtonText: 'Aceptar'
      });
    </script>
  <?php endif; ?>

  <?php include '../modales/perfil.php'; ?>
  <?php include '../../components/usuaria/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" integrity="sha384-7qAoOXltbVP82dhxHAUje59V5r2YsVfBafyUDxEdApLPmcdhBPg1DKg1ERo0BZlK" crossorigin="anonymous"></script>

</body>

</html>