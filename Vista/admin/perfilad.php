<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Validar que el usuario esté logueado y sea administrador
if (empty($_SESSION['correo']) || $_SESSION['nombre_rol'] !== 'administrador') {
    header("Location: ../../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Perfil Administrador - Shakti</title>
    <link rel="stylesheet" href="../css/estilos.css" />
    <link rel="stylesheet" href="../css/registro.css" />
    <link rel="stylesheet" href="../css/perfil.css" />
    <link rel="stylesheet" href="../css/footer.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <?php include '../../components/admin/navbar.php'; ?>
</head>

<body>

    <div class="container mt-5">
        <div class="main-body">
            <div class="row gutters-sm">
                <!-- Perfil lado izquierdo -->
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-column align-items-center text-center">
                                <!-- Puedes cambiar la imagen por una del administrador si tienes -->
                                <img src="https://bootdey.com/img/Content/avatar/avatar3.png" alt="Admin" class="rounded-circle" width="150">
                                <div class="mt-3">
                                    <h4><?php echo isset($_SESSION['nombre']) ? ucwords(strtolower($_SESSION['nombre'])) : " " ?></h4>
                                    <p class="text-secondary mb-1"><?php echo isset($_SESSION['nombre_rol']) ? ucwords(strtolower($_SESSION['nombre_rol'])) : " " ?></p>
                                    <p class="text-muted font-size-sm"><?php echo isset($_SESSION['direccion']) ? ucwords(strtolower($_SESSION['direccion'])) : " " ?></p>
                                    <button class="btn btn-outline-primary">Mensaje</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Perfil lado derecho -->
                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-body">
                            <!-- Aquí puedes mostrar información editable o no editable del admin -->
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Nombre completo</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <?php echo isset($_SESSION['nombre']) && isset($_SESSION['apellidos']) ? ucwords(strtolower($_SESSION['nombre'] . " " . $_SESSION['apellidos'])) : " " ?>
                                </div>
                            </div>
                            <hr />
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Correo electrónico</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <?php echo isset($_SESSION['correo']) ? $_SESSION['correo'] : " " ?>
                                </div>
                            </div>
                            <hr />
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Teléfono</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <?php echo isset($_SESSION['telefono']) ? $_SESSION['telefono'] : " " ?>
                                </div>
                            </div>
                            <hr />
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Dirección</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <?php echo isset($_SESSION['direccion']) ? $_SESSION['direccion'] : " " ?>
                                </div>
                            </div>
                            <hr />
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Estado de cuenta</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    Activa
                                </div>
                            </div>

                            <hr />
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

                    <!-- Puedes incluir aquí otras secciones similares a las del especialista, si deseas -->

                </div>
            </div>
        </div>
    </div>

    <!-- Modal ejemplo para completar perfil -->
    <div class="modal fade" id="completarPerfilModal" tabindex="-1" aria-labelledby="completarPerfilModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <!-- Modal contenido aquí -->
          <div class="modal-header">
            <h5 class="modal-title" id="completarPerfilModalLabel">Completar Perfil</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            <!-- Formulario para completar perfil -->
            <!-- Aquí iría el formulario -->
            <p>Formulario para completar perfil del administrador...</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary">Guardar cambios</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal ejemplo para editar perfil -->
    <div class="modal fade" id="editarPerfilModal" tabindex="-1" aria-labelledby="editarPerfilModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <!-- Modal contenido aquí -->
          <div class="modal-header">
            <h5 class="modal-title" id="editarPerfilModalLabel">Editar Perfil</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            <!-- Formulario para editar perfil -->
            <p>Formulario para editar perfil del administrador...</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary">Guardar cambios</button>
          </div>
        </div>
      </div>
    </div>

    <?php include '../../components/usuaria/footer.php';
; ?>

</body>

</html>
