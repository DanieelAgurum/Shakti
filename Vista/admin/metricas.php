<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/obtenerLink/obtenerLink.php';
$urlBase = getBaseUrl();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['correo']) || $_SESSION['id_rol'] != 3) {
    header("Location: {$urlBase}");
    exit;
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Modelo/mostrarMetricasMdl.php';

$modelo = new mostrarMetricasMdl();
$datos = $modelo->mostrar();

$vistas = $datos['vistas'] ?? [];
$tiempos = $datos['tiempos'] ?? [];

// Convertir arrays indexados en objetos con 'x' y 'value'
$vistasFormateadas = array_map(function ($item) {
    return ['x' => $item[0], 'value' => (int)$item[1]];
}, $vistas);

$tiemposFormateados = array_map(function ($item) {
    return ['x' => $item[0], 'value' => (float)$item[1]];
}, $tiempos);

$topLikes = $modelo->obtenerTopLikes();
$topComentarios = $modelo->obtenerTopComentarios();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Métricas - Shakti</title>
    <style>

        .chart-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 3rem;
        }

        #pieChart,
        #barChart,
        #likes,
        #comentarios {
            height: 500px;
            border: 1px solid #ccc;
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <?php
    include  $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/admin/navbar.php';
    ?>
    <div id="layoutSidenav">
        <?php
        include  $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/admin/lateral.php';
        ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4 mb-5 mt-5">
                    <h1 class="mt-4 text-center solid"><strong>Métricas</strong></h1>
                    <div class="chart-container mt-4 row">
                        <div id="pieChart" class="chart-box col-10"></div>
                        <div id="barChart" class="chart-box col-10"></div>
                    </div>
                    <div class="chart-container px-4 mt-4 row">
                        <div id="likes" class="chart-box col-10"></div>
                        <div id="comentarios" class="chart-box col-10"></div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.anychart.com/releases/v8/js/anychart-base.min.js"></script>
    <script src="https://cdn.anychart.com/releases/v8/js/anychart-ui.min.js"></script>
    <script src="https://cdn.anychart.com/releases/v8/js/anychart-exports.min.js"></script>
    <script>
        anychart.onDocumentReady(function() {
            try {
                const vistasData = <?= json_encode($vistasFormateadas) ?>;
                const tiempoData = <?= json_encode($tiemposFormateados) ?>;
                const likesData = <?= json_encode($topLikes) ?>;
                const comentariosData = <?= json_encode($topComentarios) ?>;

                const pieDataSet = anychart.data.set(vistasData);
                const pieChart = anychart.pie(pieDataSet);
                pieChart.labels().enabled(false);
                pieChart.legend(true);
                pieChart.legend().position("right");
                pieChart.legend().itemsLayout("vertical");
                pieChart.legend().itemsFormat(function() {
                    const total = vistasData.reduce((acc, item) => acc + item.value, 0);
                    const percent = (this.value / total) * 100;
                    return this.x + ': ' + this.value + ' (' + percent.toFixed(1) + '%)';
                });
                pieChart.title()
                    .enabled(true)
                    .text("Vistas por Página")
                    .fontSize(26)
                    .fontWeight("bold")
                    .fontColor("#333")
                    .useHtml(false);
                pieChart.container("pieChart");
                pieChart.draw();

                const barChart = anychart.bar();
                barChart.data(tiempoData, 'x', 'value');
                barChart.labels().enabled(true).format('{%value}');
                barChart.title()
                    .enabled(true)
                    .text("Tiempo Total por Página (Segundos)")
                    .fontSize(26)
                    .fontWeight("bold")
                    .fontColor("#333")
                    .useHtml(false);
                barChart.yAxis().title("Segundos");
                barChart.xAxis().title("Vistas");
                barChart.container("barChart");
                barChart.draw();

                const likesChart = anychart.pie3d(likesData);
                likesChart.title()
                    .enabled(true)
                    .text('Publicaciones con más "Me gusta"')
                    .fontSize(26)
                    .fontWeight("bold")
                    .fontColor("#333")
                    .useHtml(false);
                likesChart.radius('43%');
                likesChart.innerRadius('30%');
                likesChart.container("likes");
                likesChart.draw();

                const comentariosChart = anychart.pie3d(comentariosData);
                comentariosChart.title()
                    .enabled(true)
                    .text('Publicaciones con más Comentarios')
                    .fontSize(26)
                    .fontWeight("bold")
                    .fontColor("#333")
                    .useHtml(false);
                comentariosChart.radius('43%');
                comentariosChart.innerRadius('30%');
                comentariosChart.container("comentarios");
                comentariosChart.draw();

            } catch (e) {
                console.error("Error creando las gráficas:", e);
            }
        });
    </script>

</body>

</html>