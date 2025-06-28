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
  <title>SHAKTI</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet" />

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

  <!-- Tu CSS personalizado -->
  <link rel="stylesheet" href="<?= $urlBase ?>css/styles.css" />
</head>

<body class="bg-white text-black">

  <?php
  // Navbar según sesión
  if (isset($_SESSION['id_rol']) && $_SESSION['id_rol'] == 1) {
    include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/usuaria/navbar.php';
  } else {
    include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/navbar.php';
  }
  ?>

  <main class="hero p-5 text-center">
    <?php
    if (isset($_SESSION['id_rol']) && $_SESSION['id_rol'] == 1) {
      include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/Vista/usuaria/inicioUser.php';
    } else {
      // Vista pública
      ?>
      <h1>Bienvenido a Nuestro Sitio Shakti</h1>
      <p class="lead">Tu bienestar es primero</p>
      <div class="hero-buttons mt-4">
        <a href="#" class="btn btn-primary me-2">Conocer más</a>
        <a href="#" class="btn btn-outline-secondary">Contáctanos</a>
      </div>
      <?php
    }
    ?>
  </main>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <?php include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/usuaria/footer.php'; ?>
</body>

</html>
