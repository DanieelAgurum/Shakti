<!-- Modal Agregar contenido legal -->
<div class="modal fade" id="agregarLegal" tabindex="-1" aria-labelledby="agregarLegalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title w-100 text-center" id="agregarLegalLabel"><strong>Agregar contenido legal</strong></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="../../Controlador/libre_seguraCtrl.php" enctype="multipart/form-data">

                    <div class="mb-3">
                        <label for="portada" class="form-label">Portada:</label>
                        <input type="file" class="form-control" name="portada" id="portada" accept=".jpg" required>
                    </div>

                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título del documento:</label>
                        <input type="text" class="form-control" name="titulo" id="titulo" required>
                    </div>

                    <div class="mb-3">
                        <label for="documento" class="form-label">Archivo (PDF, máximo 10 MB):</label>
                        <input type="file" class="form-control" name="documento" id="documento" accept=".pdf" required>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción breve:</label>
                        <textarea class="form-control" name="descripcion" id="descripcion" rows="3" required></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg"></i> Cancelar
                        </button>
                        <input type="hidden" name="opcion" value="1">
                        <button type="submit" name="completar" value="agregarContenidoLegal" class="btn btn-outline-success">
                            <i class="bi bi-check2-circle"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar contenido legal -->
<div class="modal fade" id="editarLegal_<?php echo $row['id_legal']; ?>" tabindex="-1" aria-labelledby="editarLegalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title w-100 text-center" id="editarLegalLabel"><strong>Agregar contenido legal</strong></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="../../Controlador/libre_seguraCtrl.php" enctype="multipart/form-data">

                    <div class="mb-3">
                        <label for="portada" class="form-label">Portada:</label>
                        <input type="file" class="form-control" name="portada" id="portada" accept=".jpg">
                    </div>

                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título del documento:</label>
                        <input type="text" class="form-control" name="titulo" id="titulo" value="<?= htmlspecialchars($row['titulo'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label for="documento" class="form-label">Archivo (PDF, máximo 10 MB):</label>
                        <input type="file" class="form-control" name="documento" id="documento" accept=".pdf">
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción breve:</label>
                        <textarea class="form-control" name="descripcion" id="descripcion" rows="3"><?= htmlspecialchars($row['descripcion'] ?? '') ?></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg"></i> Cancelar
                        </button>
                        <input type="hidden" name="opcion" value="2">
                        <input type="hidden" name="id_legal" value="<?php echo $row['id_legal']; ?>">
                        <button type="submit" name="completar" value="editarContenidoLegal" class="btn btn-outline-success">
                            <i class="bi bi-check2-circle"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!--Eliminar contenido legal-->
<div class="modal fade" id="eliminarL_<?php echo $row['id_legal']; ?>" tabindex=" -1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Eliminar contenido legal</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
                <h2 class="text-center"> <?php echo $row['titulo']; ?></h2>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa-solid fa-ban"></i> Cancelar</button>
                <form action="../../Controlador/libre_seguraCtrl.php" method="post">
                    <input type="hidden" name="opcion" value="3">
                    <input type="hidden" name="id_legal" value="<?php echo $row['id_legal']; ?>">
                    <button type="submit" class="btn btn-warning"><i class="fa-sharp fa-solid fa-eraser"></i> Confirmar</button>
                </form>
            </div>

        </div>
    </div>
</div>