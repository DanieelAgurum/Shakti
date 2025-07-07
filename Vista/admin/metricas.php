<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/obtenerLink/obtenerLink.php';
$urlBase = getBaseUrl();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['correo']) || $_SESSION['id_rol'] != 3) {
    header("Location: {$urlBase}");
    exit;
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/Modelo/mostrarMetricasMdl.php';

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

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Métricas - Shakti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous" />
    <link rel="stylesheet" href="../../components/admin/styles.css" />
    <script src="https://kit.fontawesome.com/3c934cb418.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Fuggles&family=Lato&family=Mooli&display=swap" rel="stylesheet" />
    <link href="https://cdn.anychart.com/releases/v8/css/anychart-ui.min.css" rel="stylesheet" />
    <link href="https://cdn.anychart.com/releases/v8/fonts/css/anychart-font.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 1rem;
        }

        .chart-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 2rem;
        }

        #pieChart,
        #barChart {
            height: 450px;
            border: 1px solid #ccc;
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <?php
    include  $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/admin/navbar.php';
    ?>
    <div id="layoutSidenav">
        <?php
        include  $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/admin/lateral.php';
        ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4 text-center solid"><strong>Métricas</strong></h1>
                    <div class="chart-container mt-4 row">
                        <div id="pieChart" class="chart-box col-5"></div>
                        <div id="barChart" class="chart-box col-5"></div>
                        <div id="likes" class="chart-box" style="height: 400px;"></div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="https://cdn.anychart.com/releases/v8/js/anychart-base.min.js"></script>
    <script src="https://cdn.anychart.com/releases/v8/js/anychart-ui.min.js"></script>
    <script src="https://cdn.anychart.com/releases/v8/js/anychart-exports.min.js"></script>

    <script>
        anychart.onDocumentReady(function() {
            try {
                const vistasData = <?= json_encode($vistasFormateadas) ?>;
                const tiempoData = <?= json_encode($tiemposFormateados) ?>;
                const likesData = <?= json_encode($topLikes) ?>;

                const pieDataSet = anychart.data.set(vistasData);
                const pieChart = anychart.pie(pieDataSet);

                pieChart.labels().enabled(false);
                pieChart.legend(true);
                pieChart.legend().position("right");
                pieChart.legend().itemsLayout("vertical");
                pieChart.legend().itemsFormat(function() {
                    const total = vistasData.reduce((acc, item) => acc + item.value, 0);
                    const currentValue = this.value;
                    const percent = (currentValue / total) * 100;
                    return this.x + ': ' + currentValue + ' (' + percent.toFixed(1) + '%)';
                });
                pieChart.title()
                    .enabled(true)
                    .text("Vistas por página")
                    .fontSize(26)
                    .fontWeight("bold")
                    .fontColor("#333")
                    .useHtml(false);
                pieChart.container("pieChart");
                pieChart.draw();

                const barChart = anychart.bar();
                barChart.data(tiempoData, 'x', 'value');
                barChart.labels().enabled(true);
                barChart.labels().format('{%value}');
                barChart.title()
                    .enabled(true)
                    .text("Tiempo total por página (segundos)")
                    .fontSize(26)
                    .fontWeight("bold")
                    .fontColor("#333")
                    .useHtml(false);
                barChart.yAxis().title("Segundos");
                barChart.xAxis().title("Vistas");
                barChart.container("barChart");
                barChart.draw();

                var likesChart = anychart.pie3d(likesData);
                likesChart.title('Publicaciones con más "Me gusta"');
                likesChart.radius('43%');
                likesChart.innerRadius('30%');
                likesChart.container('likes');
                likesChart.draw();

            } catch (e) {
                console.error("Error creando las gráficas:", e);
            }
        });
    </script>

</body>

</html>