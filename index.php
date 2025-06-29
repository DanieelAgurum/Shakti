<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

include_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/obtenerLink/obtenerLink.php';
$urlBase = getBaseUrl();
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Shakti</title>
  <!-- Tu CSS personalizado -->
  <link rel="stylesheet" href="<?= $urlBase ?>css/styles.css" />
  <?php
  include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/usuaria/navbar.php';
  ?>
</head>

<body class="bg-white text-black">

  <main class="hero p-5 text-center">
    <?php if (isset($_SESSION['correo'])) {
      require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/Vista/usuaria/inicioUser.php'; ?>
    <?php } else { ?>
      <h1>Bienvenido a Nuestro Sitio Shakti</h1>
      <p class="lead">Tu bienestar es primero</p>
      <div class="hero-buttons mt-4">
        <a href="#" class="btn btn-primary me-2">Conocer más</a>
        <a href="#" class="btn btn-outline-secondary">Contáctanos</a>
      </div>
    <?php } ?>
  </main>

  <?php include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/usuaria/footer.php'; ?>
</body>

</html>