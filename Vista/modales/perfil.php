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
                    <form method="post" action="../../control/productoCtrl.php" enctype="multipart/form-data">
                        <div class="row form-group mb-2">
                            <div class="col-sm-2">
                                <label class="control-label">Foto:</label>
                            </div>
                            <div class="col-sm-10">
                                <input type="file" class="form-control" name="foto" required>
                            </div>
                        </div>
                        <div class="row form-group mb-2">
                            <div class="col-sm-2">
                                <label class="control-label">Nombre:</label>
                            </div>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="nombre" required>
                            </div>
                        </div>
                        <div class="row form-group mb-2">
                            <div class="col-sm-2">
                                <label class="control-label">Apellidos:</label>
                            </div>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="apellidos" required>
                            </div>
                        </div>
                        <div class="row form-group mb-2">
                            <div class="col-sm-2">
                                <label class="control-label">Nombre de usuaria:</label>
                            </div>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="nickname" required>
                            </div>
                        </div>
                        <div class="row form-group mb-2">
                            <div class="col-sm-2">
                                <label class="control-label">Nueva contraseña:</label>
                            </div>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" name="contraseña" required>
                            </div>
                        </div>
                        <div class="row form-group mb-2">
                            <div class="col-sm-2">
                                <label class="control-label">Teléfono:</label>
                            </div>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="telefono" required>
                            </div>
                        </div>
                        <div class="row form-group mb-2">
                            <div class="col-sm-2">
                                <label class="control-label">Dirección:</label>
                            </div>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="direccion" required>
                            </div>
                        </div>
                        <div class="row form-group mb-2">
                            <div class="col-sm-2">
                                <label for="fecha_nac" class="form-label me-2">Fecha de nacimiento</label>
                            </div>
                            <div class="col-sm-10">
                                <input type="date" class="form-control" id="fecha_nac" name="fecha_nac" />
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Send message</button>
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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <input type="hidden" value="1" name="opcion">
                        <button type="submit" name="completar" value="completarPerfilModal" class="btn btn-primary"><i class="fa-solid fa-circle-check"></i> Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>