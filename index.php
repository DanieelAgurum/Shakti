<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

include_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/obtenerLink/obtenerLink.php';
$urlBase = getBaseUrl();

// Incluye el modelo para obtener publicaciones
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/modelo/PublicacionModelo.php';
$publicacionModelo = new PublicacionModelo();
$publicaciones = $publicacionModelo->obtenerTodasConNickname();
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Shakti</title>
  <link rel="stylesheet" href="<?= $urlBase ?>css/styles.css" />
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/usuaria/navbar.php'; ?>
</head>

<body class="bg-white text-black">

  <main class="hero p-5 text-center">
    <?php if (isset($_SESSION['correo'])): ?>
      <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/Vista/usuaria/inicioUser.php'; ?>
    <?php else: ?>
      <h1>Bienvenido a Nuestro Sitio Shakti</h1>
      <p class="lead">Tu bienestar es primero</p>
      <div class="hero-buttons mt-4">
        <a href="#" class="btn btn-primary me-2">Conocer más</a>
        <a href="#" class="btn btn-outline-secondary">Contáctanos</a>
      </div>
    <?php endif; ?>
  </main>

  <!-- Sección de publicaciones -->
  <section class="container mt-5">
    <h2 class="mb-4">Publicaciones Recientes</h2>
    <?php if (!empty($publicaciones)): ?>
      <?php foreach ($publicaciones as $pub): ?>
        <div class="card mb-3 shadow-sm">
          <div class="card-header d-flex justify-content-between align-items-center">
            <strong><?= htmlspecialchars($pub['titulo']) ?></strong>
            <small class="text-muted"><?= date('d M Y H:i', strtotime($pub['fecha_publicacion'])) ?></small>
          </div>
          <div class="card-body">
            <p class="card-text"><?= nl2br(htmlspecialchars($pub['contenido'])) ?></p>
          </div>
          <div class="card-footer text-muted">
            Publicado por: <strong><?= htmlspecialchars($pub['nickname']) ?></strong>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="text-center text-muted">No hay publicaciones para mostrar.</p>
    <?php endif; ?>
  </section>

  <?php include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/usuaria/footer.php'; ?>
</body>

</html>
