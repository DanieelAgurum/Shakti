<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/obtenerLink/obtenerLink.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/Modelo/notasModelo.php';
$urlBase = getBaseUrl();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['correo']) || isset($_SESSION['id_rol'])) {
    if ($_SESSION['id_rol'] == 2) {
        header("Location: {$urlBase}Vista/especialista/perfil.php");
        exit;
    } else if ($_SESSION['id_rol'] == 3) {
        header("Location: {$urlBase}Vista/admin");
        exit;
    }
} else {
    header("Location: {$urlBase}index.php");
    exit;
}

if (!empty($_SESSION['foto'])) {
    $fotoSrc = 'data:image/*;base64,' . base64_encode($_SESSION['foto']);
} else {
    $fotoSrc = 'https://cdn1.iconfinder.com/data/icons/avatar-3/512/Secretary-512.png';
}

$notasModel = new Notas();
$notas = $notasModel->obtenerNotas()
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
                <!-- Columna izquierda con la foto y botones -->
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-column align-items-center text-center">
                                <img src="<?php echo $fotoSrc; ?>" alt="<?php echo isset($_SESSION['nombre_rol']) ? ucwords(strtolower($_SESSION['nombre_rol'])) : " " ?>" class="rounded-circle" width="150" height="150">
                                <div class="mt-3">
                                    <h4><?php echo isset($_SESSION['nombre']) ? ucwords(strtolower($_SESSION['nombre'])) : " " ?></h4>
                                    <p class="text-secondary mb-1"><?php echo isset($_SESSION['descripcion']) ? ucwords(strtolower($_SESSION['descripcion'])) : " " ?></p>
                                    <button class="btn btn-outline-primary">
                                        <i class="bi bi-envelope-paper-heart-fill"></i> Mensajes
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@fat">
                                        <i class="bi bi-book-fill"></i> Notas
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Notas -->
                    <div class="card mt-3">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                <h6 class="mb-0">Notas recientes</h6>
                            </li>

                            <?php foreach ($notas as $index => $nota): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                    <h6 class="mb-0">
                                        <i class="bi bi-journal"></i> <?= htmlspecialchars($nota['titulo']) ?>
                                    </h6>
                                    <span class="text-secondary">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#notaModal<?= $index ?>">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </span>
                                </li>
                                <?php include '../modales/notas.php'; ?>
                            <?php endforeach; ?>
                            <li class="list-group-item d-flex justify-content-end align-items-center">
                                <h6 class="mb-0">
                                    <a href="" class="text-decoration-none">Ver más</a>
                                </h6>
                            </li>
                        </ul>
                    </div>
                </div> <!-- /col-md-4 -->
                <!-- Columna derecha con datos del perfil -->
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
                                    <h6 class="mb-0">Correo electrónico</h6>
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
                                    <h6 class="mb-0">Nombre de usuaria</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <?php echo isset($_SESSION['nickname']) ? $_SESSION['nickname'] : "" ?>
                                </div>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-end gap-2 mb-3">
                                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editarPerfilModal">
                                    <i class="bi bi-pencil-fill"></i> Editar perfil
                                </button>
                            </div>
                        </div>
                    </div>
                </div> <!-- /col-md-8 -->
            </div> <!-- /row -->
        </div> <!-- /main-body -->
    </div> <!-- /container -->

    <?php include '../modales/notas.php'; ?>

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

    <script src="../../validacionRegistro/notas.js"></script>
    <script src="<?= $urlBase ?>peticiones(js)/mandarMetricas.js.php?vista=<?= urlencode(basename($_SERVER['PHP_SELF'])) ?>"></script>
    <?php include '../modales/perfil.php'; ?>
    <script src="../../validacionRegistro/validacionActualizacion.js"></script>
    <?php include '../../components/usuaria/footer.php'; ?>
</body>

</html>