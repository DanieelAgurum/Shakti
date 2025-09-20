<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/obtenerLink/obtenerLink.php';
$urlBase = getBaseUrl();
if (!(isset($_SESSION['id_rol'])) || $_SESSION['id_rol'] == 2) {
    header("Location: {$urlBase}Vista/especialista/perfil.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <title>Test Violentómetro</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?= $urlBase ?>css/publicaciones.css" />
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/usuaria/navbar.php'; ?>

</head>

<body>
    <div class="container mt-3 mb-3 d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow alzalaVoz-card p-4 w-100" style="max-width: 600px;">
            <h1 class="text-center mb-4 fw-bold">Test Violentómetro</h1>

            <?php
            if (isset($_SESSION['mensaje'])) {
                echo "<div class='alert alert-info'>" . htmlspecialchars($_SESSION['mensaje']) . "</div>";
                unset($_SESSION['mensaje']);
            }

            if (isset($_SESSION['tipo_violencia'])) {
                echo "<div class='alert alert-warning fw-semibold'>Tipo de violencia detectado: <strong>" . htmlspecialchars($_SESSION['tipo_violencia']) . "</strong></div>";
                // No unset para que persista
            }
            ?>

            <form action="<?= htmlspecialchars($urlBase) ?>/controlador/alzalaVoz.php" method="POST" class="alzalaVoz-form">
                <?php
                // Define las preguntas para generar el formulario dinámicamente
                $preguntas = [
                    "p1" => "¿Has sentido miedo por amenazas?",
                    "p2" => "¿Has sido insultada o humillada?",
                    "p3" => "¿Te han controlado económicamente?",
                    "p4" => "¿Has sufrido golpes o agresiones físicas?",
                    "p5" => "¿Tu pareja o alguien cercano te controla con palabras o actitudes?",
                    "p6" => "¿Te han impedido usar tu dinero o controlar tus finanzas?"
                ];

                $opciones = [
                    "p1" => ['no' => 'No', 'psicologica' => 'Sí, psicológica', 'fisica' => 'Sí, física'],
                    "p2" => ['no' => 'No', 'psicologica' => 'Sí, psicológica', 'fisica' => 'Sí, física'],
                    "p3" => ['no' => 'No', 'economica' => 'Sí, económica'],
                    "p4" => ['no' => 'No', 'fisica' => 'Sí'],
                    "p5" => ['no' => 'No', 'psicologica' => 'Sí'],
                    "p6" => ['no' => 'No', 'economica' => 'Sí']
                ];

                foreach ($preguntas as $key => $texto) {
                    echo '<div class="mb-3">';
                    echo "<label for='$key' class='form-label'>$texto</label>";
                    echo "<select class='form-select alzalaVoz-select' id='$key' name='respuestas[$key]' required>";
                    echo "<option value=''>Selecciona</option>";
                    foreach ($opciones[$key] as $val => $label) {
                        echo "<option value='$val'>$label</option>";
                    }
                    echo "</select>";
                    echo '</div>';
                }
                ?>

                <button type="submit" name="enviar_test" class="btn btn-outline-primary w-100 mt-3 alzalaVoz-btn">Enviar Test</button>
            </form>
        </div>
    </div>
    <script src="<?= $urlBase ?>peticiones(js)/mandarMetricas.js.php?vista=<?= urlencode(basename($_SERVER['PHP_SELF'])) ?>"></script>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/usuaria/footer.php'; ?>
</body>

</html>