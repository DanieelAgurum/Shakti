<!-- Modal para registrar servicios -->
<div class="modal fade custom-config-modal" id="agregarServicios" tabindex="-1" aria-labelledby="agregarServiciosLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- modal más ancho -->
        <form id="formServicios" method="post" action="../../Controlador/serviciosCtrl.php" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Seleccionar Servicios</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body scrollable-modal-body">
                <div class="row">
                    <?php
                    $serviciosDisponibles = [
                        "Cita médica",
                        "Terapia",
                        "Consulta psicológica",
                        "Asesoría legal",
                        "Terapia ocupacional",
                        "Nutrición",
                        "Fisioterapia",
                        "Psicopedagogía",
                        "Terapia de lenguaje",
                        "Coaching personal",
                        "Orientación vocacional",
                        "Terapia familiar",
                        "Terapia de pareja",
                        "Terapia infantil",
                        "Terapia grupal",
                        "Acompañamiento emocional",
                        "Intervención en crisis",
                        "Talleres de autoestima",
                        "Educación sexual y reproductiva",
                        "Capacitación laboral",
                        "Asesoría en proyectos productivos",
                        "Orientación en violencia digital",
                        "Educación financiera para mujeres",
                        "Mediación de conflictos",
                        "Atención a adicciones",
                        "Apoyo en procesos legales de denuncia",
                        "Orientación en refugios o casas de transición",
                        "Asesoría migratoria para mujeres en riesgo",
                        "Apoyo para madres solteras",
                        "Reinserción social y comunitaria",
                        "Otros"
                    ];

                    $serviciosRegistrados = isset($serviciosRegistrados) ? $serviciosRegistrados : [];

                    $totalServicios = count($serviciosDisponibles);
                    $mitad = ceil($totalServicios / 2);

                    for ($col = 0; $col < 2; $col++) {
                        echo '<div class="col-md-6">';
                        $start = $col * $mitad;
                        $end = min(($col + 1) * $mitad, $totalServicios);

                        for ($i = $start; $i < $end; $i++) {
                            $servicio = $serviciosDisponibles[$i];
                            $checked = in_array($servicio, $serviciosRegistrados) ? 'checked' : '';
                            echo '<div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="servicios[]" value="' . $servicio . '" id="serv' . $i . '" ' . $checked . '>
                                    <label class="form-check-label" for="serv' . $i . '">' . $servicio . '</label>
                                </div>';
                        }

                        echo '</div>'; // cierre col-md-6
                    }
                    ?>
                </div>

                <input type="hidden" name="id_usuaria" value="<?php echo $_SESSION['id_usuaria']; ?>">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-banner btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i> Cancelar
                </button>
                <button type="submit" class="btn btn-banner">
                    <i class="bi bi-check2-circle"></i> Guardar
                </button>
            </div>
        </form>
    </div>
</div>