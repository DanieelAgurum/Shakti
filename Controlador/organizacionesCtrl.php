<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/obtenerLink/obtenerLink.php';
require_once 'organizacionesModelo.php';
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
        $numero = $_POST['numero'];
        $imagen = file_get_contents($_FILES['imagen']['tmp_name']);
        if ($organizacion->create($nombre, $descripcion, $numero, $imagen)) {
            $statusMsg = 'Organización creada correctamente.';
        } else {
            $statusMsg = 'Error al crear la organización.';
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'update') {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $numero = $_POST['numero'];
        $imagen = !empty($_FILES['imagen']['tmp_name']) ? file_get_contents($_FILES['imagen']['tmp_name']) : null;
        if ($organizacion->update($id, $nombre, $descripcion, $numero, $imagen)) {
            $statusMsg = 'Organización actualizada correctamente.';
        } else {
            $statusMsg = 'Error al actualizar la organización.';
        }
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

$organizaciones = $organizacion->getAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Organizaciones - Shakti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        table {
            background-color: #2c3e50;
            color: white;
        }
        th, td {
            text-align: center;
            vertical-align: middle;
        }
        .btn-custom {
            background-color: #27ae60;
            color: white;
            border: none;
        }
        .btn-custom:hover {
            background-color: #219653;
        }
        .btn-delete {
            background-color: #e74c3c;
            color: white;
            border: none;
        }
        .btn-delete:hover {
            background-color: #c0392b;
        }
        img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 50%;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4" style="color: #2c3e50;">Servicios</h2>
        <a href="#" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addModal">+ Añadir nueva organización</a>

        <?php if ($statusMsg): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $statusMsg; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        <?php endif; ?>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Numero</th>
                    <th>Imagen</th>
                    <th>Editar</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($organizaciones as $org): ?>
                    <tr>
                        <td><?php echo $org['id']; ?></td>
                        <td><?php echo $org['nombre']; ?></td>
                        <td><?php echo $org['descripcion']; ?></td>
                        <td><?php echo $org['numero']; ?></td>
                        <td>
                            <?php if (!empty($org['imagen'])): ?>
                                <img src="data:image/*;base64,<?php echo base64_encode($org['imagen']); ?>" alt="<?php echo $org['nombre']; ?>">
                            <?php else: ?>
                                <img src="https://via.placeholder.com/60" alt="Default Image">
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="#" class="btn btn-custom btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $org['id']; ?>">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </td>
                        <td>
                            <a href="?delete=<?php echo $org['id']; ?>" class="btn btn-delete btn-sm" onclick="return confirm('¿Seguro que deseas eliminar?');">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal<?php echo $org['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $org['id']; ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel<?php echo $org['id']; ?>">Editar Organización</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" name="id" value="<?php echo $org['id']; ?>">
                                        <div class="mb-3">
                                            <label class="form-label">Nombre</label>
                                            <input type="text" name="nombre" class="form-control" value="<?php echo $org['nombre']; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Descripción</label>
                                            <textarea name="descripcion" class="form-control" required><?php echo $org['descripcion']; ?></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Numero</label>
                                            <input type="text" name="numero" class="form-control" value="<?php echo $org['numero']; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Imagen</label>
                                            <input type="file" name="imagen" class="form-control" accept="image/*">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Agregar Nueva Organización</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="create">
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea name="descripcion" class="form-control" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Numero</label>
                            <input type="text" name="numero" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Imagen</label>
                            <input type="file" name="imagen" class="form-control" accept="image/*" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Crear</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
</body>
</html>