<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/obtenerLink/obtenerLink.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Modelo/notasModelo.php';
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
    $tieneFoto = true;
} else {
    $fotoSrc = $urlBase . 'img/undraw_chill-guy-avatar_tqsm.svg';
    $tieneFoto = false;
}

$notasObj = new Notas();
$idUsuaria = $_SESSION['id_usuaria'];

$notasPorPagina = 3;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina - 1) * $notasPorPagina;

$notas = $notasObj->obtenerNotasPaginadas($idUsuaria, $offset, $notasPorPagina);

$totalNotas = $notasObj->contarNotas($idUsuaria);
$totalPaginas = ceil($totalNotas / $notasPorPagina);
?>



<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Perfil - Shakti</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/usuaria/navbar.php'; ?>
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
                                <div class="profile-pic-wrapper position-relative">
                                    <!-- Imagen de perfil -->
                                    <img src="<?php echo $fotoSrc; ?>"
                                        alt="Foto de perfil"
                                        class="rounded-circle" width="150" height="150">

                                    <!-- Botón editar -->
                                    <button id="editFotoBtn" class="edit-icon"
                                        data-bs-placement="top" title="Cambiar foto">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>

                                    <!-- Botón eliminar solo si tiene foto -->
                                    <?php if ($tieneFoto): ?>
                                        <button id="deleteFotoBtn" class="delete-icon"
                                            data-bs-toggle="modal" data-bs-target="#confirmDeleteModal"
                                            data-bs-placement="top" title="Eliminar foto">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>

                                <div class="mt-3">
                                    <h4>
                                        <?php echo isset($_SESSION['nombre'])
                                            ? ucwords(strtolower($_SESSION['nombre']))
                                            : " "; ?>
                                    </h4>
                                    <p class="text-secondary mb-1">
                                        <?php echo isset($_SESSION['descripcion'])
                                            ? ucwords(strtolower($_SESSION['descripcion']))
                                            : " "; ?>
                                    </p>

                                    <button class="btn btn-outline-primary"
                                        onclick="window.location.href='<?php echo '../chat'; ?>'">
                                        <i class="bi bi-envelope-paper-heart-fill"></i> Mensajes
                                    </button>

                                    <button type="button" class="btn btn-outline-secondary"
                                        data-bs-toggle="modal" data-bs-target="#exampleModal"
                                        data-bs-whatever="@fat">
                                        <i class="bi bi-book-fill"></i> Notas
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Notas -->
                    <div class="card mt-3 notas">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                <h6 class="mb-0">Notas recientes</h6>
                            </li>

                            <?php foreach ($notas as $index => $nota): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                    <h6 class="mb-0">
                                        <i class="bi bi-journal"></i> <?= htmlspecialchars($nota['titulo']) ?>
                                    </h6>
                                    <span class="text-secondary" data-bs-placement="top"
                                        title="Ver nota">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#notaModal<?= $index ?>">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </span>
                                </li>
                                <?php include '../modales/notas.php'; ?>
                            <?php endforeach; ?>
                            <?php if ($totalPaginas > 1): ?>
                                <nav aria-label="Paginación de notas">
                                    <ul class="pagination justify-content-end px-3 pb-2 mt-2">
                                        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                                            <li class="page-item <?= $i == $pagina ? 'active' : '' ?>">
                                                <a class="page-link" href="?pagina=<?= $i ?>"><?= $i ?></a>
                                            </li>
                                        <?php endfor; ?>
                                    </ul>
                                </nav>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
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

    <script src="<?= $urlBase ?>peticiones(js)/tooltip.js"></script>

    <script src="../../validacionRegistro/notas.js"></script>
    <script src="<?= $urlBase ?>peticiones(js)/mandarMetricas.js.php?vista=<?= urlencode(basename($_SERVER['PHP_SELF'])) ?>"></script>
    <?php include '../modales/perfil.php'; ?>
    <script src="../../peticiones(js)/actualizarFoto.js"></script>
    <script src="../../validacionRegistro/validacionActualizacion.js"></script>
    <?php include '../../components/usuaria/footer.php'; ?>
</body>

</html>