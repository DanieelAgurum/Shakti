<!-- Modal Editar perfil -->
<div class="modal fade custom-config-modal" id="editarPerfilModal" tabindex="-1" aria-labelledby="editarPerfilLabel" aria-hidden="true">
    <div class="modal-dialog perfilmodal" role="document">
        <div class="modal-content">
            <form method="post" id="actualizarForm" action="../../Controlador/UsuariasControlador.php" enctype="multipart/form-data" autocomplete="off">
                <div class="modal-header">
                    <h4 class="modal-title text-center" id="editarPerfilLabel"><strong>Editar perfil</strong></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div id="formErrorAlert" class="alert alert-danger d-none" role="alert"></div>

                        <div class="row">
                            <!-- Columna izquierda -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nombreN" class="form-label">Nombre:</label>
                                    <input type="text" id="nombreN" class="form-control" name="nombreN"
                                        value="<?= htmlspecialchars($_SESSION['nombre'] ?? '') ?>">
                                    <small class="error" id="errorNombreN"></small>
                                </div>

                                <div class="mb-3">
                                    <label for="apellidosN" class="form-label">Apellidos:</label>
                                    <input type="text" id="apellidosN" class="form-control" name="apellidosN"
                                        value="<?= htmlspecialchars($_SESSION['apellidos'] ?? '') ?>">
                                    <small class="error" id="errorApellidosN"></small>
                                </div>

                                <div class="mb-3">
                                    <label for="nicknameN" class="form-label">Nombre de usuaria:</label>
                                    <input type="text" id="nicknameN" class="form-control" name="nicknameN"
                                        value="<?= htmlspecialchars($_SESSION['nickname'] ?? '') ?>">
                                    <small class="error" id="errorNicknameN"></small>
                                </div>
                            </div>

                            <!-- Columna derecha -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="telefono" class="form-label">Teléfono:</label>
                                    <input type="tel" id="telefono" class="form-control" name="telefono"
                                        value="<?= htmlspecialchars($_SESSION['telefono'] ?? '') ?>">
                                    <small class="error" id="errorTelefono"></small>
                                </div>

                                <div class="mb-3">
                                    <label for="direccion" class="form-label">Dirección:</label>
                                    <input type="text" id="direccion" class="form-control" name="direccion"
                                        value="<?= htmlspecialchars($_SESSION['direccion'] ?? '') ?>">
                                    <small class="error" id="errorDireccion"></small>
                                </div>

                                <div class="mb-3">
                                    <label for="fecha_nac" class="form-label">Fecha de nacimiento:</label>
                                    <input type="date" id="fecha_nac" class="form-control" name="fecha_nac"
                                        value="<?= htmlspecialchars($_SESSION['fecha_nacimiento'] ?? '') ?>">
                                    <small class="error" id="errorFecha_nac"></small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="descripcion" class="form-label">Descripción</label>
                                    <textarea class="form-control" name="descripcion" id="descripcion" rows="5"><?= htmlspecialchars($_SESSION['descripcion'] ?? '') ?></textarea>
                                    <small class="error" id="errorDescripcion"></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-banner btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Cancelar</button>
                    <input type="hidden" value="2" name="opcion">
                    <button type="submit" name="completar" value="actualizarDatos" class="btn btn-banner">
                        <i class="bi bi-check2-circle"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Completar perfil -->
<div class="modal fade custom-config-modal" id="completarPerfilModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-center" id="addNewLabel"><strong>Completar perfil</strong></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="../../Controlador/CompletarPerfilCtrl.php" enctype="multipart/form-data">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" value="option1" checked>
                        <label class="form-check-label" for="exampleRadios1">
                            Declaro que la información y documentación proporcionada es verdadera y autorizo su validación.
                        </label>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">Identificación oficial con fotografía</label>
                        <div class="input-group">
                            <input type="file" class="form-control" name="id_oficial" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04" aria-label="Upload" accept=".pdf">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">Título profesional:</label>
                        <div class="input-group">
                            <input type="file" class="form-control" name="documento1" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04" aria-label="Upload" accept=".pdf">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">Cédula profesional o matrícula:</label>
                        <div class="input-group">
                            <input type="file" class="form-control" name="documento2" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04" aria-label="Upload" accept=".pdf">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">Certificados de diplomados o posgrados:</label>
                        <div class="input-group">
                            <input type="file" class="form-control" name="documento3" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04" aria-label="Upload" accept=".pdf">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">Constancias de práctica o experiencia laboral:</label>
                        <div class="input-group">
                            <input type="file" class="form-control" name="documento4" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04" aria-label="Upload" accept=".pdf">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Cancelar</button>
                        <input type="hidden" value="1" name="opcion">
                        <button type="submit" name="completar" value="completarPerfilModal" class="btn btn-outline-success"><i class="bi bi-check2-circle"></i> Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para activar/desactivar cuenta -->
<div class="modal fade" id="cambiarEstado_<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title"><?php echo $row['estatus'] == 1 ? 'Desactivar' : 'Activar'; ?> cuenta de especialista</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
                <h5 class="text-center"><strong><?php echo $row['nombre'] . ' ' . $row['apellidos']; ?></strong>
                </h5>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa-solid fa-ban"></i> Cancelar</button>
                <form action="../../Controlador/especialistaCtrl.php" method="post">
                    <input type="hidden" name="opcion" value="1">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <input type="hidden" name="nuevo_estado" value="<?php echo $row['estatus'] == 1 ? 0 : 1; ?>">
                    <button type="submit" class="btn <?php echo $row['estatus'] == 1 ? 'btn-danger' : 'btn-success'; ?>">
                        <i class="fa-solid fa-check"></i> Confirmar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!--Eliminar cuenta-->
<div class="modal fade" id="eliminarE_<?php echo $row['id']; ?>" tabindex=" -1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Eliminar cuenta de especialista</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
                <h2 class="text-center"> <?php echo $row['nombre']; ?> <?php echo $row['apellidos']; ?></h2>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa-solid fa-ban"></i> Cancelar</button>
                <form action="../../Controlador/especialistaCtrl.php" method="post">
                    <input type="hidden" name="opcion" value="2">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <button type="submit" class="btn btn-warning"><i class="fa-sharp fa-solid fa-eraser"></i> Confirmar</button>
                </form>
            </div>

        </div>
    </div>
</div>

<!-- Modal para editar foto -->
<div class="modal fade custom-config-modal" id="editarFotoModal" tabindex="-1" aria-labelledby="editarFotoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="fotoForm" action="../../Controlador/UsuariasControlador.php" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarFotoLabel">Cambiar foto de perfil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body text-center">
                    <!-- Previsualización de la imagen -->
                    <img id="imagenPreview" src="<?php echo $fotoSrc; ?>" alt="Foto actual" class="rounded mb-3" style="max-width:50%;">

                    <input type="file" id="fotoInput" name="nuevaFoto" accept="image/*" class="d-none">
                    <label for="fotoInput" class="btn btn-outline-light">
                        <i class="bi bi-upload"></i> Seleccionar nueva foto
                    </label>

                    <input type="hidden" name="opcion" value="4">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-banner btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-banner">
                        <i class="bi bi-check2-circle"></i> Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal eliminar foto-->
<div class="modal fade custom-config-modal" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="../../Controlador/UsuariasControlador.php" method="post" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="confirmDeleteModalLabel"><i class="bi bi-exclamation-triangle-fill"></i> Confirmar eliminación</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body text-center text-white">
                    <p>¿Estás seguro de que quieres <strong>eliminar tu foto de perfil</strong>?</p>
                    <p class="text-muted"><small class="text-white">Esta acción no se puede deshacer.</small></p>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-banner btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Cancelar</button>
                    <input type="hidden" name="opcion" value="5">
                    <button type="submit" class="btn btn-banner-eliminar"><i class="bi bi-eraser-fill"></i> Eliminar foto</button>
                </div>
            </div>
        </form>
    </div>
</div>