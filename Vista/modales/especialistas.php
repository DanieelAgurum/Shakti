<div class="modal fade" id="modalEspecialista<?= htmlspecialchars($row['id'] ?? '') ?>" tabindex="-1" aria-labelledby="labelModal<?= htmlspecialchars($row['id'] ?? '') ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg rounded-4" style="background-image: linear-gradient(to top, #fad0c4 0%, #ffd1ff 100%);">

            <div class="modal-header bg-dark text-white d-flex justify-content-between align-items-start">
                <div>
                    <h5 class="modal-title mb-0" id="labelModal<?= htmlspecialchars($row['id'] ?? '') ?>">
                        <i class="bi bi-person-circle me-2"></i>
                        Perfil del Especialista:
                        <?= htmlspecialchars($row['nickname'] ?? '') ?>
                    </h5>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-sm btn-reportar-hover position-relative"
                        data-bs-toggle="modal" data-bs-target="#modalReportar"
                        onclick="rellenarDatosReporte('<?= htmlspecialchars(ucwords(strtolower($row['nickname'] ?? ''))) ?>', '<?= htmlspecialchars($row['id'] ?? '') ?>')">
                        <span class="tooltip-hover">Reportar</span>
                        <i class="fa-solid fa-triangle-exclamation" style="color:red"></i>
                    </button>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
            </div>

            <div class="modal-body">
                <div class="row g-4">
                    <!-- Foto -->
                    <div class="col-md-4 text-center">
                        <img src="<?= htmlspecialchars($src ?? '') ?>" class="rounded-circle border border-white border-5" width="150" height="150" alt="Foto de perfil">
                    </div>

                    <!-- Datos -->
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong>Nombre completo:</strong>
                                <div class="text-muted">
                                    <?= htmlspecialchars($row['nombre'] ?? '') ?>
                                    <?= htmlspecialchars($row['apellidos'] ?? '') ?>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <strong>Correo:</strong>
                                <div class="text-muted"><?= htmlspecialchars($row['correo'] ?? '') ?></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <strong>Descripción:</strong>
                                <div class="text-muted"><?= htmlspecialchars($row['descripcion'] ?? '') ?></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <strong>Servicios:</strong>
                                <div class="text-muted"><?= htmlspecialchars($row['servicio'] ?? '') ?></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <strong>Teléfono:</strong>
                                <div class="text-muted"><?= htmlspecialchars($row['telefono'] ?? '') ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer justify-content-end">
                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>