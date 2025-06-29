<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/modelo/PublicacionModelo.php';

$mensaje = $_SESSION['mensaje'] ?? '';
unset($_SESSION['mensaje']);

$urlBase = '/Shakti/';

// Instanciar el modelo y traer publicaciones
$publicacionModelo = new PublicacionModelo();
$publicaciones = $publicacionModelo->obtenerTodas(); // O solo por usuaria si deseas
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Publicaciones - Shakti</title>
  <link rel="stylesheet" href="<?= $urlBase ?>css/styles.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/usuaria/navbar.php'; ?>
</head>
<body>

<div class="container mt-5">
  <?php if ($mensaje): ?>
    <div class="alert alert-info"><?= htmlspecialchars($mensaje) ?></div>
  <?php endif; ?>

  <!-- Formulario de publicación -->
  <div class="card mb-4 shadow-sm">
    <div class="card-body">
      <h5 class="card-title">Crear publicación</h5>
      <form method="POST" action="<?= $urlBase ?>Controlador/PublicacionControlador.php">
        <div class="mb-3">
          <input type="text" class="form-control mb-2" name="titulo" placeholder="Título de tu publicación" required>
          <textarea class="form-control" name="contenido" rows="3" placeholder="¿Qué estás pensando?" required></textarea>
        </div>
        <input type="hidden" name="guardar_publicacion" value="1" />
        <button type="submit" class="btn btn-primary">Publicar</button>
      </form>
    </div>
  </div>

  <!-- Lista de publicaciones -->
  <?php if (count($publicaciones) > 0): ?>
    <?php foreach ($publicaciones as $pub): ?>
      <div class="card mb-3 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
          <strong><?= htmlspecialchars($pub['titulo']) ?></strong>
          <small class="text-muted"><?= date('d M Y H:i', strtotime($pub['fecha_publicacion'])) ?></small>
        </div>
        <div class="card-body">
          <p class="card-text"><?= nl2br(htmlspecialchars($pub['contenido'])) ?></p>
        </div>
        <div class="card-footer">
          <div class="d-flex justify-content-between align-items-center">
            <button class="btn btn-sm btn-outline-primary btn-like" data-id="<?= $pub['id_publicacion'] ?>">
              <i class="bi bi-hand-thumbs-up"></i> Me gusta <span class="badge bg-primary likes-count">0</span>
            </button>
            <button class="btn btn-sm btn-outline-secondary btn-toggle-comments" data-id="<?= $pub['id_publicacion'] ?>">
              <i class="bi bi-chat"></i> Comentarios
            </button>
          </div>
          <div class="comments-section mt-3 d-none" id="comments-<?= $pub['id_publicacion'] ?>">
            <div class="existing-comments mb-3">
              <p class="text-muted">Aún no hay comentarios.</p>
            </div>
            <form class="comment-form" data-id="<?= $pub['id_publicacion'] ?>">
              <div class="input-group">
                <input type="text" class="form-control form-control-sm" placeholder="Escribe un comentario..." required />
                <button class="btn btn-sm btn-primary" type="submit">Enviar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p class="text-center text-muted">No hay publicaciones aún.</p>
  <?php endif; ?>
</div>

<script>
document.querySelectorAll('.btn-toggle-comments').forEach(btn => {
  btn.addEventListener('click', () => {
    const pubId = btn.getAttribute('data-id');
    const commentsSection = document.getElementById('comments-' + pubId);
    commentsSection.classList.toggle('d-none');
  });
});

document.querySelectorAll('.btn-like').forEach(btn => {
  btn.addEventListener('click', () => {
    const badge = btn.querySelector('.likes-count');
    let count = parseInt(badge.textContent) || 0;
    count++;
    badge.textContent = count;
    btn.classList.add('btn-primary');
    btn.classList.remove('btn-outline-primary');
    btn.disabled = true;
  });
});

document.querySelectorAll('.comment-form').forEach(form => {
  form.addEventListener('submit', e => {
    e.preventDefault();
    const input = form.querySelector('input[type="text"]');
    const commentText = input.value.trim();
    if (!commentText) return;

    const commentsDiv = form.previousElementSibling;
    const p = document.createElement('p');
    p.textContent = commentText;
    p.classList.add('mb-1');
    commentsDiv.appendChild(p);
    input.value = '';
  });
});
</script>
</body>
</html>
