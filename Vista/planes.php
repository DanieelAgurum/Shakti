<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suscripción - Shakti</title>

    <link rel="stylesheet" href="../css/planes.css">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/usuaria/navbar.php'; ?>
</head>

<body>
    <div class="container">
        <h1>Suscripción a Shakti</h1>
        <p class="intro">Apoya la prevención de la violencia hacia la mujer con nuestras opciones de suscripción.</p>

        <div class="plans">
            <div class="plan-card free">
                <h2>Básica</h2>
                <h3>Free</h3>
                <ul>
                    <li>✅ Acceso limitado a comentarios</li>
                    <li>❌ Atención por un especialista</li>
                    <li>❌ Publicaciones</li>
                    <li>❌ Alza la voz</li>
                    <li>❌ Foro</li>
                </ul>
                <button class="plan-button" disabled>Plan Actual</button>
            </div>

            <div class="plan-card subscription">
                <h2>Suscripción</h2>
                <h3>$500</h3>
                <ul>
                    <li>✅ Acceso ilimitado a comentarios</li>
                    <li>✅ Atención por un especialista</li>
                    <li>✅ Publicaciones</li>
                    <li>✅ Alza la voz</li>
                    <li>✅ Foro</li>
                </ul>
                 <button class="plan-button" disabled>Plan Actual</button>
            </div>
        </div>
    </div>
    <?php include_once '../components/usuaria/footer.php'; ?>
</body>

</html>