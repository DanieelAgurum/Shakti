<!-- Modal Editar perfil -->
<div class="modal fade" id="editarPerfilModal" tabindex="-1" aria-labelledby="addNewLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-center" id="addNewLabel"><strong>Editar perfil</strong></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div id="formErrorAlert" class="alert alert-danger d-none" role="alert"></div>
                    <form method="post" id="actualizarForm" action="../../Controlador/UsuariasControlador.php" enctype="multipart/form-data">

                        <!-- Foto -->
                        <div class="row form-group mb-2">
                            <div class="col-sm-2">
                                <label class="control-label">Foto:</label>
                            </div>
                            <div class="col-sm-10">
                                <input type="file" class="form-control" name="foto" accept="image/*"
                                    value="<?= htmlspecialchars($_SESSION['foto'] ?? '') ?>">
                            </div>
                        </div>

                        <!-- Nombre -->
                        <div class="row form-group mb-2">
                            <div class="col-sm-2">
                                <label class="control-label">Nombre:</label>
                            </div>
                            <div class="col-sm-10">
                                <input type="text" id="nombreN" class="form-control" name="nombreN"
                                    value="<?= htmlspecialchars($_SESSION['nombre'] ?? '') ?>">
                                <small class="error" id="errorNombreN"></small>
                            </div>
                        </div>

                        <!-- Apellidos -->
                        <div class="row form-group mb-2">
                            <div class="col-sm-2">
                                <label class="control-label">Apellidos:</label>
                            </div>
                            <div class="col-sm-10">
                                <input type="text" id="apellidosN" class="form-control" name="apellidosN"
                                    value="<?= htmlspecialchars($_SESSION['apellidos'] ?? '') ?>">
                                <small class="error" id="errorApellidosN"></small>
                            </div>
                        </div>

                        <!-- Nickname -->
                        <div class="row form-group mb-2">
                            <div class="col-sm-2">
                                <label class="control-label">Nombre de usuaria:</label>
                            </div>
                            <div class="col-sm-10">
                                <input type="text" id="nicknameN" class="form-control" name="nicknameN"
                                    value="<?= htmlspecialchars($_SESSION['nickname'] ?? '') ?>">
                                <small class="error" id="errorNicknameN"></small>
                            </div>
                        </div>

                        <!-- Contraseña -->
                        <div class="row form-group mb-2">
                            <div class="col-sm-2">
                                <label class="control-label">Nueva contraseña:</label>
                            </div>
                            <div class="col-sm-10">
                                <input type="password" id="contraseñaN" class="form-control" name="contraseñaN">
                                <small class="error" id="errorContraseñaN"></small>
                            </div>
                        </div>

                        <!-- Teléfono -->
                        <div class="row form-group mb-2">
                            <div class="col-sm-2">
                                <label class="control-label">Teléfono:</label>
                            </div>
                            <div class="col-sm-10">
                                <input type="number" id="telefono" class="form-control" name="telefono"
                                    value="<?= htmlspecialchars($_SESSION['telefono'] ?? '') ?>">
                                <small class="error" id="errorTelefono"></small>
                            </div>
                        </div>

                        <!-- Dirección -->
                        <div class="row form-group mb-2">
                            <div class="col-sm-2">
                                <label class="control-label">Dirección:</label>
                            </div>
                            <div class="col-sm-10">
                                <input type="text" id="direccion" class="form-control" name="direccion"
                                    value="<?= htmlspecialchars($_SESSION['direccion'] ?? '') ?>">
                                <small class="error" id="errorDireccion"></small>
                            </div>
                        </div>

                        <!-- Fecha de nacimiento -->
                        <div class="row form-group mb-2">
                            <div class="col-sm-2">
                                <label for="fecha_nac" class="form-label me-2">Fecha de nacimiento</label>
                            </div>
                            <div class="col-sm-10">
                                <input type="date" class="form-control" id="fecha_nac" name="fecha_nac"
                                    value="<?= htmlspecialchars($_SESSION['fecha_nacimiento'] ?? '') ?>">
                                <small class="error" id="errorFecha_nac"></small>
                            </div>
                        </div>

                        <!-- Descripción -->
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" name="descripcion" id="descripcion" rows="3"><?= htmlspecialchars($_SESSION['descripcion'] ?? '') ?></textarea>
                            <small class="error" id="errorDescripcion"></small>
                        </div>

                        <!-- Botones -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Cancelar</button>
                            <input type="hidden" value="2" name="opcion">
                            <button type="submit" name="completar" value="actualizarDatos" class="btn btn-outline-success">
                                <i class="bi bi-check2-circle"></i> Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal Completar perfil -->
<div class="modal fade" id="completarPerfilModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                            <input type="file" class="form-control" name="id_oficial" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04" aria-label="Upload">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">Título profesional:</label>
                        <div class="input-group">
                            <input type="file" class="form-control" name="documento1" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04" aria-label="Upload">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">Cédula profesional o matrícula:</label>
                        <div class="input-group">
                            <input type="file" class="form-control" name="documento2" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04" aria-label="Upload">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">Certificados de diplomados o posgrados:</label>
                        <div class="input-group">
                            <input type="file" class="form-control" name="documento3" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04" aria-label="Upload">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">Constancias de práctica o experiencia laboral:</label>
                        <div class="input-group">
                            <input type="file" class="form-control" name="documento4" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04" aria-label="Upload">
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
