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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organizaciones - Shakti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


</head>

<body class="sb-nav-fixed">
    <?php
    include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/admin/navbar.php';
    ?>
    <div id="layoutSidenav">
        <?php
        include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/admin/lateral.php';
        ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4 mb-5">
                    <h1 class="mt-4"></h1>
                    <div class="container">
                        <h1 class="page-header text-center"> <strong> Organizaciones </strong></h1>
                        <div class="row">
                            <div class="col-sm-12 d-flex justify-content-start">
                                <button type="button" style="margin-bottom: 8px;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevaOrganizacionModal">
                                    Nuevo <i class="fa-solid fa-circle-plus"></i>
                                </button>
                            </div>

                            <div class="col-sm-12">
                                <?php if (isset($_GET['status'])): ?>
                                    <?php
                                    $mensajes = [
                                        'eliminada' => 'La organización fue eliminada correctamente.',
                                        'estatus_actualizado' => 'El estado de la organización fue actualizado correctamente.',
                                        'agregada' => 'La organización fue agregada correctamente.', // Nuevo mensaje
                                        'error_activar' => 'Error al activar la organización.',
                                        'error_eliminar' => 'Error al eliminar la organización.',
                                        'error_estatus' => 'Error al cambiar el estado de la organización.',
                                        'error_agregar' => 'Error al agregar la organización.', // Nuevo mensaje
                                    ];
                                    $clase = (in_array($_GET['status'], ['agregada', 'estatus_actualizado', 'eliminada', 'activada'])) ? 'success' : 'danger';
                                    ?>
                                    <div class="alert alert-<?php echo $clase; ?> alert-dismissible fade show" role="alert">
                                        <?php echo $mensajes[$_GET['status']]; ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <table class="table table-bordered table-striped" id="MiAgenda" style="margin-top:20px;">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Imagen</th>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Numero</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    include_once '../../Modelo/conexion.php';
                                    $database = new ConectarDB();
                                    $db = $database->open();

                                    try {
                                        $sql = "SELECT id, nombre, descripcion, imagen, estatus FROM organizaciones";
                                        foreach ($db->query($sql) as $row) {
                                    ?>
                                            <tr>
                                                <td><?php echo $row['id']; ?></td>
                                                <td>
                                                    <?php if (!empty($row['imagen'])): ?>
                                                        <img src="data:image/*;base64,<?php echo base64_encode($row['imagen']); ?>" width="60px" height="60px" alt="">
                                                    <?php else: ?>
                                                        <img src="https://cdn1.iconfinder.com/data/icons/business-1218/512/business_organization-512.png" width="60px" height="60px" alt="">
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo $row['nombre']; ?></td>
                                                <td><?php echo $row['descripcion']; ?></td>
                                                <td>
                                                    <a href="#edit_<?php echo $row['id']; ?>" class="btn btn-primary btn-sm" data-bs-toggle="modal">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.121l6.813-6.814z"/>
                                                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                                        </svg>
                                                        Editar
                                                    </a>
                                                    <a href="#delete_<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" data-bs-toggle="modal">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                                            <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
                                                        </svg>
                                                        Eliminar
                                                    </a>
                                                </td>
                                            </tr>
                                    <?php
                                            include '../modales/perfil_organizacion.php'; // Asegúrate de que este modal incluya los modales de editar y eliminar
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
            </main>
        </div>
    </div>

    <div class="modal fade" id="nuevaOrganizacionModal" tabindex="-1" aria-labelledby="nuevaOrganizacionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="nuevaOrganizacionModalLabel">Nueva Organización</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formNuevaOrganizacion" action="../Controlador/organizacionesCtrl.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="imagenOrganizacion" class="form-label">Imagen</label>
                            <input class="form-control" type="file" id="imagenOrganizacion" name="imagen" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label for="nombreOrganizacion" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombreOrganizacion" name="nombre" placeholder="Ingresa el nombre de la organización" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcionOrganizacion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcionOrganizacion" name="descripcion" rows="3" placeholder="Escribe la descripción aquí" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="numeroOrganizacion" class="form-label">Número</label>
                            <input type="text" class="form-control" id="numeroOrganizacion" name="numero" placeholder="Ingresa el número de contacto">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <script>
        $(document).ready(function() {
            // Inicialización de DataTables
            $('#MiAgenda').DataTable({
                language: {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                    "infoEmpty": "Mostrando 0 a 0 de 0 Entradas",
                    "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ Entradas",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "Sin resultados encontrados",
                    "paginate": {
                        "first": "Primero",
                        "last": "Ultimo",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },
            });

            // Ajustar el título H1 para que coincida con "Organizaciones" (confirmado en el código original)
            $('.page-header.text-center strong').text('Organizaciones');

            // Event listener para limpiar el formulario cuando el modal de "Nueva Organización" se muestra
            var nuevaOrganizacionModal = document.getElementById('nuevaOrganizacionModal');
            if (nuevaOrganizacionModal) { // Asegurarse de que el modal exista
                nuevaOrganizacionModal.addEventListener('show.bs.modal', function (event) {
                    document.getElementById('formNuevaOrganizacion').reset();
                });
            }
        });
    </script>
</body>

</html>