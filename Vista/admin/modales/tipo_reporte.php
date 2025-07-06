<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3">
                    <div class="col-auto">
                        <label for="staticEmail2" class="visually-hidden">Nombre (Tipo de reporte)</label>
                        <input type="text" readonly class="form-control-plaintext" id="staticEmail2" value="email@example.com">
                    </div>
                    <div class="col-auto">
                        <label for="inputPassword2" class="visually-hidden"></label>
                        <input type="password" class="form-control" id="inputPassword2" placeholder="Password">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
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