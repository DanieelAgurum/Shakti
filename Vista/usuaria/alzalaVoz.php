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
    <meta charset="UTF-8">
    <title>Test IAn – Bienestar Emocional Masculino</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= $urlBase ?>css/publicaciones.css">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/usuaria/navbar.php'; ?>
</head>

<body>
<div class="container mt-3 mb-3 d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow p-4 w-100" style="max-width: 650px;">
        <h1 class="text-center mb-4 fw-bold text-primary">Test IAn – Bienestar Emocional Masculino</h1>
        <p class="text-muted text-center mb-4">Este test busca ayudarte a identificar cómo estás gestionando tus emociones. 
        No es un diagnóstico, solo una guía para conocerte mejor.</p>

        <form id="testIan" method="POST">
            <?php
            $preguntas = [
                "p1" => "¿Sueles guardar lo que sientes para no parecer débil?",
                "p2" => "¿Te cuesta hablar de tus emociones con tus amigos o familia?",
                "p3" => "¿Últimamente te has sentido cansado mentalmente o sin motivación?",
                "p4" => "¿Te enojas con facilidad, incluso por cosas pequeñas?",
                "p5" => "¿Sientes que no puedes fallar o mostrarte vulnerable?",
                "p6" => "¿Has sentido presión por cumplir con expectativas de los demás?",
                "p7" => "¿Duermes bien y descansas lo suficiente?",
                "p8" => "¿Tienes alguien con quien puedas hablar sin sentirte juzgado?"
            ];

            $opciones = [
                'no' => 'No, nunca',
                'poco' => 'A veces',
                'frecuente' => 'Con frecuencia',
                'siempre' => 'Casi siempre'
            ];

            foreach ($preguntas as $key => $texto) {
                echo "<div class='mb-3'>";
                echo "<label for='$key' class='form-label fw-semibold'>$texto</label>";
                echo "<select class='form-select' id='$key' name='respuestas[$key]' required>";
                echo "<option value=''>Selecciona</option>";
                foreach ($opciones as $val => $label) {
                    echo "<option value='$val'>$label</option>";
                }
                echo "</select></div>";
            }
            ?>

            <button type="submit" class="btn btn-primary w-100 mt-3">Analizar con IAn</button>
        </form>

        <div id="respuestaIA" class="mt-4"></div>
    </div>
</div>

<script>
document.getElementById('testIan').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const respuestas = Object.fromEntries(formData.entries());

    const resp = await fetch('<?= $urlBase ?>Controlador/testControl.php', {
        method: 'POST',
        body: formData
    });

    const data = await resp.json();
    document.getElementById('respuestaIA').innerHTML = `
        <div class="alert alert-info mt-3">${data.respuesta}</div>`;
});
</script>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/usuaria/footer.php'; ?>
</body>
</html>
