<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/shakti/Modelo/conexion.php";
$db = new ConectarDB();
$conn = $db->open();
$sql = "SELECT * FROM tipo_reporte WHERE tipo_objetivo IN (1)";
$consulta = $conn->query($sql);
?>

<div class="modal fade" id="modalReportar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Motivo del Reporte</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="mensaje"></div>
                <form class="row g-3" id="reportarPublicacion">
                    <input type="hidden" id="nickname" name="nickname">
                    <input type="hidden" id="id_usuaria" name="id_usuaria" value="<?= $_SESSION['id_usuaria'] ?>">
                    <input type="hidden" name="opcion" value="1">
                    <input type="hidden" id="publicacion" name="publicacion">
                    <input type="hidden" name="tipo_de_reporte" value="1">
                    <?php foreach ($consulta as $registro): ?>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label" for="reporte<?= $registro['id_tipo_reporte'] ?>">
                                <input class="form-check-input" type="radio" name="tipoReporte" id="reporte<?= $registro['id_tipo_reporte'] ?>" value="<?= htmlspecialchars($registro['id_tipo_reporte']) ?>" required>
                                <i class="bi bi-exclamation-triangle"></i> <?= htmlspecialchars($registro['nombre_reporte']) ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="enviarReporte(event)" class="btn btn-primary w-100">Reportar</button>
            </div>
        </div>
    </div>
</div>