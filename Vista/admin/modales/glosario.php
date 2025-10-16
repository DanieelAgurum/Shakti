<!-- Modal para agregar -->
<div class="modal fade custom-config-modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Agregar Término</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div id="mensaje"></div>
                <form class="row g-3" id="agregarGlosario">
                    <input type="hidden" name="opcion" value="1">
                    <div class="col-12">
                        <label class="form-label" for="icono">Ícono (<a href="https://fontawesome.com/search" target="_blank" rel="noopener noreferrer">clic</a>)</label>
                        <input type="text" class="form-control" name="icono" id="icono">
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="titulo">Título</label>
                        <input type="text" class="form-control" name="titulo" id="titulo">
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="descripcion">Descripción</label>
                        <textarea class="form-control" name="descripcion" id="descripcion"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="enviarDatos(event)">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para modificar -->
<div class="modal fade custom-config-modal" id="modificarModal" tabindex="-1" aria-labelledby="modificarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modificarModalLabel">Modificar Término</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div id="mensajeModificar"></div>
                <form class="row g-3" id="formModificarGlosario">
                    <input type="hidden" name="id_glosario" id="idGlosario">
                    <input type="hidden" name="opcion" value="2">
                    <div class="col-12">
                        <label class="form-label" for="iconoModificado">Ícono</label>
                        <input type="text" class="form-control" name="iconoModificado" id="iconoModificado">
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="tituloModificado">Título</label>
                        <input type="text" class="form-control" name="tituloModificado" id="tituloModificado">
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="descripcionModificado">Descripción</label>
                        <textarea class="form-control" name="descripcionModificado" id="descripcionModificado"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" onclick="enviarDatosModificados(event)" class="btn btn-primary">Modificar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de eliminación -->
<div class="modal fade" id="miModal" tabindex="-1" aria-labelledby="miModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">¿Deseas eliminar el término <span id="nombreGlosarioModal" class="text-danger fw-bold"></span>?</h5>
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