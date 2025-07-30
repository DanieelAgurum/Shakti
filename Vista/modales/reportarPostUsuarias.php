<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/Shakti/Modelo/conexion.php";
$db = new ConectarDB();
$conn = $db->open();
$sql = "SELECT * FROM tipo_reporte WHERE tipo_objetivo IN (3)";
$consulta = $conn->query($sql);
?>

<!-- Modal para reportar -->
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
                    <input type="hidden" name="tipo_de_reporte" value="">
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


<div class="modal fade" id="modalCompartir" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info position-relative">
                <h1 class="modal-title fs-5 position-absolute top-50 start-50 translate-middle" id="exampleModalLabel">
                    Compartir
                </h1>
                <button type="button" class="btn btn-close btn-sm ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="#" onclick="compartirWhatsapp()" class="btn btn-outline-success" title="Compartir por WhatsApp">
                        <i class="bi bi-whatsapp"></i> WhatsApp
                    </a>
                    <a href="#" onclick="compartirFacebook()" class="btn btn-outline-primary" title="Compartir en Facebook">
                        <i class="bi bi-facebook"></i> Facebook
                    </a>
                    <a href="#" onclick="compartirTwitter()" class="btn btn-outline-dark" title="Compartir en X (Twitter)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-twitter-x" viewBox="0 0 16 16">
                            <path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>