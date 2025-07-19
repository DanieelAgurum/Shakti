<!-- Modal para registrar servicios -->
<div class="modal fade" id="agregarServicios" tabindex="-1" aria-labelledby="agregarServiciosLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formServicios" method="post" action="../../Controlador/serviciosCtrl.php" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Seleccionar Servicios</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <?php
                $serviciosDisponibles = ["Cita médica", "Terapia", "Consulta psicológica", "Asesoría legal", "Terapia ocupacional", "Nutrición", "Fisioterapia", "Psicopedagogía", "Terapia de lenguaje", "Coaching personal", "Orientación vocacional", "Terapia familiar", "Terapia de pareja", "Terapia infantil", "Terapia grupal", "Otros"];
                foreach ($serviciosDisponibles as $index => $servicio) {
                    $checked = in_array($servicio, $serviciosRegistrados) ? 'checked' : '';
                    echo '<div class="form-check">
                    <input class="form-check-input" type="checkbox" name="servicios[]" value="' . $servicio . '" id="serv' . $index . '" ' . $checked . '>
                    <label class="form-check-label" for="serv' . $index . '">' . $servicio . '</label>
                  </div>';
                }
                ?>

                <input type="hidden" name="id_usuaria" value="<?php echo $_SESSION['id_usuaria']; ?>">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Cancelar</button>
                <button type="submit" class="btn btn-outline-success">
                    <i class="bi bi-check2-circle"></i> Guardar
                </button>
            </div>
        </form>
    </div>
</div>