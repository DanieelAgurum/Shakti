<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/obtenerLink/obtenerLink.php';
require_once 'organizacion.php';
$urlBase = getBaseUrl();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['correo']) || $_SESSION['id_rol'] != 3) {
    header("Location: {$urlBase}");
    exit;
}

$organizacion = new organizacionesModelo();
$statusMsg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'create') {
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $imagen = file_get_contents($_FILES['imagen']['tmp_name']);
        if ($organizacion->create($nombre, $descripcion, $imagen)) {
            $statusMsg = 'Organización creada correctamente.';
        } else {
            $statusMsg = 'Error al crear la organización.';
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'update') {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $imagen = file_get_contents($_FILES['imagen']['tmp_name']);
        $estatus = $_POST['estatus'];
        if ($organizacion->update($id, $nombre, $descripcion, $imagen, $estatus)) {
            $statusMsg = 'Organización actualizada correctamente.';
        } else {
            $statusMsg = 'Error al actualizar la organización.';
        }
    } elseif (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        if ($organizacion->delete($id)) {
            $statusMsg = 'Organización eliminada correctamente.';
            header("Location: ?status=eliminada");
            exit;
        } else {
            $statusMsg = 'Error al eliminar la organización.';
            header("Location: ?status=error_eliminar");
            exit;
        }
    }
}

$organizaciones = $organizacion->getAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organizaciones - Shakti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-vet {
            border: 1px solid #ddd;
            border-radius: 15px;
            text-align: center;
            padding: 20px;
            margin: 10px;
            background: #fff;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .card-vet img {
            border-radius: 50%;
            width: 150px;
            height: 150px;
            object-fit: cover;
        }
        .vet-list {
            background-color: #e6f0fa;
            padding: 20px;
        }
    </style>
</head>
<body class="sb-nav-fixed">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/admin/navbar.php'; ?>
    <div id="layoutSidenav">
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/admin/lateral.php'; ?>
        <div id="layoutSidenav_content">
            <main>
                <div style="margin-top: -100px">
                    <div class="container vet-list">
                        <h1 class="text-center" style="color: #007bff;">Nuestras Organizaciones</h1>
                        <?php if ($statusMsg): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?php echo $statusMsg; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Create Form -->
                        <div class="mb-4">
                            <h3>Agregar Organización</h3>
                            <form method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="action" value="create">
                                <div class="mb-3">
                                    <input type="text" name="nombre" class="form-control" placeholder="Nombre" required>
                                </div>
                                <div class="mb-3">
                                    <textarea name="descripcion" class="form-control" placeholder="Descripción" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <input type="file" name="imagen" class="form-control" accept="image/*" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Crear</button>
                            </form>
                        </div>

                        <!-- Organizations Cards -->
                        <div class="row">
                            <?php foreach ($organizaciones as $org): ?>
                                <div class="col-md-4">
                                    <div class="card-vet">
                                        <?php if (!empty($org['imagen'])): ?>
                                            <img src="data:image/*;base64,<?php echo base64_encode($org['imagen']); ?>" alt="<?php echo $org['nombre']; ?>">
                                        <?php else: ?>
                                            <img src="https://cdn1.iconfinder.com/data/icons/business-1218/512/business_organization-512.png" alt="Default Image">
                                        <?php endif; ?>
                                        <h4><?php echo $org['nombre']; ?></h4>
                                        <p>Descripción: <?php echo $org['descripcion']; ?></p>
                                        <p>Estatus: <?php echo $org['estatus'] ? 'Activa' : 'Desactivada'; ?></p>
                                        <a href="?delete=<?php echo $org['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar?');">Eliminar</a>
                                        <!-- Update Form (inline) -->
                                        <form method="POST" enctype="multipart/form-data" class="mt-2" style="display: inline;">
                                            <input type="hidden" name="action" value="update">
                                            <input type="hidden" name="id" value="<?php echo $org['id']; ?>">
                                            <input type="text" name="nombre" class="form-control mb-2" value="<?php echo $org['nombre']; ?>" required>
                                            <textarea name="descripcion" class="form-control mb-2" required><?php echo $org['descripcion']; ?></textarea>
                                            <input type="file" name="imagen" class="form-control mb-2" accept="image/*">
                                            <select name="estatus" class="form-control mb-2">
                                                <option value="1" <?php echo $org['estatus'] == 1 ? 'selected' : ''; ?>>Activa</option>
                                                <option value="0" <?php echo $org['estatus'] == 0 ? 'selected' : ''; ?>>Desactivada</option>
                                            </select>
                                            <button type="submit" class="btn btn-warning btn-sm">Actualizar</button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>