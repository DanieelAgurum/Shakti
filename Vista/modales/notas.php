
<!-- Modal para agregar una nueva nota -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Nueva nota</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="../../Controlador/notasCtrl.php" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">Título:</label>
                        <input type="text" name="titulo" class="form-control" id="recipient-name">
                    </div>
                    <div class="mb-3">
                        <label for="nota" class="col-form-label">Mensaje:</label>
                        <textarea class="form-control" name="nota" id="nota"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i> Cancelar
                    </button>
                    <input type="hidden" name="opcion" value="1">
                    <button type="submit" class="btn btn-outline-success">
                        <i class="bi bi-check2-circle"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para mostrar la nota -->
<div class="modal fade" id="notaModal<?= $index ?>" tabindex="-1" aria-labelledby="notaModalLabel<?= $index ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notaModalLabel<?= $index ?>">Título: <?= htmlspecialchars($nota['titulo']) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p>
                    <?= nl2br(htmlspecialchars($nota['nota'])) ?>
                </p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-danger" data-bs-target="#eliminarNotaModal<?= $index ?>" data-bs-toggle="modal" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i> Eliminar
                </button>
                <button class="btn btn-outline-primary" data-bs-target="#editarNotaModal<?= $index ?>" data-bs-toggle="modal" data-bs-dismiss="modal">
                    <i class="bi bi-pencil-fill"></i> Editar nota
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar la nota específica -->
<div class="modal fade" id="editarNotaModal<?= $index ?>" tabindex="-1" aria-labelledby="editarNotaModalLabel<?= $index ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="../../Controlador/notasCtrl.php" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarNotaModalLabel<?= $index ?>">Editar nota</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="titulo<?= $index ?>" class="col-form-label">Título:</label>
                        <input type="text" class="form-control" name="titulo" id="titulo<?= $index ?>" value="<?= htmlspecialchars($nota['titulo']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="nota<?= $index ?>" class="col-form-label">Mensaje:</label>
                        <textarea class="form-control" name="nota" id="nota<?= $index ?>"><?= htmlspecialchars($nota['nota']) ?></textarea>
                    </div>
                    <input type="hidden" name="id" value="<?= $nota['id_nota'] ?>">
                    <input type="hidden" name="opcion" value="2">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Cancelar</button>
                    <button type="submit" class="btn btn-outline-success"><i class="bi bi-check2-circle"></i> Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para eliminar la nota específica -->
<div class="modal fade" id="eliminarNotaModal<?= $index ?>" tabindex="-1" aria-labelledby="editarNotaModalLabel<?= $index ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="../../Controlador/notasCtrl.php" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="eliminarNotaModalLabel<?= $index ?>">Eliminar nota: <?= htmlspecialchars($nota['titulo']) ?> </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p class="text-center"> <?= htmlspecialchars($nota['nota']) ?></p>
                    <input type="hidden" name="id" value="<?= $nota['id_nota'] ?>">
                    <input type="hidden" name="opcion" value="3">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Cancelar</button>
                    <button type="submit" class="btn btn-outline-danger"><i class="bi bi-eraser-fill"></i> Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>
