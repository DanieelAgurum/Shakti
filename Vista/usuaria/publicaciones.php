<?php
session_start();

// Validar rol de usuaria (1)
if (!isset($_SESSION['id_rol']) || $_SESSION['id_rol'] != 1) {
    echo "<p class='text-danger'>No tienes permiso para acceder a esta sección.</p>";
    exit;
}

function conectarBD() {
    $con = mysqli_connect("localhost", "root", "", "shakti");
    if (!$con) {
        die("<p class='text-danger'>Error en la conexión a la base de datos.</p>");
    }
    return $con;
}

$con = conectarBD();

// Procesar formulario POST (crear o actualizar publicación)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id_publicacion'] ?? 0);
    $titulo = mysqli_real_escape_string($con, trim($_POST['titulo'] ?? ''));
    $contenido = mysqli_real_escape_string($con, trim($_POST['contenido'] ?? ''));
    $id_usuaria = intval($_SESSION['id_usuaria'] ?? 0);

    if ($titulo === '' || $contenido === '') {
        echo "<div class='alert alert-danger'>Los campos título y contenido son obligatorios.</div>";
    } else if ($id_usuaria === 0) {
        echo "<div class='alert alert-danger'>Error: No se pudo identificar al usuario.</div>";
    } else {
        if ($id > 0) {
            // Actualizar
            $query = "UPDATE publicacion SET titulo='$titulo', contenido='$contenido' WHERE id_publicacion = $id";
        } else {
            // Insertar
            $query = "INSERT INTO publicacion (titulo, contenido, fecha_publicacion, id_usuarias) 
                      VALUES ('$titulo', '$contenido', NOW(), $id_usuaria)";
        }

        if (mysqli_query($con, $query)) {
            echo "<div class='alert alert-success'>Publicación guardada correctamente.</div>";
        } else {
            echo "<div class='alert alert-danger'>Error al guardar publicación: " . mysqli_error($con) . "</div>";
        }
    }
}

// Cargar publicación para editar si existe $_GET['editar_id']
$editarPublicacion = null;
if (isset($_GET['editar_id'])) {
    $editar_id = intval($_GET['editar_id']);
    $res = mysqli_query($con, "SELECT * FROM publicacion WHERE id_publicacion = $editar_id");
    $editarPublicacion = mysqli_fetch_assoc($res);
}

// Obtener todas las publicaciones ordenadas por fecha descendente
$publicaciones = [];
$res = mysqli_query($con, "SELECT * FROM publicacion ORDER BY fecha_publicacion DESC");
while ($fila = mysqli_fetch_assoc($res)) {
    $publicaciones[] = $fila;
}

mysqli_close($con);
?>

<!-- Estilos Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

<div class="container my-4">
    <h2 class="mb-4">Gestión de Publicaciones</h2>

    <!-- Formulario para crear o editar -->
    <form method="post" class="mb-5">
        <input type="hidden" name="id_publicacion" value="<?= htmlspecialchars($editarPublicacion['id_publicacion'] ?? '') ?>" />
        <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" id="titulo" name="titulo" class="form-control" required
                value="<?= htmlspecialchars($editarPublicacion['titulo'] ?? '') ?>" />
        </div>

        <div class="mb-3">
            <label for="contenido" class="form-label">Contenido</label>
            <textarea id="contenido" name="contenido" rows="5" class="form-control" required><?= htmlspecialchars($editarPublicacion['contenido'] ?? '') ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">
            <?= isset($editarPublicacion) ? 'Actualizar publicación' : 'Crear publicación' ?>
        </button>
        <?php if (isset($editarPublicacion)): ?>
            <a href="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="btn btn-secondary ms-2">Cancelar</a>
        <?php endif; ?>
    </form>

    <!-- Lista de publicaciones -->
    <?php if (empty($publicaciones)): ?>
        <p>No hay publicaciones disponibles.</p>
    <?php else: ?>
        <div class="list-group">
            <?php foreach ($publicaciones as $pub): ?>
                <div class="list-group-item mb-3">
                    <h5><?= htmlspecialchars($pub['titulo']) ?></h5>
                    <p><?= nl2br(htmlspecialchars($pub['contenido'])) ?></p>
                    <small class="text-muted">Publicado el <?= date("d/m/Y H:i", strtotime($pub['fecha_publicacion'])) ?></small>
                    <div class="mt-2">
                        <a href="?editar_id=<?= $pub['id_publicacion'] ?>" class="btn btn-sm btn-outline-primary">Editar</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- JS de Bootstrap (opcional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
