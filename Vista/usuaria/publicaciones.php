<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/obtenerLink/obtenerLink.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Modelo/publicacionModelo.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Modelo/likeModelo.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Modelo/comentariosModelo.php';
$urlBase = getBaseUrl();

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

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
  <title>Publicaciones - NexoH</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/usuaria/navbar.php'; ?>
  <style>
    .respuestas {
      margin-left: 1rem;
      border-left: 2px solid #ddd;
      padding-left: 1rem;
    }

    .input-error {
      border: 2px solid red;
      background-color: #ffe6e6;
    }
  </style>
</head>

<body>

  <div class="container mt-5">

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/shakti/Vista/usuaria/lenguaje.php'; ?>

    <!-- Formulario de publicación -->
    <div class="card mb-4 shadow-sm">
      <div class="card-body">
        <h5 class="card-title">Crear publicación</h5>
        <form method="POST" action="<?= $urlBase ?>Controlador/publicacionControlador.php" onsubmit="return validarFormulario();">
          <div class="mb-3">
            <input type="text" class="form-control mb-2" name="titulo" placeholder="Título de tu publicación" minlength="3" required>
            <textarea class="form-control" name="contenido" rows="3" placeholder="¿Qué estás pensando?" minlength="5" required></textarea>
          </div>
          <div class="form-check form-switch form-check-reverse">
            <input class="form-check-input" name="anonima" value="1" type="checkbox" id="switchCheckReverse">
            <label class="form-check-label" for="switchCheckReverse">Publicar de forma anónima</label>
          </div>
          <input type="hidden" name="guardar_publicacion" value="1" />
          <button type="submit" id="btnPublicar" class="btn btn-outline-primary">
            <i class="bi bi-check2-circle"></i> Publicar
          </button>
        </form>
      </div>
    </div>

    <!-- Lista de publicaciones -->
    <?php if (count($publicaciones) > 0): ?>

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

            <form class="edit-form d-none" id="edit-form-<?= $pub['id_publicacion'] ?>" method="POST" action="<?= $urlBase ?>Controlador/publicacionControlador.php" onsubmit="return validarEdicion(<?= $pub['id_publicacion'] ?>)">
              <input type="hidden" name="editar_publicacion" value="1" />
              <input type="hidden" name="id_publicacion" value="<?= $pub['id_publicacion'] ?>" />
              <input type="text" class="form-control mb-2" name="titulo" id="titulo-<?= $pub['id_publicacion'] ?>" value="<?= htmlspecialchars($pub['titulo']) ?>" minlength="3" required>
              <textarea class="form-control mb-2" name="contenido" id="contenido-<?= $pub['id_publicacion'] ?>" rows="3" minlength="5" required><?= htmlspecialchars($pub['contenido']) ?></textarea>
              <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-outline-primary btn-guardar">
                  <i class="bi bi-check2-circle"></i> Guardar
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary btn-cancel" data-id="<?= $pub['id_publicacion'] ?>"><i class="bi bi-x-lg"></i> Cancelar</button>
              </div>
            </form>
          </div>

          <div class="card-footer d-flex justify-content-between align-items-center">
            <div>
              <?php
              $likes = $likeModelo->contarLikes($pub['id_publicacion']);
              $yaDioLike = $likeModelo->usuarioYaDioLike($id_usuaria, $pub['id_publicacion']);
              $btnLikeClass = $yaDioLike ? 'btn-danger' : 'btn-outline-danger';
              ?>
              <div class="d-grid gap-2 d-md-flex">
                <button class="btn btn-sm <?= $btnLikeClass ?>  btn-like" data-id="<?= $pub['id_publicacion'] ?>">
                  <i class="bi heart-icon <?= $yaDioLike ? 'bi-suit-heart-fill' : 'bi-suit-heart' ?>"></i>
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
            </div>
            <div>
              <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button class="btn btn-sm btn-outline-success btn-toggle-edit" data-id="<?= $pub['id_publicacion'] ?>"><i class="bi bi-pencil-square"></i> Editar</button>
                <button class="btn btn-sm btn-outline-danger btn-eliminar-publicacion"
                  data-id="<?= $pub['id_publicacion'] ?>">
                  <i class="bi bi-trash3"></i> Eliminar
                </button>
              </div>
            </div>
          </div>

          <div class="comments-section d-none" id="comments-<?= $pub['id_publicacion'] ?>">
            <div class="existing-comments mb-3">
              <?php if ($comRaiz): ?>
                <?php foreach ($comRaiz as $comentario):
                  $id_comentario = $comentario['id_comentario'];
                  $nombre = htmlspecialchars($comentario['nombre'] ?? 'Anónimo');
                  $contenidoTexto = nl2br(htmlspecialchars($comentario['comentario']));
                  $contenidoPlano = htmlspecialchars($comentario['comentario']);
                  $fecha = !empty($comentario['fecha_comentario']) ? date('d M Y H:i', strtotime($comentario['fecha_comentario'])) : 'Sin fecha';
                  $tiempoComentario = strtotime($comentario['fecha_comentario']);
                  $esAutoraComentario = isset($comentario['id_usuaria']) && $comentario['id_usuaria'] == $id_usuaria;
                  $puedeEditar = $esAutoraComentario && (time() - $tiempoComentario) <= 300;
                  $respuestasCount = $comentarioModelo->contarRespuestasPorPadre($id_comentario);
                ?>
                  <div class="comentario-raiz mb-2 p-2 bg-light rounded position-relative border" id="comentario-<?= $id_comentario ?>">
                    <strong><?= $nombre ?>:</strong> <?= $contenidoTexto ?><br>
                    <small class="text-muted"><?= $fecha ?></small>

                    <button class="btn btn-outline-primary btn-sm btn-responder" data-id="<?= $id_comentario ?>">Responder</button>
                    <div class="ms-4 d-none" id="respuestas-<?= $id_comentario ?>"></div>

                    <?php if ($puedeEditar): ?>
                      <div class="dropdown position-absolute top-0 end-0 mt-2 me-2">
                        <button class="btn btn-sm btn-link p-0 text-dark" type="button" data-bs-toggle="dropdown" title="Opciones">
                          <i class="bi bi-three-dots-vertical fs-5"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                          <li>
                            <button class="dropdown-item btn btn-outline-success btn-sm btn-edit-comentario" data-id="<?= $id_comentario ?>">
                              <i class="bi bi-pencil-square text-success"></i> Editar
                            </button>
                          </li>
                          <li>
                            <button class="dropdown-item btn btn-outline-danger btn-sm btn-eliminar-comentario" data-id="<?= $id_comentario ?>">
                              <i class="bi bi-trash3 text-danger"></i> Eliminar
                            </button>
                          </li>
                        </ul>
                      </div>

                      <form class="edit-comentario-form d-none mt-2" id="edit-form-<?= $id_comentario ?>">
                        <input type="hidden" name="id_comentario" value="<?= $id_comentario ?>">
                        <div class="input-group">
                          <input type="text" class="form-control form-control-sm" name="nuevo_comentario" value="<?= $contenidoPlano ?>" required>
                          <button type="submit" class="btn btn-sm btn-outline-success">
                            <i class="bi bi-check2-circle"></i> Guardar
                          </button>
                        </div>
                      </form>
                    <?php endif; ?>

                    <?php if ($respuestasCount > 0): ?>
                      <button class="btn btn-sm btn-outline-secondary ver-respuestas" data-id="<?= $id_comentario ?>" data-count="<?= $respuestasCount ?>">
                        Ver respuestas (<?= $respuestasCount ?>)
                      </button>
                    <?php endif; ?>
                  </div>
                  <div class="ms-4 mt-2" id="form-responder-<?= $id_comentario ?>"></div>
                <?php endforeach; ?>

              <?php else: ?>
                <p class="text-muted">Aún no hay comentarios.</p>
              <?php endif; ?>
            </div>
            <form class="comment-form" data-id-publicacion="<?= $pub['id_publicacion'] ?>" method="post">
              <input type="hidden" name="opcion" value="1">
              <input type="hidden" name="id_publicacion" value="<?= $pub['id_publicacion'] ?>">
              <input type="hidden" name="id_padre" value="">
              <div class="input-group mb-2">
                <input type="text" name="comentario" class="form-control form-control-sm" placeholder="Escribe un comentario..." required>
                <button type="submit" class="btn btn-sm btn-outline-primary btn-enviar-comentario">
                  Enviar <i class="bi bi-arrow-right-circle"></i>
                </button>
              </div>
            </form>

          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="text-center text-muted">No has creado publicaciones aún.</p>
    <?php endif; ?>
  </div>


  <?php if (isset($_SESSION['sweet_alert'])): ?>
    <script>
      Swal.fire({
        icon: '<?= $_SESSION['sweet_alert']['icon'] ?>',
        title: '<?= $_SESSION['sweet_alert']['title'] ?>',
        text: '<?= $_SESSION['sweet_alert']['text'] ?>',
        timer: 2000,
        showConfirmButton: false
      });
    </script>
    <?php unset($_SESSION['sweet_alert']); ?>
  <?php endif; ?>

  <script src="<?= $urlBase ?>peticiones(js)/publicaciones.js"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="<?= $urlBase ?>peticiones(js)/likesContar.js"></script>
  <script src="<?= $urlBase ?>peticiones(js)/mandarMetricas.js.php?vista=<?= urlencode(basename($_SERVER['PHP_SELF'])) ?>"></script>
  <script src="<?= $urlBase ?>validacionRegistro/abrirComentarios.js"></script>
  <script src="../../peticiones(js)/borrarPublicacion.js"></script>
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/usuaria/footer.php'; ?>

</body>

</html>