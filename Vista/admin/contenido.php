<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/obtenerLink/obtenerLink.php';
$urlBase = getBaseUrl();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['correo']) || $_SESSION['id_rol'] != 3) {
    header("Location: {$urlBase}");
    exit;

}


?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Contenido - Shakti</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <link href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
</head>
<body class="sb-nav-fixed">

<?php include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/admin/navbar.php'; ?>

<div id="layoutSidenav">
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/admin/lateral.php'; ?>
  <div id="layoutSidenav_content">
    <main>
      <div class="container-fluid px-4 mb-5">
        <div class="container">
          <h1 class="page-header text-center mt-4"><strong>Gestion de Contenido</strong></h1>
          <div class="row">
            <div class="col-sm-12">
              <!-- Botón para abrir modal -->
              <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalContenido">
                Agregar <i class="fa-solid fa-circle-plus"></i>
              </button>

              <!-- Alertas de estado -->
              <?php if (isset($_GET['status'])): ?>
                <?php
                $mensajes = [
                  'eliminado' => 'El contenido fue eliminado correctamente.',
                  'estatus_actualizado' => 'El estado del contenido fue actualizado correctamente.',
                  'error_activar' => 'Error al activar el contenido.',
                  'error_eliminar' => 'Error al eliminar el contenido.',
                  'error_estatus' => 'Error al cambiar el estado del contenido.',
                ];
                $clase = in_array($_GET['status'], ['eliminado', 'estatus_actualizado']) ? 'success' : 'danger';
                ?>
                <div class="alert alert-<?= $clase ?> alert-dismissible fade show" role="alert">
                  <?= $mensajes[$_GET['status']] ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
              <?php endif; ?>

              <!-- Tabla de contenido -->
              <table class="table table-bordered table-striped mt-3" id="tablaContenido">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Descripción</th>
                    <th>URL</th>
                    <th>Imagen</th>
                    <th>Autor</th>
                    <th>Fecha</th>
                    <th>Estatus</th>
                    <th>Opciones</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  include_once '../../Modelo/conexion.php';
                  $database = new ConectarDB();
                  $db = $database->open();

                  try {
                    $sql = "SELECT id, titulo, descripcion, url, imagen, autor, fecha_publicacion, estatus FROM contenido ORDER BY fecha_publicacion DESC";
                    foreach ($db->query($sql) as $row):
                  ?>
                    <tr>
                      <td><?= $row['id'] ?></td>
                      <td><?= htmlspecialchars($row['titulo']) ?></td>
                      <td><?= nl2br(htmlspecialchars($row['descripcion'])) ?></td>
                      <td><?= htmlspecialchars($row['url']) ?></td>
                      <td>
                        <?php if (!empty($row['imagen'])): ?>
                          <img src="data:image/jpeg;base64,<?= base64_encode($row['imagen']) ?>" alt="Imagen" width="100" height="100" style="object-fit: contain;">
                        <?php else: ?>
                          <span class="text-muted">Sin imagen</span>
                        <?php endif; ?>
                      </td>
                      <td>
                        <?php if (!empty($row['autor'])): ?>
                          <a href="<?= htmlspecialchars($row['url']) ?>" target="_blank">Ver enlace</a>
                        <?php else: ?>
                          <span class="text-muted">N/A</span>
                        <?php endif; ?>
                      </td>
                      <td><?= date('d M Y', strtotime($row['fecha_publicacion'])) ?></td>
                      <td><?= $row['estatus'] ? 'Activo' : 'Inactivo' ?></td>
                      <td>
                        <a href="editar_contenido.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Editar</a>
                        <a href="eliminar_contenido.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar este contenido?')">Eliminar</a>
                      </td>
                    </tr>
                  <?php endforeach; } catch (PDOException $e) {
                      echo '<tr><td colspan="9">Error: ' . $e->getMessage() . '</td></tr>';
                  } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</div>

<!-- Modal para agregar contenido -->
<div class="modal fade" id="modalContenido" tabindex="-1" aria-labelledby="modalContenidoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content shadow">
      <div class="modal-header">
        <h5 class="modal-title" id="modalContenidoLabel">Registrar nuevo contenido</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form method="POST" enctype="multipart/form-data" action="<?= $urlBase ?>Controlador/contenido.Ctrl.php">
          <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" name="titulo" class="form-control" required minlength="3">
          </div>
          <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control" rows="4" required minlength="5"></textarea>
          </div>
          <div class="mb-3">
            <label for="url" class="form-label">URL (opcional)</label>
            <input type="url" name="url" class="form-control" placeholder="https://ejemplo.com">
          </div>
          <div class="mb-3">
            <label for="imagen" class="form-label">Imagen</label>
            <input type="file" name="imagen" class="form-control" accept="image/*" required>
          </div>
          <input type="hidden" name="opcion" value="1">
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-success">Guardar contenido</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- JS Bootstrap + DataTables -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>

<script>
  $(document).ready(function () {
    $('#tablaContenido').DataTable({
      language: {
        "decimal": "",
        "emptyTable": "No hay información",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
        "infoEmpty": "Mostrando 0 a 0 de 0 entradas",
        "infoFiltered": "(filtrado de _MAX_ entradas totales)",
        "lengthMenu": "Mostrar _MENU_ entradas",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "Buscar:",
        "zeroRecords": "Sin resultados encontrados",
        "paginate": {
          "first": "Primero",
          "last": "Último",
          "next": "Siguiente",
          "previous": "Anterior"
        }
      }
    });
  });
</script>

</body>
</html>
