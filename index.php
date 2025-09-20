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
  <link rel="stylesheet" href="<?= $urlBase ?>/css/estilos.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  <script src="<?= $urlBase ?>/peticiones(js)/mandarMetricas.js.php?vista=<?= urlencode(basename($_SERVER['PHP_SELF'])) ?>"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="<?= $urlBase ?>/peticiones(js)/likesContar.js"></script>
  <meta name="google-adsense-account" content="ca-pub-6265821190577353">
  <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-6265821190577353" crossorigin="anonymous"></script>
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/usuaria/navbar.php'; ?>
</head>

<body class="bg-white text-black">

  <!-- Carrusel de imágenes -->
  <div id="mainCarousel" class="carousel slide carousel-fade main-carousel" data-bs-ride="carousel" data-bs-interval="4000">
    <div class="carousel-inner">

      <!-- Slide 1 -->
      <div class="carousel-item active" style="background-image: url('https://picsum.photos/id/1018/1920/1080');">
        <div class="carousel-caption">
          <h1>Título del Slide 1</h1>
          <p>Este es un texto de ejemplo para el primer apartado del carrusel.</p>
          <a href="#" class="btn btn-primary btn-main-carousel">Acción 1</a>
        </div>
      </div>

      <!-- Slide 2 -->
      <div class="carousel-item" style="background-image: url('https://picsum.photos/id/1015/1920/1080');">
        <div class="carousel-caption">
          <h1>Título del Slide 2</h1>
          <p>Aquí puedes colocar otra descripción o contenido principal.</p>
          <a href="#" class="btn btn-danger btn-main-carousel">Acción 2</a>
        </div>
      </div>

      <!-- Slide 3 -->
      <div class="carousel-item" style="background-image: url('https://picsum.photos/id/1019/1920/1080');">
        <div class="carousel-caption">
          <h1>Título del Slide 3</h1>
          <p>Texto para destacar algún servicio o producto importante.</p>
          <a href="#" class="btn btn-success btn-main-carousel">Acción 3</a>
        </div>
      </div>

    </div>

    <!-- Controles (opcionales, puedes quitarlos si quieres que sea solo automático) -->
    <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Anterior</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Siguiente</span>
    </button>
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
  <section class="py-3 py-md-5 mb-3">
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

  <!-- Testimonios -->
  <div class="justify-content-center">
    <div class="container py-5">
      <h2 class="text-center mb-4">Ellas ya vivieron la experiencia</h2>
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
              <?php foreach ($testimonios as $index => $item): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                  <div class="opiniones p-4 p-md-5">
                    <i class="bi bi-quote quote-icon position-absolute top-0 start-0 mt-3 ms-3"></i>
                    <div class="text-center mb-4">
                      <?php
                      $foto = $item['foto'];
                      $src = $foto ? 'data:image/jpeg;base64,' . base64_encode($foto) : 'https://cdn1.iconfinder.com/data/icons/avatar-3/512/Secretary-512.png';
                      ?>
                      <img src="<?= $src ?>" class="fotoOpinion mb-3" alt="Especialista">
                      <h5 class="cust-name"><?= htmlspecialchars($item['nombre'] ?? 'Usuaria') ?></h5>
                    </div>
                    <p class="mb-2"><?= htmlspecialchars($item['opinion']) ?></p>
                    <div class="d-flex flex-column align-items-center">
                      <span class="custom-rating mt-2">Calificación: <?= $item['calificacion'] ?> <i class="bi bi-star-fill text-warning"></i></span>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Next</span>
            </button>
            <div class="carousel-indicators">
              <?php foreach ($testimonios as $index => $item): ?>
                <button type="button"
                  data-bs-target="#testimonialCarousel"
                  data-bs-slide-to="<?= $index ?>"
                  class="<?= $index === 0 ? 'active' : '' ?>"
                  aria-current="<?= $index === 0 ? 'true' : 'false' ?>"
                  aria-label="Slide <?= $index + 1 ?>">
                </button>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

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

  <script src="peticiones(js)/testimonios.js"></script>

  <?php
  include $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/usuaria/footer.php';
  ?>

  <!-- Scripts -->
  <script src="peticiones(js)/return.js"></script>
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
</body>

</html>