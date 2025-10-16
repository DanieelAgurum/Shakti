<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/obtenerLink/obtenerLink.php';
$urlBase = getBaseUrl();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['correo']) || $_SESSION['id_rol'] != 3) {
    header("Location: {$urlBase}");
    exit;
}
?>
<div class="modal fade custom-config-modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="POST" id="agregarOrganizacion" enctype="multipart/form-data">
                <input type="hidden" name="opcion" value="1">

                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Organizaciones</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div class="modal-body">
                    <div id="mensaje"></div>

                    <div class="col-12 mb-2">
                        <label for="nombre" class="form-label">Organización</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingresa el nombre de la organización" required>
                    </div>

                    <div class="col-12 mb-2">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="4" placeholder="Descripción de la organización" required></textarea>
                    </div>

                    <div class="col-12 mb-2">
                        <label for="numero" class="form-label">Número de la organización</label>
                        <input type="text" class="form-control" id="numero" name="numero" placeholder="Ingresa el número de la organización" required>
                    </div>

                    <div class="col-12 mb-2">
                        <label for="imagen" class="form-label">Imagen de la organización</label>
                        <input type="file" name="imagen" id="imagen" class="form-control" accept="image/*" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" onclick="enviarDatos(event)" class="btn btn-primary">Guardar</button>
                </div>
            </form>

        </div>
    </div>
</div>


<div class="modal fade custom-config-modal" id="modificarModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Modificar Organización</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="mensajeModificados">
                </div>
                
                <form class="row g-3" id="modificarOrganizacion">
                    <input type="hidden" name="opcion" value="2">
                    <input type="hidden" id="id_modificar" name="id" value="">
                    <div class="col-12">
                        <label for="nombre_modificar" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre_modificar" name="nombre" placeholder="Ingresa el nombre" required>
                    </div>
                    <div class="col-12">
                        <label for="descripcion_modificar" class="form-label">Descripcion</label>
                        <textarea class="form-control" id="descripcion_modificar" name="descripcion" rows="4" placeholder="Coloca la descripcion" required></textarea>
                    </div>
                    <div class="col-12">
                        <label for="numero_modificar" class="form-label">Numero</label>
                        <input type="text" class="form-control" id="numero_modificar" name="numero" placeholder="Ingresa numero" required>
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

<div class="modal fade" id="miModal" tabindex="-1" aria-labelledby="miModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">¿Deseas eliminar a <span id="nombreEliminar" class="text-danger fw-bold"></span>?</h5>
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