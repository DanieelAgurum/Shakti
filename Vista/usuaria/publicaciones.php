<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/modelo/PublicacionModelo.php';

$urlBase = '/Shakti/';

// Verificar sesión
if (!isset($_SESSION['correo'])) {
  header("Location: $urlBase/Vista/login.php");
  exit;
}

$id_usuaria = $_SESSION['id_usuaria'];
$mensaje = $_SESSION['mensaje'] ?? '';
unset($_SESSION['mensaje']);

$publicacionModelo = new PublicacionModelo();
$publicaciones = $publicacionModelo->obtenerPorUsuaria($id_usuaria);
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
        <form method="POST" action="<?= $urlBase ?>Controlador/PublicacionControlador.php" onsubmit="return validarFormulario();">
          <div class="mb-3">
            <input type="text" class="form-control mb-2" name="titulo" placeholder="Título de tu publicación" minlength="3" required>
            <textarea class="form-control" name="contenido" rows="3" placeholder="¿Qué estás pensando?" minlength="5" required></textarea>
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
            <!-- Texto visible -->
            <strong class="titulo-text" id="titulo-text-<?= $pub['id_publicacion'] ?>"><?= htmlspecialchars($pub['titulo']) ?></strong>
            <small class="text-muted"><?= date('d M Y H:i', strtotime($pub['fecha_publicacion'])) ?></small>
          </div>
          <div class="card-body">
            <p class="card-text contenido-text" id="contenido-text-<?= $pub['id_publicacion'] ?>"><?= nl2br(htmlspecialchars($pub['contenido'])) ?></p>

            <!-- Formulario edición oculto -->
            <form class="edit-form d-none" id="edit-form-<?= $pub['id_publicacion'] ?>" method="POST" action="<?= $urlBase ?>Controlador/PublicacionControlador.php" onsubmit="return validarEdicion(<?= $pub['id_publicacion'] ?>)">
              <input type="hidden" name="editar_publicacion" value="1" />
              <input type="hidden" name="id_publicacion" value="<?= $pub['id_publicacion'] ?>" />
              <input type="text" class="form-control mb-2" name="titulo" id="titulo-<?= $pub['id_publicacion'] ?>" value="<?= htmlspecialchars($pub['titulo']) ?>" minlength="3" required>
              <textarea class="form-control mb-2" name="contenido" id="contenido-<?= $pub['id_publicacion'] ?>" rows="3" minlength="5" required><?= htmlspecialchars($pub['contenido']) ?></textarea>
              <button type="submit" class="btn btn-sm btn-success">Guardar</button>
              <button type="button" class="btn btn-sm btn-secondary btn-cancel" data-id="<?= $pub['id_publicacion'] ?>">Cancelar</button>
            </form>
          </div>
          <div class="card-footer d-flex justify-content-between align-items-center mb-2">
            <div>
              <button class="btn btn-sm btn-outline-primary btn-like" data-id="<?= $pub['id_publicacion'] ?>">
                <i class="bi bi-hand-thumbs-up"></i> Me gusta <span class="badge bg-primary likes-count">0</span>
              </button>
              <button class="btn btn-sm btn-outline-secondary btn-toggle-comments" data-id="<?= $pub['id_publicacion'] ?>">
                <i class="bi bi-chat"></i> Comentarios
              </button>
            </div>
            <div>
              <button class="btn btn-sm btn-warning btn-toggle-edit" data-id="<?= $pub['id_publicacion'] ?>">Editar</button>
              <a href="<?= $urlBase ?>Controlador/PublicacionControlador.php?borrar_id=<?= $pub['id_publicacion'] ?>"
                class="btn btn-sm btn-danger"
                onclick="return confirm('¿Estás seguro de eliminar esta publicación?');">Eliminar</a>
            </div>
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
      <?php endforeach; ?>
    <?php else: ?>
      <p class="text-center text-muted">No has creado publicaciones aún.</p>
    <?php endif; ?>
  </div>

  <script src="<?= $urlBase ?>peticiones(js)/mandarMetricas.js.php?vista=<?= urlencode(basename($_SERVER['PHP_SELF'])) ?>"></script>
  <script>
    function validarFormulario() {
      const titulo = document.querySelector('input[name="titulo"]').value.trim();
      const contenido = document.querySelector('textarea[name="contenido"]').value.trim();

      if (titulo.length < 3) {
        alert("El título debe tener al menos 3 caracteres.");
        return false;
      }

      if (contenido.length < 5) {
        alert("El contenido debe tener al menos 5 caracteres.");
        return false;
      }

      return true;
    }

    function validarEdicion(id) {
      const titulo = document.getElementById('titulo-' + id).value.trim();
      const contenido = document.getElementById('contenido-' + id).value.trim();

      if (titulo.length < 3) {
        alert("El título debe tener al menos 3 caracteres.");
        return false;
      }

      if (contenido.length < 5) {
        alert("El contenido debe tener al menos 5 caracteres.");
        return false;
      }

      return true;
    }

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

    // Mostrar formulario edición y ocultar texto
    document.querySelectorAll('.btn-toggle-edit').forEach(btn => {
      btn.addEventListener('click', () => {
        const id = btn.getAttribute('data-id');
        document.getElementById('edit-form-' + id).classList.remove('d-none');
        document.getElementById('titulo-text-' + id).style.display = 'none';
        document.getElementById('contenido-text-' + id).style.display = 'none';
        btn.style.display = 'none'; // Oculta botón editar mientras editas
      });
    });

    // Cancelar edición
    document.querySelectorAll('.btn-cancel').forEach(btn => {
      btn.addEventListener('click', () => {
        const id = btn.getAttribute('data-id');
        document.getElementById('edit-form-' + id).classList.add('d-none');
        document.getElementById('titulo-text-' + id).style.display = 'block';
        document.getElementById('contenido-text-' + id).style.display = 'block';
        document.querySelector('.btn-toggle-edit[data-id="' + id + '"]').style.display = 'inline-block';
      });
    });
  </script>
</body>

</html>