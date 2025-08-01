<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/obtenerLink/obtenerLink.php';
$urlBase = getBaseUrl();
include_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/Modelo/conexion.php';

$database = new ConectarDB();
$db = $database->open();

$contenido = [];
try {
  $sql = "SELECT id, nombre, descripcion, numero, imagen FROM organizaciones";
  $contenido = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $error = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Organizaciones - Shakti</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= $urlBase ?>css/organizacion.css">
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/usuaria/navbar.php'; ?>
</head>

<body>
  <div class="container mt-5">
    <h1 class="mb-5 text-center">Organizaciones</h1>

    <?php if (!empty($contenido)): ?>
      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-5">
        <?php foreach ($contenido as $item): ?>
          <div class="col mb-4">
            <div class="card h-100 shadow-sm custom-card">
              <?php if (!empty($item['imagen'])): ?>
                <img src="data:image/jpg;base64,<?= base64_encode($item['imagen']) ?>" class="card-img-top" alt="Imagen" style="object-fit: cover;">
              <?php endif; ?>

              <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($item['nombre']) ?></h5>
                <p class="card-text"><?= nl2br(htmlspecialchars($item['descripcion'])) ?></p>
              </div>
              <div class="card-footer text-muted">
                <?php if (!empty($item['numero'])): ?>
                  <span>Contacto: <?= htmlspecialchars($item['numero']) ?></span>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="alert alert-info text-center">No hay contenido disponible por el momento.</div>
    <?php endif; ?>
  </div>
  <?php include_once '../components/usuaria/footer.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>