<!-- Modal para agregar -->
<div class="modal fade custom-config-modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Pregunta Frecuente</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div id="mensaje"></div>
                <form class="row g-3" id="agregarPreguntaFrecuente">
                    <input type="hidden" name="opcion" value="1">

                    <div class="col-12">
                        <label for="nombrePregunta" class="form-label">Pregunta</label>
                        <input type="text" class="form-control" id="nombrePregunta" name="pregunta" placeholder="Ingresa la pregunta" required>
                    </div>
                    <div class="col-12">
                        <label for="textoPregunta" class="form-label">Respuesta</label>
                        <textarea class="form-control" id="textoPregunta" name="respuesta" rows="4" placeholder="Escribe la respuesta aquí" required></textarea>
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
<!-- Modifica -->
<div class="modal fade custom-config-modal" id="modificarModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Pregunta Frecuente</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="mensajeModificados">
                </div>
                <div id="mensaje"></div>
                <form class="row g-3" id="formModificarTipoReporte">
                    <input type="hidden" name="opcion" value="2">
                    <input type="hidden" id="id" name="id" value="">
                    <div class="col-12">
                        <label for="nombrePreguntaModificar" class="form-label">Pregunta</label>
                        <input type="text" class="form-control" id="nombrePreguntaModificar" name="pregunta" placeholder="Ingresa la pregunta" required>
                    </div>
                    <div class="col-12">
                        <label for="textoPreguntaModificar" class="form-label">Respuesta</label>
                        <textarea class="form-control" id="textoPreguntaModificar" name="respuesta" rows="4" placeholder="Escribe la respuesta aquí" required></textarea>
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