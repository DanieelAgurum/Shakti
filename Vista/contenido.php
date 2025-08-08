<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/obtenerLink/obtenerLink.php';
$urlBase = getBaseUrl();
include_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Modelo/conexion.php';

$database = new ConectarDB();
$db = $database->open();

$contenido = [];
try {
    $sql = "SELECT id, titulo, descripcion, url, imagen, fecha_publicacion FROM contenido WHERE estatus = 1 ORDER BY fecha_publicacion DESC";
    $contenido = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Contenido Público - SHAKTI</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #fce4ec, #f3e5f5);
      font-family: 'Montserrat', sans-serif;
      padding-top: 80px;
    }
    .titulo-seccion {
      text-align: center;
      font-weight: 600;
      color: #000000ff;
      margin-bottom: 40px;
      font-size: 2.5rem;
    }
    .card {
      border: none;
      border-radius: 1rem;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .card-title {
      color: #ffffffff;
      font-weight: 600;
    }
    .btn-vermas {
      border-color: #ffffffff;
      color: #ffffffff;
    }
    .btn-vermas:hover {
      background-color: #ffffffff;
      color: white;
    }
  </style>
</head>
<body>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/usuaria/navbar.php'; ?>

<div class="container">
  <h1 class="titulo-seccion">Contenido Informativo</h1>

  <?php if (!empty($contenido)): ?>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
      <?php foreach ($contenido as $item): ?>
        <div class="col">
          <div class="card h-100 shadow-sm bg-white">
            <?php if (!empty($item['imagen'])): ?>
              <img src="data:image/jpeg;base64,<?= base64_encode($item['imagen']) ?>" class="card-img-top" alt="Imagen" style="height: 200px; object-fit: cover; border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
            <?php endif; ?>
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($item['titulo']) ?></h5>
              <p class="card-text"><?= nl2br(htmlspecialchars($item['descripcion'])) ?></p>
            </div>
            <div class="card-footer d-flex justify-content-between align-items-center bg-light border-top-0">
              <small class="text-muted"><?= date('d M Y', strtotime($item['fecha_publicacion'])) ?></small>
              <?php if (!empty($item['url'])): ?>
                <a href="<?= htmlspecialchars($item['url']) ?>" target="_blank" class="btn btn-sm btn-vermas">Ver más</a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="alert alert-warning text-center mt-5">
      <strong>No hay contenido disponible por el momento.</strong>
    </div>
  <?php endif; ?>
</div>

</body>
</html>
