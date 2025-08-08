<?php
header('Content-Type: application/javascript');
$vista = $_GET['vista'] ?? 'desconocida';
include_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/obtenerLink/obtenerLink.php';
$urlBase = getBaseUrl();
?>

window.tiempoInicio = Date.now() / 1000;
window.vista = "<?= addslashes($vista) ?>";

setInterval(() => {
const ahora = Date.now() / 1000;
const transcurrido = ahora - window.tiempoInicio;
}, 1000);

window.addEventListener("beforeunload", function () {
    const tiempoFin = Date.now() / 1000;
    const duracion = tiempoFin - window.tiempoInicio;

    const data = new FormData();
    data.append("vista", window.vista);
    data.append("tiempo_estancia", duracion);

    navigator.sendBeacon("<?= $urlBase ?>metricas/metricasAdmin.php", data);
});
