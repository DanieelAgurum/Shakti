<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/obtenerLink/obtenerLink.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/contenidoMdl.php';
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="sb-nav-fixed">

<?php include $_SERVER['DOCUMENT_ROOT'] . '/components/admin/navbar.php'; ?>

<div id="layoutSidenav">
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/components/admin/lateral.php'; ?>
  <div id="layoutSidenav_content">
    <main>
      <div class="container-fluid px-4 mb-5">
        <div class="container">
          <h1 class="page-header text-center mt-4"><strong>Gestión de Contenido</strong></h1>
          <div class="row">
            <div class="col-sm-12">
              <!-- Botón para abrir modal de agregar -->
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
                  'exito_actualizar' => 'El contenido se actualizó correctamente.',
                  'exito_agregar' => 'El contenido se agregó correctamente.'
                ];
                $clase = in_array($_GET['status'], ['eliminado', 'estatus_actualizado', 'exito_actualizar', 'exito_agregar']) ? 'success' : 'danger';
                ?>
                <div class="alert alert-<?= $clase ?> alert-dismissible fade show" role="alert">
                  <?= $mensajes[$_GET['status']] ?? 'Ha ocurrido un error inesperado.' ?>
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
                  $contenido_mdl = new contenidoMdl();
                  $conexion = $contenido_mdl->conectarBD();

                  try {
                      $sql = "SELECT id, titulo, descripcion, url, imagen, autor, fecha_publicacion, estatus FROM contenido ORDER BY fecha_publicacion DESC";
                      $resultado = mysqli_query($conexion, $sql);
                      while ($row = mysqli_fetch_assoc($resultado)):
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
                      <td><?= htmlspecialchars($row['autor']) ?></td>
                      <td><?= date('d M Y', strtotime($row['fecha_publicacion'])) ?></td>
                      <td><?= $row['estatus'] ? 'Activo' : 'Inactivo' ?></td>
                      <td>
                        <button type="button" class="btn btn-sm btn-primary btn-editar" 
                          data-bs-toggle="modal" 
                          data-bs-target="#modalEditar"
                          data-id="<?= $row['id'] ?>"
                          data-titulo="<?= htmlspecialchars($row['titulo']) ?>"
                          data-descripcion="<?= htmlspecialchars($row['descripcion']) ?>"
                          data-url="<?= htmlspecialchars($row['url']) ?>"
                          data-imagen="<?= base64_encode($row['imagen']) ?>">
                          <i class="fa-solid fa-edit"></i> Editar
                        </button>
                        <button type="button" class="btn btn-sm btn-danger btn-eliminar"
                          data-bs-toggle="modal" 
                          data-bs-target="#modalEliminar" 
                          data-id="<?= $row['id'] ?>">
                          <i class="fa-solid fa-trash-alt"></i> Eliminar
                        </button>
                      </td>
                    </tr>
                  <?php endwhile; } catch (Exception $e) {
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

<!-- Modal para editar contenido -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarLabel">Editar contenido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="form-editar" method="POST" enctype="multipart/form-data" action="<?= $urlBase ?>Controlador/contenido.Ctrl.php">
                    <input type="hidden" name="opcion" value="2">
                    <input type="hidden" name="id" id="editar-id">
                    <div class="mb-3">
                        <label for="editar-titulo" class="form-label">Título</label>
                        <input type="text" name="titulo" id="editar-titulo" class="form-control" required minlength="3">
                    </div>
                    <div class="mb-3">
                        <label for="editar-descripcion" class="form-label">Descripción</label>
                        <textarea name="descripcion" id="editar-descripcion" class="form-control" rows="4" required minlength="5"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="editar-url" class="form-label">URL (opcional)</label>
                        <input type="url" name="url" id="editar-url" class="form-control" placeholder="https://ejemplo.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Imagen Actual</label>
                        <div class="mb-2">
                            <img id="imagen-actual" src="" alt="Imagen Actual" width="150" height="150" style="object-fit: contain;">
                        </div>
                        <label for="editar-imagen" class="form-label">Subir nueva imagen (opcional)</label>
                        <input type="file" name="imagen" id="editar-imagen" class="form-control" accept="image/*">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación para eliminar contenido -->
<div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEliminarLabel">Confirmar eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar este contenido?</p>
                <form id="form-eliminar" action="<?= $urlBase ?>Controlador/contenido.Ctrl.php" method="POST">
                    <input type="hidden" name="opcion" value="3">
                    <input type="hidden" name="id" id="eliminar-id">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
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
    // Inicialización de DataTables
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

    // JavaScript para el modal de edición
    var modalEditar = document.getElementById('modalEditar');
    modalEditar.addEventListener('show.bs.modal', function (event) {
      // Botón que disparó el modal
      var button = event.relatedTarget;
      // Extrae la información de los atributos data-*
      var id = button.getAttribute('data-id');
      var titulo = button.getAttribute('data-titulo');
      var descripcion = button.getAttribute('data-descripcion');
      var url = button.getAttribute('data-url');
      var imagenBase64 = button.getAttribute('data-imagen');

      // Actualiza los campos del modal
      var modalTitle = modalEditar.querySelector('.modal-title');
      var inputId = modalEditar.querySelector('#editar-id');
      var inputTitulo = modalEditar.querySelector('#editar-titulo');
      var inputDescripcion = modalEditar.querySelector('#editar-descripcion');
      var inputUrl = modalEditar.querySelector('#editar-url');
      var imagenActual = modalEditar.querySelector('#imagen-actual');

      modalTitle.textContent = 'Editar contenido: ' + titulo;
      inputId.value = id;
      inputTitulo.value = titulo;
      inputDescripcion.value = descripcion;
      inputUrl.value = url;
      if (imagenBase64) {
        imagenActual.src = 'data:image/jpeg;base64,' + imagenBase64;
      } else {
        imagenActual.src = 'https://placehold.co/150x150/e0e0e0/000000?text=Sin+imagen';
      }
    });
    
    // JavaScript para el modal de eliminación
    var modalEliminar = document.getElementById('modalEliminar');
    modalEliminar.addEventListener('show.bs.modal', function (event) {
        // Botón que disparó el modal
        var button = event.relatedTarget;
        // Extrae el ID del atributo data-id
        var id = button.getAttribute('data-id');
        // Actualiza el campo oculto del formulario de eliminación
        var inputId = modalEliminar.querySelector('#eliminar-id');
        inputId.value = id;
    });
  });
</script>

</body>
</html>
