<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/obtenerLink/obtenerLink.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Modelo/contenidoMdl.php';
$urlBase = getBaseUrl();

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (empty($_SESSION['correo']) || $_SESSION['id_rol'] != 3) {
  header("Location: {$urlBase}");
  exit;
}

$modelo = new Contenido();
$contenidos = $modelo->obtenerContenidos();
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Contenido - NexoH</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <link href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- TinyMCE Editor -->
  <script src="https://cdn.tiny.cloud/1/j2a6tisbajyf3idxgv1i6z4uw6p1zooj40uog0q52ulrme22/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
  <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/admin/icono.php' ?>
</head>

<body class="sb-nav-fixed">

  <?php include $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/admin/navbar.php'; ?>

  <div id="layoutSidenav">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/admin/lateral.php'; ?>
    <div id="layoutSidenav_content">
      <main>
        <div class="container-fluid px-4 mb-5">
          <div class="container">
            <h1 class="page-header text-center mt-4"><strong>Gestión de Contenido</strong></h1>

            <div class="row">
              <div class="col-sm-12">

                <!-- Botón agregar -->
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#agregarContenido">
                  Agregar <i class="fa-solid fa-circle-plus"></i>
                </button>

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

                <div class="table-responsive">
                  <table class="table table-bordered table-striped mt-3" id="tablaContenido">
                    <thead class="text-center">
                      <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Tipo</th>
                        <th>Miniatura</th>
                        <th>URL / Archivo</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Opciones</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      include_once '../../Modelo/conexion.php';
                      $database = new ConectarDB();
                      $db = $database->open();

                      try {
                        $sql = "SELECT * FROM contenidos";
                        foreach ($db->query($sql) as $row) {
                      ?>
                          <tr>
                            <td class="text-center"><?= htmlspecialchars($row['id_contenido']); ?></td>
                            <td><?= htmlspecialchars($row['titulo']); ?></td>
                            <td class="text-center"><span class="badge bg-info text-dark"><?= htmlspecialchars($row['tipo']); ?></span></td>
                            <td class="text-center">
                              <?php if (!empty($row['thumbnail'])): ?>
                                <img src="<?= $urlBase . 'uploads/thumbnails/' . basename($row['thumbnail']); ?>"
                                  alt="Miniatura" width="70" height="70" class="rounded shadow-sm border">
                              <?php else: ?>
                                <span class="text-muted">Sin imagen</span>
                              <?php endif; ?>
                            </td>
                            <td class="text-center">
                              <?php if (!empty($row['url_contenido'])): ?>
                                <a href="<?= htmlspecialchars($row['url_contenido']); ?>"
                                  target="_blank"
                                  class="btn btn-outline-primary btn-sm">
                                  <i class="bi bi-box-arrow-up-right"></i> Ver enlace
                                </a>

                              <?php elseif (!empty($row['archivo'])): ?>
                                <a class="btn btn-sm btn-outline-primary" target="_blank" href="../../Modelo/verArchivo.php?id_contenido=<?php echo $row['id_contenido']; ?>"><i class="bi bi-file-earmark-text"></i> Ver archivo</a>
                              <?php else: ?>
                                <span class="text-muted">N/A</span>
                              <?php endif; ?>
                            </td>

                            <td class="text-center"><?= htmlspecialchars($row['fecha_publicacion']); ?></td>
                            <td>
                              <?php if ($row['estado'] == 1): ?>
                                <span class="badge bg-success">Activo</span>
                              <?php else: ?>
                                <span class="badge bg-danger">Desactivado</span>
                              <?php endif; ?>
                            </td>

                            <td class="text-center">
                              <button class="btn btn-outline-info m-auto btn-sm d-block" data-bs-toggle="modal"
                                data-bs-target="#editarContenido_<?= $row['id_contenido']; ?>">
                                <i class="bi bi-pencil-square"></i> Editar
                              </button><br>

                              <button class="btn btn-outline-danger m-auto btn-sm d-block" data-bs-toggle="modal"
                                data-bs-target="#eliminarContenido_<?= $row['id_contenido']; ?>">
                                <i class="bi bi-trash3"></i> Eliminar
                              </button><br>

                              <a href="#cambiarEstado_<?php echo $row['id_contenido']; ?>"
                                class="btn m-auto btn-sm <?php echo $row['estado'] == 1 ? 'btn-warning' : 'btn-success'; ?> d-block"
                                data-bs-toggle="modal">
                                <i class="fa-sharp fa-solid fa-pen-to-square"></i>
                                <?php echo $row['estado'] == 1 ? 'Desactivar' : 'Activar'; ?>
                              </a>

                            </td>
                            <?php include 'modales/contenido.php'; ?>
                          </tr>
                      <?php
                        }
                      } catch (PDOException $e) {
                        echo 'Hay problemas con la conexión: ' . $e->getMessage();
                      }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
    <?php include 'modales/contenido.php'; ?>
  </div>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#tablaContenido').DataTable({
        language: {
          decimal: "",
          emptyTable: "No hay información",
          info: "Mostrando _START_ a _END_ de _TOTAL_ entradas",
          infoEmpty: "Mostrando 0 a 0 de 0 entradas",
          infoFiltered: "(filtrado de _MAX_ total entradas)",
          thousands: ",",
          lengthMenu: "Mostrar _MENU_ entradas",
          loadingRecords: "Cargando...",
          processing: "Procesando...",
          search: "Buscar:",
          zeroRecords: "Sin resultados encontrados",
          paginate: {
            first: "Primero",
            last: "Último",
            next: "Siguiente",
            previous: "Anterior"
          }
        }
      });
    });
  </script>

</body>

</html>