<!-- Modal para agregar -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Tipo de Reporte</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="mensaje">
                </div>
                <form class="row g-3" id="agregarTipoReporte">
                    <input type="hidden" name="opcion" value="1">
                    <div class="col-12">
                        <label for="staticEmail2" class="form-label">Nombre (Tipo de reporte)</label>
                        <input type="text" class="form-control" name="nombre" value="">
                    </div>
                    <div class="col-12">
                        <label for="selectTipo" class="form-label">Seleccione tipo</label>
                        <select class="form-select" id="selectTipo" name="tipo">
                            <option selected disabled>Elige una opción</option>
                            <option value="1">Contenido</option>
                            <option value="2">Usuario</option>
                            <option value="3">Posts</option>
                            <option value="4">Comentarios</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" onclick="enviarDatos()" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modificar datos -->
<div class="modal fade" id="modificarModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Tipo de Reporte</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="mensajeModificados">
                </div>
                <form class="row g-3" id="formModificarTipoReporte">
                    <input type="hidden" name="id_tipo_reporte" id="id_tipo_reporte">
                    <input type="hidden" name="opcion" value="2">
                    <div class="col-12">
                        <label class="form-label">Nombre (Tipo de reporte)</label>
                        <input type="text" class="form-control" name="nombreModificado" id="nombreModificado">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Seleccione tipo</label>
                        <select class="form-select" id="tipoModificado" name="tipoModificado">
                            <option selected disabled>Elige una opción</option>
                            <option value="1">Contenido</option>
                            <option value="2">Usuaria</option>
                            <option value="3">Posts</option>
                            <option value="4">Comentarios</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" onclick="enviarDatosModificados()" class="btn btn-primary">Modificar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="miModal" tabindex="-1" aria-labelledby="miModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">¿Deseas eliminar a <span id="nombreUsuariaModal" class="text-danger fw-bold"></span>?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
                <p>Esta acción no se puede deshacer.</p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a id="btnEliminarLink" href="#" class="btn btn-danger">Eliminar</a>
            </div>

        </div>
    </div>
</div>