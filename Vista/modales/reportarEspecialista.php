<?php
$conn = mysqli_connect("localhost", "root", "", "shakti");

if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

$sql_reportes = "SELECT * FROM tipo_reporte WHERE tipo_objetivo IN (2)";
$consulta_reporte = $conn->query($sql_reportes);
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
                    <input type="hidden" id="id_usuaria" name="id_usuaria" value="<?= htmlspecialchars($_SESSION['id_usuaria']) ?>">
                    <input type="hidden" name="opcion" value="1">
                    <input type="hidden" id="publicacion" name="publicacion">
                    <input type="hidden" name="tipo_de_reporte" value="2">
                    <?php if ($consulta_reporte && $consulta_reporte->num_rows > 0): ?>
                        <?php foreach ($consulta_reporte as $registro_reporte): ?>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label" for="reporte<?= $registro_reporte['id_tipo_reporte'] ?>">
                                    <input class="form-check-input" type="radio" name="tipoReporte" id="reporte<?= $registro_reporte['id_tipo_reporte'] ?>" value="<?= htmlspecialchars($registro_reporte['id_tipo_reporte']) ?>" required>
                                    <i class="bi bi-exclamation-triangle"></i> <?= htmlspecialchars($registro_reporte['nombre_reporte']) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No hay tipos de reporte disponibles.</p>
                    <?php endif; ?>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="enviarReporte(event)" class="btn btn-primary w-100">Reportar</button>
            </div>
        </div>
    </div>
</div>
