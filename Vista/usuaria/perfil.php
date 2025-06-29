<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/obtenerLink/obtenerLink.php';
$urlBase = getBaseUrl();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['correo']) || $_SESSION['id_rol'] == 2) {
    header("Location: {$urlBase}Vista/especialista/perfil.php");
    exit;
} else if (empty($_SESSION['correo']) || $_SESSION['id_rol'] == 3) {
    header("Location: {$urlBase}Vista/admin");
    exit;
} else if (empty($_SESSION['correo']) || $_SESSION['id_rol'] != 1) {
    header("Location: {$urlBase}index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Perfil - Shakti</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php include '../../components/usuaria/navbar.php'; ?>
</head>

<body>

    <div class="container mt-5">
        <div class="main-body">
            <div class="row gutters-sm">
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-column align-items-center text-center">
                                <img src="https://cdn1.iconfinder.com/data/icons/avatar-3/512/Secretary-512.png" alt="Admin" class="rounded-circle" width="150">
                                <div class="mt-3">
                                    <h4><?php echo isset($_SESSION['nombre']) ? ucwords(strtolower($_SESSION['nombre'])) : " " ?></h4>
                                    <p class="text-secondary mb-1"><?php echo isset($_SESSION['nombre_rol']) ? ucwords(strtolower($_SESSION['nombre_rol'])) : " " ?></p>
                                    <p class="text-muted font-size-sm"><?php echo isset($_SESSION['direccion']) ? ucwords(strtolower($_SESSION['direccion'])) : " " ?></p>
                                    <button class="btn btn-outline-primary">Mensaje</button>
                                    <button class="btn btn-secondary">Diario</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Nombre completo</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <?php
                                    echo (isset($_SESSION['nombre']) && isset($_SESSION['apellidos']))
                                        ? ucwords(strtolower($_SESSION['nombre'])) . ' ' . ucwords(strtolower($_SESSION['apellidos']))
                                        : " ";
                                    ?>
                                </div>

                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Correo eléctronico</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <?php echo isset($_SESSION['correo']) ? $_SESSION['correo'] : " "; ?>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Teléfono</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <?php echo isset($_SESSION['telefono']) ? $_SESSION['telefono'] : " "; ?>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Dirección</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <?php echo isset($_SESSION['direccion']) ? $_SESSION['direccion'] : " "; ?>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Nickname</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <?php echo isset($_SESSION['nickname']) ? $_SESSION['nickname'] : ""  ?>
                                </div>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-end gap-2 mb-3">
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
</body>

</html>