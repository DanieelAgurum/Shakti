<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/modelo/PublicacionModelo.php';

$urlBase = '/Shakti/';

// Validar admin
if (!isset($_SESSION['correo']) || $_SESSION['id_rol'] != 3) {
    header("Location: {$urlBase}");
    exit;
}

$id_usuaria = $_SESSION['id_usuaria'];
$publicacionModelo = new PublicacionModelo();
$publicaciones = $publicacionModelo->obtenerPorUsuaria($id_usuaria);

$mensaje = $_SESSION['mensaje'] ?? '';
unset($_SESSION['mensaje']);
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin - Publicaciones</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="<?= $urlBase ?>components/admin/styles.css" />
  <link rel="stylesheet" href="<?= $urlBase ?>components/admin/custom.css" />
  <link rel="stylesheet" href="<?= $urlBase ?>components/admin/datatables.min.css" />
</head>

<body class="sb-nav-fixed">

  <?php include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/admin/navbar.php'; ?>

  <div id="layoutSidenav">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/admin/lateral.php'; ?>

    <div id="layoutSidenav_content">
      <main class="container mt-5">

        <h2>Asesoramiento</h2>

        <?php if ($mensaje): ?>
          <div class="alert alert-info"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <!-- Formulario para crear publicación -->
        <form method="POST" action="<?= $urlBase ?>Controlador/PublicacionAdminCtrl.php" class="mb-4">
          <h5>Crear nueva publicación</h5>
          <div class="mb-3">
            <input type="text" name="titulo" class="form-control" placeholder="Título" minlength="3" required>
          </div>
          <div class="mb-3">
            <textarea name="contenido" class="form-control" placeholder="Contenido" rows="3" minlength="5" required></textarea>
          </div>
          <input type="hidden" name="guardar_publicacion" value="1" />
          <input type="hidden" name="id_usuaria" value="<?= $id_usuaria ?>" />
          <button type="submit" class="btn btn-primary">Crear</button>
        </form>

        <!-- Tabla publicaciones -->
        <table class="table table-striped" id="tablaPublicaciones">
          <thead>
            <tr>
              <th>ID</th>
              <th>Título</th>
              <th>Contenido</th>
              <th>Fecha</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($publicaciones as $pub): ?>
              <tr>
                <form method="POST" action="<?= $urlBase ?>Controlador/PublicacionAdminCtrl.php">
                  <td><?= $pub['id_publicacion'] ?></td>
                  <td>
                    <input type="text" name="titulo" value="<?= htmlspecialchars($pub['titulo']) ?>" class="form-control form-control-sm" minlength="3" required>
                  </td>
                  <td>
                    <textarea name="contenido" class="form-control form-control-sm" rows="2" minlength="5" required><?= htmlspecialchars($pub['contenido']) ?></textarea>
                  </td>
                  <td><?= date('d/m/Y H:i', strtotime($pub['fecha_publicacion'])) ?></td>
                  <td>
                    <input type="hidden" name="id_publicacion" value="<?= $pub['id_publicacion'] ?>" />
                    <input type="hidden" name="editar_publicacion" value="1" />
                    <button type="submit" class="btn btn-success btn-sm mb-1">Guardar</button>
                    <a href="<?= $urlBase ?>Controlador/PublicacionAdminCtrl.php?borrar_id=<?= $pub['id_publicacion'] ?>" onclick="return confirm('¿Eliminar esta publicación?');" class="btn btn-danger btn-sm">Eliminar</a>
                  </td>
                </form>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

      </main>
    </div>
  </div>

  <script src="<?= $urlBase ?>components/admin/bootstrap.bundle.min.js"></script>
  <script src="<?= $urlBase ?>components/admin/datatables.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      new DataTable('#tablaPublicaciones');
    });
  </script>

</body>

</html>
