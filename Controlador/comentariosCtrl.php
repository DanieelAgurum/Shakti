<?php
date_default_timezone_set('America/Mexico_City');
session_start();
require_once("../Modelo/comentariosModelo.php");

header('Content-Type: application/json');

if (!isset($_SESSION['id_usuaria']) || !isset($_SESSION['nombre'])) {
  echo json_encode(['status' => 'error', 'message' => 'Únete a la comunidad para darle like y comentar']);
  exit;
}

$modelo = new Comentario();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['opcion'])) {
  $opcion = intval($_POST['opcion']);

  switch ($opcion) {
    case 1:
      $comentario = trim($_POST['comentario'] ?? '');
      $id_publicacion = intval($_POST['id_publicacion'] ?? 0);
      $id_padre = isset($_POST['id_padre']) && $_POST['id_padre'] !== '' ? intval($_POST['id_padre']) : null;
      $id_usuaria = $_SESSION['id_usuaria'];
      $nombre = $_SESSION['nombre'];

      if ($comentario && $id_publicacion > 0) {
        $id_comentario = $modelo->agregarComentario($comentario, $id_publicacion, $id_usuaria, $id_padre);

        if ($id_comentario === 'malas_palabras') {
          echo json_encode(['status' => 'error', 'message' => 'malas_palabras']);
          exit;
        }

        if ($id_comentario === 'doxxing') {
          echo json_encode(['status' => 'error', 'message' => 'doxxing']);
          exit;
        }

        if (is_numeric($id_comentario)) {
          require_once("../Modelo/notificacionesModelo.php");
          Notificacion::crearDesdeComentario($id_usuaria, $id_publicacion, $id_comentario, $id_padre);
        }

        if ($id_comentario) {
          echo json_encode([
            'status' => 'ok',
            'id_comentario' => $id_comentario,
            'nombre' => htmlspecialchars($nombre),
            'comentario' => htmlspecialchars($comentario),
            'fecha' => date('d M Y H:i'),
            'id_padre' => $id_padre
          ]);
        } else {
          echo json_encode(['status' => 'error', 'message' => 'No se pudo guardar el comentario']);
        }
      } else {
        echo json_encode(['status' => 'error', 'message' => 'Faltan datos']);
      }
      break;

    case 2:
      $id_comentario = intval($_POST['id_comentario'] ?? 0);
      $nuevo_comentario = trim($_POST['comentario'] ?? '');

      if ($id_comentario > 0 && $nuevo_comentario !== '') {
        $resultado = $modelo->editarComentario($id_comentario, $nuevo_comentario);

        if ($resultado === 'malas_palabras') {
          echo json_encode(['status' => 'error', 'message' => 'malas_palabras']);
          exit;
        }

        echo json_encode(['status' => $resultado ? 'ok' : 'error']);
      } else {
        echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
      }
      break;

    case 3:
      $id_comentario = intval($_POST['id_comentario'] ?? 0);

      if ($id_comentario > 0) {
        $resultado = $modelo->eliminarComentario($id_comentario);
        echo json_encode(['status' => $resultado ? 'ok' : 'error']);
      } else {
        echo json_encode(['status' => 'error', 'message' => 'ID inválido']);
      }
      break;

    default:
      echo json_encode(['status' => 'error', 'message' => 'Opción no válida']);
      break;

    case 4:
      $id_publicacion = intval($_POST['id_publicacion'] ?? 0);
      if ($id_publicacion > 0) {
        $comentarios = $modelo->obtenerComentariosPorPublicacion($id_publicacion);
        $idUsuaria = $_SESSION['id_usuaria'];

        ob_start();
        foreach ($comentarios as $c) {
          $id_comentario = (int)$c['id_comentario'];
          $nombre = ($c['anonimo'] == 1)
            ? "Anónimo"
            : htmlspecialchars($c['nombre']);
          $contenido = nl2br(htmlspecialchars($c['comentario']));
          $fecha = !empty($c['fecha_comentario']) ? date('d M Y H:i', strtotime($c['fecha_comentario'])) : 'Sin fecha';
          $tiempoComentario = strtotime($c['fecha_comentario']);
          $esAutoraComentario = isset($c['id_usuaria']) && $c['id_usuaria'] == $idUsuaria;
          $puedeEditar = $esAutoraComentario && (time() - $tiempoComentario) <= 300;
          $respuestasCount = $modelo->contarRespuestasPorPadre($id_comentario);

          echo "<div class='comentario-raiz mb-2 p-2 bg-light rounded position-relative border' id='comentario-$id_comentario'>
                <strong>$nombre:</strong> $contenido<br>
                <small class='text-muted'>$fecha</small>
                <button class='btn btn-outline-primary btn-sm btn-responder' data-id='$id_comentario'>Responder</button>";

          if ($puedeEditar) {
            echo " <div class='dropdown position-absolute top-0 end-0 mt-2 me-2'>
              <button class='btn btn-sm btn-link p-0 text-dark' type='button' data-bs-toggle='dropdown' title='Opciones' data-bs-toggle='tooltip'>
                <i class='bi bi-three-dots-vertical fs-5'></i>
              </button>
              <ul class='dropdown-menu dropdown-menu-end'>
                <li>
                  <button class='dropdown-item btn btn-outline-success btn-sm btn-edit-comentario' data-id='$id_comentario'>
                    <i class='bi bi-pencil-square text-success'></i> Editar
                  </button>
                </li>
                <li>
                  <button class='dropdown-item btn btn-outline-danger btn-sm btn-eliminar-comentario' data-id='$id_comentario'>
                    <i class='bi bi-trash3 text-danger'></i> Eliminar
                  </button>
                </li>
              </ul>
            </div>

                      <form class='edit-comentario-form d-none mt-2' id='edit-form-$id_comentario'>
                          <input type='hidden' name='id_comentario' value='$id_comentario'>
                          <div class='input-group'>
                              <input type='text' class='form-control form-control-sm' name='nuevo_comentario' value='" . htmlspecialchars($c['comentario']) . "' required>
                              <button type='submit' class='btn btn-sm btn-outline-success'><i class='bi bi-check2-circle'></i> Guardar</button>
                          </div>
                      </form>";
          }

          if ($respuestasCount > 0) {
            echo " <button class='btn btn-sm btn-outline-secondary ver-respuestas' data-id='$id_comentario' data-count='$respuestasCount'>
                        Ver respuestas ($respuestasCount)
                      </button>";
          }
          echo "<div class='d-none' id='respuestas-$id_comentario'></div>";
          echo "<div class='form-responder-container mt-2' id='form-responder-$id_comentario'></div>";
          echo "</div>";
        }
        $htmlComentarios = ob_get_clean();

        echo json_encode(['status' => 'ok', 'html' => $htmlComentarios]);
      } else {
        echo json_encode(['status' => 'error', 'message' => 'ID inválido']);
      }
      break;


    case 5:
      $id_padre = $_POST['id_padre'] ?? 0;
      if ($id_padre) {
        $respuestas = $modelo->obtenerRespuestasPorPadre($id_padre);
        $idUsuaria = $_SESSION['id_usuaria'];

        ob_start();
        foreach ($respuestas as $r) {
          $id_comentario = (int)$r['id_comentario'];
          $nombre = ($r['anonimo'] == 1)
            ? "Anónimo"
            : htmlspecialchars($r['nombre']);
          $contenido = nl2br(htmlspecialchars($r['comentario']));
          $fecha = !empty($r['fecha_comentario']) ? date('d M Y H:i', strtotime($r['fecha_comentario'])) : 'Sin fecha';
          $tiempoComentario = strtotime($r['fecha_comentario']);
          $esAutoraComentario = isset($r['id_usuaria']) && $r['id_usuaria'] == $idUsuaria;
          $puedeEditar = $esAutoraComentario && (time() - $tiempoComentario) <= 300;
          $respuestasCount = $modelo->contarRespuestasPorPadre($id_comentario);

          echo "<div class='respuesta mb-2 p-2 bg-white rounded position-relative border' id='comentario-$id_comentario'>
                    <strong>$nombre:</strong> $contenido<br>
                    <small class='text-muted'>$fecha</small>
                    <button class='btn btn-outline-primary btn-sm btn-responder' data-id='$id_comentario'>Responder</button>";

          if ($puedeEditar) {
            echo " <div class='dropdown position-absolute top-0 end-0 mt-2 me-2'>
              <button class='btn btn-sm btn-link p-0 text-dark' type='button' data-bs-toggle='dropdown' title='Opciones' data-bs-toggle='tooltip'>
                <i class='bi bi-three-dots-vertical fs-5'></i>
              </button>
              <ul class='dropdown-menu dropdown-menu-end'>
                <li>
                  <button class='dropdown-item btn btn-outline-success btn-sm btn-edit-comentario' data-id='$id_comentario'>
                    <i class='bi bi-pencil-square text-success'></i> Editar
                  </button>
                </li>
                <li>
                  <button class='dropdown-item btn btn-outline-danger btn-sm btn-eliminar-comentario' data-id='$id_comentario'>
                    <i class='bi bi-trash3 text-danger'></i> Eliminar
                  </button>
                </li>
              </ul>
            </div>
                      <form class='edit-comentario-form d-none mt-2' id='edit-form-$id_comentario'>
                          <input type='hidden' name='id_comentario' value='$id_comentario'>
                          <div class='input-group'>
                              <input type='text' class='form-control form-control-sm' name='nuevo_comentario' value='" . htmlspecialchars($r['comentario']) . "' required>
                              <button type='submit' class='btn btn-sm btn-outline-success'>
                                <i class='bi bi-check2-circle'></i> Guardar
                              </button>
                          </div>
                      </form>";
          }

          if ($respuestasCount > 0) {
            echo " <button class='btn btn-sm btn-outline-secondary ver-respuestas' data-id='$id_comentario' data-count='$respuestasCount'>
                        Ver respuestas ($respuestasCount)
                      </button>";
          }

          echo "<div class='d-none' id='respuestas-$id_comentario'></div>";
          echo "<div class='form-responder-container mt-2' id='form-responder-$id_comentario'></div>";
          echo "</div>";
        }
        $html = ob_get_clean();

        echo json_encode(['status' => 'ok', 'html' => $html]);
      } else {
        echo json_encode(['status' => 'error']);
      }
      break;
  }
} else {
  echo json_encode(['status' => 'error', 'message' => 'Petición inválida']);
}
