<?php
session_start();

// Validar que el usuario esté logueado y sea especialista
if (empty($_SESSION['correo']) || $_SESSION['nombre_rol'] !== 'especialista') {
    header("Location: ../../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Perfil Especialista - Shakti</title>
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
                            <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Especialista" class="rounded-circle" width="150">
                            <div class="mt-3">
                                <h4><?php echo ucwords(strtolower($_SESSION['nombre'] ?? '')) ?></h4>
                                <p class="text-secondary mb-1"><?php echo ucwords(strtolower($_SESSION['nombre_rol'] ?? '')) ?></p>
                                <p class="text-muted font-size-sm"><?php echo ucwords(strtolower($_SESSION['direccion'] ?? '')) ?></p>
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
                            'Nombre completo' => ($_SESSION['nombre'] ?? '') . ' ' . ($_SESSION['apellidos'] ?? ''),
                            'Correo electrónico' => $_SESSION['correo'] ?? '',
                            'Teléfono' => $_SESSION['telefono'] ?? '',
                            'Dirección' => $_SESSION['direccion'] ?? '',
                            'Estado de cuenta' => 'Activa'
                        ];
                        foreach ($fields as $label => $value): ?>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0"><?php echo $label; ?></h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <?php echo htmlspecialchars($value); ?>
                                </div>
                            </div>
                            <hr>
                        <?php endforeach; ?>

                        <div class="d-flex justify-content-end gap-2 mb-3">
                            <button type="button" class="btn btn-primary position-relative" data-bs-toggle="modal" data-bs-target="#completarPerfilModal">
                                Completar perfil
                                <span class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle"></span>
                            </button>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editarPerfilModal">
                                <i class="fa-solid fa-circle-plus"></i> Editar perfil
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal completar perfil -->
<div class="modal fade" id="completarPerfilModal" tabindex="-1" aria-labelledby="completarPerfilModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="completarPerfilModalLabel">Completar Perfil</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <p>Formulario para completar perfil del especialista...</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary">Guardar cambios</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal editar perfil -->
<div class="modal fade" id="editarPerfilModal" tabindex="-1" aria-labelledby="editarPerfilModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editarPerfilModalLabel">Editar Perfil</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <p>Formulario para editar perfil del especialista...</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary">Guardar cambios</button>
      </div>
    </div>
  </div>
</div>

<?php include '../../components/usuaria/footer.php'; ?>
</body>
</html>
