<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/modelo/PublicacionModelo.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/modelo/likeModelo.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/modelo/comentariosModelo.php';

$urlBase = '/Shakti/';

$likeModelo = new likeModelo();
$comentarioModelo = new Comentario();

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
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
      <?php

      function renderComentarios($comentarios, $hijos)
      {
        foreach ($comentarios as $c) {
          $id_comentario = isset($c['id_comentario']) ? (int)$c['id_comentario'] : 0;
          $nombre = htmlspecialchars($c['nombre'] ?? 'Anónimo');
          $contenido = nl2br(htmlspecialchars($c['comentario'] ?? ''));
          $fecha = !empty($c['fecha']) ? date('d M Y H:i', strtotime($c['fecha'])) : 'Sin fecha';

          echo "<div class='mb-2 p-2 bg-light rounded'>
            <strong>{$nombre}:</strong> {$contenido}<br>
            <small class='text-muted'>{$fecha}</small> 
            <button class='btn btn-sm btn-link btn-responder' data-id='{$id_comentario}'>Responder</button>
          </div>";

          echo "<div class='ms-4'>";
          if (isset($hijos[$id_comentario])) {
            renderComentarios($hijos[$id_comentario], $hijos);
          }
          echo "</div>";
        }
      }
      ?>

      <?php foreach ($publicaciones as $pub): ?>
        <?php
        $allCom = $comentarioModelo->obtenerComentariosPorPublicacion($pub['id_publicacion']);
        $totalComentarios = count($allCom);
        $comRaiz = [];
        $comHijos = [];
        foreach ($allCom as $c) {
          $idPadre = $c['id_padre'] ?? null;
          if (is_null($idPadre)) $comRaiz[$c['id_comentario']] = $c;
          else $comHijos[$idPadre][] = $c;
        }
        ?>
        <div class="card mb-3 shadow-sm">
          <div class="card-header d-flex justify-content-between align-items-center">
            <strong class="titulo-text" id="titulo-text-<?= $pub['id_publicacion'] ?>">
              <?= htmlspecialchars($pub['titulo']) ?>
            </strong>
            <small class="text-muted">
              <?= date('d M Y H:i', strtotime($pub['fecha_publicacion'])) ?>
            </small>
          </div>

          <div class="card-body">
            <p class="card-text contenido-text" id="contenido-text-<?= $pub['id_publicacion'] ?>">
              <?= nl2br(htmlspecialchars($pub['contenido'])) ?>
            </p>

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
              <?php
              $likes = $likeModelo->contarLikes($pub['id_publicacion']);
              $yaDioLike = $likeModelo->usuarioYaDioLike($id_usuaria, $pub['id_publicacion']);
              $btnLikeClass = $yaDioLike ? 'btn-danger' : 'btn-outline-danger';
              ?>
              <button class="btn btn-sm <?= $btnLikeClass ?> btn-like" data-id="<?= $pub['id_publicacion'] ?>">
                <i class="bi <?= $yaDioLike ? 'bi-suit-heart-fill' : 'bi-suit-heart' ?>"></i>
                <span class="like-text">Me gusta</span>
                <span class="badge bg-danger likes-count"><?= $likes ?></span>
              </button>
              <button class="btn btn-sm btn-outline-primary btn-toggle-comments" data-id="<?= $pub['id_publicacion'] ?>">
                <i class="bi bi-chat"></i> Comentarios
                <span class="badge bg-primary comentarios-count" id="comentarios-count-<?= $pub['id_publicacion'] ?>">
                  <?= $totalComentarios ?>
                </span>
              </button>
            </div>
            <div>
              <button class="btn btn-sm btn-warning btn-toggle-edit" data-id="<?= $pub['id_publicacion'] ?>">Editar</button>
              <a href="<?= $urlBase ?>Controlador/PublicacionControlador.php?borrar_id=<?= $pub['id_publicacion'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta publicación?');">Eliminar</a>
            </div>
          </div>

          <div class="comments-section mt-3 d-none" id="comments-<?= $pub['id_publicacion'] ?>">
            <div class="existing-comments mb-3">
              <?php if ($allCom) renderComentarios($comRaiz, $comHijos);
              else echo "<p class='text-muted'>No hay comentarios aún.</p>"; ?>
            </div>

            <form class="comment-form" data-id-publicacion="<?= $pub['id_publicacion'] ?>">
              <input type="hidden" name="opcion" value="1">
              <input type="hidden" name="id_publicacion" value="<?= $pub['id_publicacion'] ?>">
              <input type="hidden" name="id_padre" value="">
              <div class="input-group mb-2">
                <input type="text" name="comentario" class="form-control form-control-sm" placeholder="Escribe un comentario..." required>
                <button type="submit" class="btn btn-sm btn-primary">Enviar</button>
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
  <script src="../../validacionRegistro/abrirComentarios.js"></script>
  <script src="../../peticiones(js)/likesContar.js"></script>
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/usuaria/footer.php'; ?>

</body>

</html>