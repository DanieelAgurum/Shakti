<!-- AGREGAR CONTENIDO -->
<div class="modal fade" id="agregarContenido" tabindex="-1" aria-labelledby="agregarContenidoLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title w-100 text-center"><strong>Agregar nuevo contenido</strong></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
                <form method="post" action="../../Controlador/contenidoCtrl.php" enctype="multipart/form-data">

                    <!-- MINIATURA -->
                    <div class="mb-3">
                        <label for="thumbnail" class="form-label fw-bold">Miniatura (imagen portada):</label>
                        <input type="file" class="form-control" name="thumbnail" accept="image/*" required>
                        <div class="preview-thumbnail mt-2"></div>
                        <small class="text-muted">Formato permitido: JPG, PNG, GIF</small>
                    </div>

                    <!-- TÍTULO -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Título:</label>
                        <input type="text" class="form-control" name="titulo" maxlength="255" required>
                    </div>

                    <!-- DESCRIPCIÓN -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Descripción breve:</label>
                        <textarea class="form-control" name="descripcion" rows="3" maxlength="500" required></textarea>
                    </div>

                    <!-- TIPO -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tipo de contenido:</label>
                        <select class="form-select tipo-select" name="tipo" id="tipoAgregar" data-id="agregar" required>
                            <option value="" disabled selected>Selecciona un tipo</option>
                            <option value="infografia">Infografía</option>
                            <option value="articulo">Artículo</option>
                            <option value="video">Video</option>
                        </select>
                    </div>

                    <!-- CATEGORIA -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Categoria:</label>
                        <select class="form-select categoria-select" name="categoria" id="categoriaAgregar" data-id="agregar" required>
                            <option value="" disabled selected>Selecciona una categoría</option>
                            <option value="Ansiedad">Ansiedad</option>
                            <option value="Depresión">Depresión</option>
                            <option value="Estrés">Estrés</option>
                        </select>
                    </div>

                    <!-- Estado -->
                    <input type="hidden" name="estado" value="1">

                    <!-- INFOGRAFÍA -->
                    <div class="tipo-seccion" id="seccion-infografia" style="display:none;">
                        <hr>
                        <h5 class="fw-bold text-primary">Infografía</h5>
                        <input type="file" class="form-control" name="archivo" accept=".pdf">
                        <div class="preview-infografia mt-2 text-center"></div>
                        <small class="text-muted">Formatos permitidos: PDF.</small>
                    </div>

                    <!-- ARTÍCULO -->
                    <div class="tipo-seccion" id="seccion-articulo" style="display:none;">
                        <hr>
                        <h5 class="fw-bold text-primary">Artículo</h5>
                        <textarea id="cuerpo_agregar" class="form-control" name="cuerpo_html" rows="6" placeholder="Escribe el contenido del artículo..."></textarea>

                        <div class="mt-3">
                            <label class="form-label fw-bold">Imágenes del artículo (opcional):</label>

                            <div class="row g-3">
                                <div class="col-md-4 text-center">
                                    <input type="file" class="form-control" name="imagen1" accept="image/*">
                                    <div class="preview-imagen1 mt-2"></div>
                                </div>
                                <div class="col-md-4 text-center">
                                    <input type="file" class="form-control" name="imagen2" accept="image/*">
                                    <div class="preview-imagen2 mt-2"></div>
                                </div>
                                <div class="col-md-4 text-center">
                                    <input type="file" class="form-control" name="imagen3" accept="image/*">
                                    <div class="preview-imagen3 mt-2"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- VIDEO -->
                    <div class="tipo-seccion" id="seccion-video" style="display:none;">
                        <hr>
                        <h5 class="fw-bold text-primary">Video</h5>
                        <input type="url" class="form-control" name="url_contenido" placeholder="https://...">
                    </div>

                    <input type="hidden" name="opcion" value="1">

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-outline-success">
                            <i class="bi bi-check2-circle"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- EDITAR CONTENIDO -->
<div class="modal fade" id="editarContenido_<?php echo $row['id_contenido']; ?>" tabindex="-1" aria-labelledby="editarContenidoLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title w-100 text-center"><strong>Editar contenido</strong></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
                <form method="post" action="../../Controlador/contenidoCtrl.php" enctype="multipart/form-data">

                    <!-- MINIATURA -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Miniatura (imagen portada):</label>
                        <input type="file" class="form-control" name="thumbnail" accept="image/*">
                        <?php if (!empty($row['thumbnail'])): ?>
                            <div class="mt-2">
                                <small class="text-muted d-block">Actual:</small>
                                <img src="<?= $urlBase . 'uploads/thumbnails/' . basename($row['thumbnail']); ?>" class="rounded border shadow-sm" width="120" height="120" style="object-fit:cover;">
                                <input type="hidden" name="thumbnail_actual" value="<?= htmlspecialchars($row['thumbnail']); ?>">
                            </div>
                        <?php endif; ?>
                        <div class="preview-thumbnail mt-2"></div>
                    </div>

                    <!-- TÍTULO -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Título:</label>
                        <input type="text" class="form-control" name="titulo" value="<?= htmlspecialchars($row['titulo']); ?>" maxlength="255" required>
                    </div>

                    <!-- DESCRIPCIÓN -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Descripción breve:</label>
                        <textarea class="form-control" name="descripcion" rows="3" maxlength="500" required><?= htmlspecialchars($row['descripcion']); ?></textarea>
                    </div>

                    <!-- TIPO -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tipo de contenido:</label>
                        <select name="tipo_mostrado" class="form-select tipo-select" data-id="<?= $row['id_contenido']; ?>" disabled>
                            <option value="<?= $row['tipo']; ?>" selected><?= ucfirst($row['tipo']); ?></option>
                        </select>
                        <input type="hidden" name="tipo" value="<?= $row['tipo']; ?>" class="tipo-real">
                    </div>

                    <!-- CATEGORIA -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Categoria:</label>
                        <select class="form-select categoria-select" name="categoria">
                            <option value="" disabled>Selecciona una categoría</option>

                            <option value="Ansiedad" <?= ($row['categoria'] == 'Ansiedad') ? 'selected' : '' ?>>
                                Ansiedad
                            </option>

                            <option value="Depresión" <?= ($row['categoria'] == 'Depresión') ? 'selected' : '' ?>>
                                Depresión
                            </option>

                            <option value="Estrés" <?= ($row['categoria'] == 'Estrés') ? 'selected' : '' ?>>
                                Estrés
                            </option>
                        </select>
                    </div>

                    <!-- INFOGRAFÍA -->
                    <div class="tipo-seccion" id="editar-infografia-<?= $row['id_contenido']; ?>" style="display:none;">
                        <hr>
                        <label class="form-label fw-bold">Archivo de infografía (imagen o PDF):</label>
                        <input type="file" class="form-control" name="nuevo_archivo" accept=".pdf">
                    </div>

                    <!-- ARTÍCULO -->
                    <div class="tipo-seccion" id="editar-articulo-<?= $row['id_contenido']; ?>" style="display:none;">
                        <hr>
                        <h5 class="fw-bold text-primary">Artículo</h5>
                        <textarea id="cuerpo_editar_<?= $row['id_contenido']; ?>" class="form-control" name="cuerpo_html" rows="6"><?= htmlspecialchars($row['cuerpo_html'] ?? ''); ?></textarea>

                        <div class="mt-3">
                            <label class="form-label fw-bold">Imágenes del artículo:</label>

                            <div class="row g-3">
                                <!-- Imagen 1 -->
                                <div class="col-md-4 text-center">
                                    <input type="file" class="form-control" name="nueva_imagen1" accept="image/*">
                                    <?php if (!empty($row['imagen1'])): ?>
                                        <div class="mt-2">
                                            <small class="text-muted d-block">Actual:</small>
                                            <img src="data:image/jpeg;base64,<?= base64_encode($row['imagen1']); ?>"
                                                width="120" height="120"
                                                class="rounded border shadow-sm"
                                                style="object-fit:cover;">
                                            <input type="hidden" name="imagen1_actual" value="<?= base64_encode($row['imagen1']); ?>">
                                        </div>
                                    <?php endif; ?>
                                    <div class="preview-imagen1 mt-2"></div>
                                </div>

                                <!-- Imagen 2 -->
                                <div class="col-md-4 text-center">
                                    <input type="file" class="form-control" name="nueva_imagen2" accept="image/*">
                                    <?php if (!empty($row['imagen2'])): ?>
                                        <div class="mt-2">
                                            <small class="text-muted d-block">Actual:</small>
                                            <img src="data:image/jpeg;base64,<?= base64_encode($row['imagen2']); ?>"
                                                width="120" height="120"
                                                class="rounded border shadow-sm"
                                                style="object-fit:cover;">
                                            <input type="hidden" name="imagen2_actual" value="<?= base64_encode($row['imagen2']); ?>">
                                        </div>
                                    <?php endif; ?>
                                    <div class="preview-imagen2 mt-2"></div>
                                </div>

                                <!-- Imagen 3 -->
                                <div class="col-md-4 text-center">
                                    <input type="file" class="form-control" name="nueva_imagen3" accept="image/*">
                                    <?php if (!empty($row['imagen3'])): ?>
                                        <div class="mt-2">
                                            <small class="text-muted d-block">Actual:</small>
                                            <img src="data:image/jpeg;base64,<?= base64_encode($row['imagen3']); ?>"
                                                width="120" height="120"
                                                class="rounded border shadow-sm"
                                                style="object-fit:cover;">
                                            <input type="hidden" name="imagen3_actual" value="<?= base64_encode($row['imagen3']); ?>">
                                        </div>
                                    <?php endif; ?>
                                    <div class="preview-imagen3 mt-2"></div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- VIDEO -->
                    <div class="tipo-seccion" id="editar-video-<?= $row['id_contenido']; ?>" style="display:none;">
                        <hr>
                        <label class="form-label fw-bold">URL del video:</label>
                        <input type="url" class="form-control" name="nueva_url_contenido" value="<?= htmlspecialchars($row['url_contenido']); ?>">
                    </div>

                    <input type="hidden" name="opcion" value="2">
                    <input type="hidden" name="id_contenido" value="<?= $row['id_contenido']; ?>">

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-outline-success">
                            <i class="bi bi-check2-circle"></i> Guardar cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para activar/desactivar contenido-->
<div class="modal fade" id="cambiarEstado_<?php echo $row['id_contenido']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <?php echo $row['estado'] == 1 ? 'Desactivar' : 'Activar'; ?> contenido
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body text-center">
                <p>
                    ¿Seguro que deseas
                    <strong><?php echo $row['estado'] == 1 ? 'desactivar' : 'activar'; ?></strong>
                    este contenido?
                </p>
                <p class="fw-bold text-primary"><?= htmlspecialchars($row['titulo']); ?></p>
            </div>

            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa-solid fa-ban"></i> Cancelar
                </button>
                <form action="../../Controlador/contenidoCtrl.php" method="post">
                    <input type="hidden" name="opcion" value="4">
                    <input type="hidden" name="id_contenido" value="<?php echo $row['id_contenido']; ?>">
                    <input type="hidden" name="nuevo_estado" value="<?php echo $row['estado'] == 1 ? 0 : 1; ?>">
                    <button type="submit" class="btn <?php echo $row['estado'] == 1 ? 'btn-danger' : 'btn-success'; ?>">
                        <i class="fa-solid fa-check"></i> Confirmar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ELIMINAR CONTENIDO -->
<div class="modal fade" id="eliminarContenido_<?php echo $row['id_contenido']; ?>" tabindex="-1" aria-labelledby="eliminarContenidoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-danger">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill"></i> Confirmar eliminación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body text-center">
                <p>¿Estás seguro de eliminar el siguiente contenido?</p>
                <h5 class="fw-bold text-danger">"<?= htmlspecialchars($row['titulo']); ?>"</h5>
                <p class="text-muted mb-0">Esta acción no se puede deshacer.</p>
            </div>

            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Cancelar
                </button>
                <form method="post" action="../../Controlador/contenidoCtrl.php">
                    <input type="hidden" name="opcion" value="3">
                    <input type="hidden" name="id_contenido" value="<?= $row['id_contenido']; ?>">
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash3-fill"></i> Eliminar definitivamente
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        tinymce.init({
            selector: 'textarea[name="cuerpo_html"]',
            language: 'es',
            height: 350,
            menubar: false,
            plugins: 'lists code autoresize',
            toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright | bullist numlist | removeformat',
            statusbar: false,
            branding: false,
            setup: function(editor) {
                editor.on('init', function() {
                    const container = editor.getContainer();
                    if (container.closest('.modal')) {
                        container.closest('.modal').addEventListener('focusin', function(e) {
                            if (
                                e.target.closest('.tox-tinymce-aux') ||
                                e.target.closest('.tox-tinymce')
                            ) {
                                e.stopImmediatePropagation();
                            }
                        });
                    }
                });
            },
        });

        document.querySelectorAll(".tipo-select").forEach(select => {
            const id = select.dataset.id || "agregar";
            const tipoActual = select.value;

            const secciones = [
                `seccion-infografia`,
                `seccion-articulo`,
                `seccion-video`,
                `editar-infografia-${id}`,
                `editar-articulo-${id}`,
                `editar-video-${id}`
            ];

            const mostrar = tipo => {
                secciones.forEach(sec => {
                    const div = document.getElementById(sec);
                    if (div) div.style.display = sec.includes(tipo) ? "block" : "none";
                });
            };

            mostrar(tipoActual);
            if (!select.disabled) {
                select.addEventListener("change", e => mostrar(e.target.value));
            }
        });

        /* Vista previa miniatura */
        document.querySelectorAll('input[name="thumbnail"]').forEach(input => {
            input.addEventListener('change', e => {
                const file = e.target.files[0];
                if (!file) return;
                const cont = e.target.closest('form').querySelector('.preview-thumbnail');
                const reader = new FileReader();
                reader.onload = ev => cont.innerHTML = `<img src="${ev.target.result}" class="rounded mt-2 border shadow-sm" style="width:120px;height:120px;object-fit:cover;">`;
                reader.readAsDataURL(file);
            });
        });

        /* Vista previa de imágenes del artículo */
        ['imagen1', 'imagen2', 'imagen3'].forEach(nombre => {
            document.querySelectorAll(`input[name="${nombre}"]`).forEach(input => {
                input.addEventListener('change', e => {
                    const file = e.target.files[0];
                    if (!file) return;
                    const cont = e.target.closest('form').querySelector(`.preview-${nombre}`);
                    const reader = new FileReader();
                    reader.onload = ev => {
                        cont.innerHTML = `<img src="${ev.target.result}" class="rounded mt-2 border shadow-sm" style="width:120px;height:120px;object-fit:cover;">`;
                    };
                    reader.readAsDataURL(file);
                });
            });
        });

        /* Mostrar nombre del archivo seleccionado */
        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', e => {
                const fileName = e.target.files.length ? e.target.files[0].name : '';
                let label = e.target.nextElementSibling;
                if (!label || label.tagName.toLowerCase() !== 'small') {
                    label = document.createElement('small');
                    label.className = 'text-muted';
                    e.target.insertAdjacentElement('afterend', label);
                }
                label.textContent = fileName ? `Archivo seleccionado: ${fileName}` : '';
            });
        });
    });
</script>