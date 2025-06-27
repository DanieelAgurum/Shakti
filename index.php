
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
  <link rel="stylesheet" href="css/styles.css" />
</head>

<body class="bg-white text-black">
 <?php
require $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/navbar.php';

?>


  <main class="hero">
    <h1>Bienvenido a Nuestro Sitio Shakti</h1>
    <p>Tu bienestar es primero</p>
    <div class="hero-buttons">
      <a href="#" class="primary-btn">Conocer más</a>
      <a href="#" class="secondary-btn">Contáctanos</a>
    </div>
  </main>

  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

 
</body>

<?php include 'components/usuaria/footer.php'; ?>
</html>
