<?php
require_once 'Modelo/testimoniosMdl.php';
date_default_timezone_set('America/Mexico_City');
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

include_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/obtenerLink/obtenerLink.php';
$urlBase = getBaseUrl();

$temp = new Testimonios(null);
$db = $temp->conectarBD();
$testimonio = new Testimonios($db);
$testimonios = $testimonio->obtenerTestimonios();

if (isset($_SESSION['id_rol']) && $_SESSION['id_rol'] == 3) {
  header("Location: shakti/Vista/admin");
  exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Shakti</title>
  <!-- Librerías adicionales en el head del navbar -->
  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.theme.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <!-- Scripts únicos -->
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  <!-- Navbar -->
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/usuaria/navbar.php'; ?>
</head>

<body class="text-black">

  <!-- Banner principal -->
  <div class="main-banner">
    <div class="banner-caption">
      <h1>Título principal</h1>
      <p>Este es un texto de ejemplo para tu banner inicial, ideal para destacar tu contenido.</p>
      <a href="#" class="btn-banner">Explorar más</a>
    </div>
  </div>

  <!-- Bienvenida -->
  <main class="hero p-5 text-center">
    <?php if (!empty($_SESSION['correo'])): ?>
      <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Vista/usuaria/inicioUser.php'; ?>
    <?php else: ?>
      <h1>Bienvenido a Nuestro Sitio Shakti</h1>
      <p class="lead">Tu bienestar es primero</p>
      <div class="hero-buttons d-flex flex-wrap justify-content-center">
        <a href="<?= $urlBase ?>/Vista/contenido.php" class="btn btn-primary me-2 mt-2 mb-2 w-45">Más contenido...</a>
        <a href="<?= $urlBase ?>/Vista/organizacionVista.php" class="btn btn-outline-secondary mt-2 mb-2 w-45">Organizaciones</a>
      </div>
    <?php endif; ?>
  </main>

  <!-- FAQ 1 - Bootstrap Brain Component -->
  <section class="py-3 py-md-5">
    <div class="container">
      <div class="row gy-5 gy-lg-0 align-items-lg-center">
        <div class="col-12 col-lg-6">
          <img class="img-fluid rounded animate__animated animate__fadeInLeft  animate__delay-1s animate__slow animacion" loading="lazy" src="img/question-41.svg" alt="How can we help you?">
        </div>
        <div class="col-12 col-lg-6 animate__animated animate__fadeInRight  animate__delay-1s animate__slow animacion">
          <div class="row justify-content-xl-end">
            <div class="col-12 col-xl-11">
              <h2 class="h1 mb-3">¿Cómo podemos ayudarte?</h2>
              <p class="lead text-secondary mb-5">
                Esperamos que hayas encontrado una respuesta a tu pregunta. Si necesitas ayuda, por favor busca tu consulta en nuestro Centro de Soporte o contáctanos por correo electrónico.
              </p>
              <div id="carouselAcordeon" class="carousel slide">
                <div class="carousel-inner">
                  <?php
                  require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Controlador/preguntasFrecuentesCtrl.php';
                  $preg = new preguntasFrecuentesMdl();
                  $preg->conectarBD();
                  $preg->mostrarTodas();
                  ?>
                </div>
              </div>

              <div class="d-flex justify-content-center mt-4 gap-3">
                <button class="btn btn-black text-white d-flex align-items-center justify-content-center square-button"
                  type="button" data-bs-target="#carouselAcordeon" data-bs-slide="prev">
                  <i class="fas fa-arrow-left" style="color: black;"></i>
                </button>
                <button class="btn btn-black text-white d-flex align-items-center justify-content-center square-button"
                  type="button" data-bs-target="#carouselAcordeon" data-bs-slide="next">
                  <i class="fas fa-arrow-right" style="color: black;"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="w-100 p-4 rounded-3 mb-4">
    <div class="testimonial-container">
      <div class="testimonial-track">
        <?php foreach ($testimonios as $item): ?>
          <?php
          // Foto: si no tiene, usar imagen por defecto
          $foto = $item['foto'];
          $src = $foto
            ? 'data:image/jpeg;base64,' . base64_encode($foto)
            : 'https://cdn1.iconfinder.com/data/icons/avatar-3/512/Secretary-512.png';

          // Nombre
          $nombre = htmlspecialchars($item['nombre'] ?? 'Usuaria');

          $opinion = htmlspecialchars($item['opinion']);

          $calificacion = (int)$item['calificacion'];
          $estrellas = str_repeat("★", $calificacion) . str_repeat("☆", 5 - $calificacion);
          ?>

          <div class="testimonial-card">
            <img src="<?= $src ?>" alt="Foto de <?= $nombre ?>">
            <h3><?= $nombre ?></h3>
            <p><?= $opinion ?></p>
            <div class="testimonial-stars"><?= $estrellas ?></div>
          </div>
        <?php endforeach; ?>

        <!-- Duplicamos para simular el loop infinito -->
        <?php foreach ($testimonios as $item): ?>
          <?php
          $foto = $item['foto'];
          $src = $foto
            ? 'data:image/jpeg;base64,' . base64_encode($foto)
            : 'https://cdn1.iconfinder.com/data/icons/avatar-3/512/Secretary-512.png';

          $nombre = htmlspecialchars($item['nombre'] ?? 'Usuaria');
          $opinion = htmlspecialchars($item['opinion']);
          $calificacion = (int)$item['calificacion'];
          $estrellas = str_repeat("★", $calificacion) . str_repeat("☆", 5 - $calificacion);
          ?>

          <div class="testimonial-card">
            <img src="<?= $src ?>" alt="Foto de <?= $nombre ?>">
            <h3><?= $nombre ?></h3>
            <p><?= $opinion ?></p>
            <div class="testimonial-stars"><?= $estrellas ?></div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>


  <!-- Formulario calificación al sistema -->
  <div class="container">
    <div class="rating-container text-center">
      <div class="rating-emoji"><img src="img/2764.svg" class="w-25" alt=""></div>
      <h3 class="rating-title">¿Cómo fue tu experiencia?</h3>
      <form id="formCalificacion">
        <div class="container d-flex justify-content-center">
          <div class="row">
            <div class="col-md-12">
              <div class="stars likes-count">
                <input class="star star-1" id="star-1" type="radio" value="5" name="calificacion" />
                <label class="star star-1" for="star-1"></label>
                <input class="star star-2" id="star-2" type="radio" value="4" name="calificacion" />
                <label class="star star-2" for="star-2"></label>
                <input class="star star-3" id="star-3" type="radio" value="3" name="calificacion" />
                <label class="star star-3" for="star-3"></label>
                <input class="star star-4" id="star-4" type="radio" value="2" name="calificacion" />
                <label class="star star-4" for="star-4"></label>
                <input class="star star-5" id="star-5" type="radio" value="1" name="calificacion" />
                <label class="star star-5" for="star-5"></label>
              </div>
            </div>
          </div>
        </div>
        <div class="rating-feedback mt-3">
          <textarea class="form-control" rows="3" id="opinion" name="opinion" placeholder="Cuéntanos tu experiencia"></textarea>
        </div>
        <input type="hidden" name="opcion" value="1">
        <button type="submit" class="submit-rating bg-dark mt-3 btn btn-dark likes-count">Enviar calificación</button>
      </form>
    </div>
  </div>

  <?php
  include $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/usuaria/footer.php';
  ?>

  <!-- Scripts -->
  <script src="peticiones(js)/testimonios.js"></script>
  <script src="peticiones(js)/return.js"></script>
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
</body>

</html>