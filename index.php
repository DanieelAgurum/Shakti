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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <!-- Scripts únicos -->
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/usuaria/navbar.php'; ?>
</head>

<body>

  <!-- Banner principal -->
  <div id="myCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <div class="mask d-flex align-items-center">
          <div class="container">
            <div class="row align-items-center">
              <div class="col-md-7 col-12 order-md-1 order-2">
                <h4>Misión</h4>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto ut perferendis aliquid eum eaque reprehenderit iste aspernatur tempora?
                  Quos, fugit. Nostrum sed temporibus quam cumque perferendis, laudantium amet tempora culpa!</p>
              </div>
              <div class="col-md-5 col-12 order-md-2 order-1">
                <img src="img/undraw_online-community_3o0l.svg" class="d-block mx-auto img-fluid" alt="slide">
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="carousel-item">
        <div class="mask d-flex align-items-center">
          <div class="container">
            <div class="row align-items-center">
              <div class="col-md-7 col-12 order-md-1 order-2">
                <h4>Visión</h4>
                <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Dignissimos, error voluptate.
                  Maiores tempore et excepturi optio reiciendis commodi ipsam eos nam, nesciunt quae officiis facere, eum ab, incidunt esse numquam.</p>
              </div>
              <div class="col-md-5 col-12 order-md-2 order-1">
                <img src="img/Mind map-bro.svg" class="d-block mx-auto img-fluid" alt="slide">
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="carousel-item">
        <div class="mask d-flex align-items-center">
          <div class="container">
            <div class="row align-items-center">
              <div class="col-md-7 col-12 order-md-1 order-2">
                <h4>Nuestros valores</h4>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Odit perferendis, doloremque, itaque reprehenderit,
                  dolorum rerum hic fugit dicta aliquid sunt eius deserunt sint corrupti odio consectetur mollitia modi reiciendis officiis!</p>
              </div>
              <div class="col-md-5 col-12 order-md-2 order-1">
                <img src="img/undraw_chat-bot_c8iw.svg" class="d-block mx-auto img-fluid" alt="slide">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Controles -->
    <button class="carousel-control-prev" type="button" data-bs-target="#myCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#myCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>

  <!-- Contenedor inicial de información del sitio web -->
  <section id="about" class="py-5 bg-light">
    <div class="container">
      <!-- Título de la sección -->
      <div class="row mb-4">
        <div class="col text-center">
          <h2 class="display-5 fw-bold">Acerca de Nuestro Sitio Web</h2>
          <p class="lead text-muted">Conoce lo que hacemos y cómo podemos ayudarte</p>
        </div>
      </div>

      <!-- Contenido principal -->
      <div class="row align-items-center">
        <!-- Imagen o ilustración -->
        <div class="col-md-6 mb-4 mb-md-0">
          <img src="img/undraw_chat-bot_c8iw.svg" class="img-fluid rounded shadow" alt="Imagen representativa del sitio">
        </div>

        <!-- Texto informativo -->
        <div class="col-md-6">
          <p>
            Bienvenido a nuestro sitio web, diseñado para ofrecerte una experiencia intuitiva y segura.
            Aquí encontrarás recursos, información y herramientas que te permitirán explorar y aprender
            de manera eficiente.
          </p>
          <p>
            Nuestro objetivo es proporcionarte contenido de calidad, servicios confiables y soporte
            continuo, adaptado a tus necesidades y al ritmo de tu aprendizaje o uso diario.
          </p>
          <div class="d-flex justify-content-center mt-3">
            <a href="#" class="btn btn-banner">Contáctanos</a>
          </div>
        </div>
      </div>
    </div>
  </section>

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
  <section class="py-3 py-md-5 bg-light">
    <div class="container">
      <div class="row gy-5 gy-lg-0 align-items-lg-center">
        <div class="col-12 col-lg-6">
          <img class="img-fluid rounded animate__animated animate__fadeInLeft  animate__delay-1s animate__slow animacion" loading="lazy" src="img/Shrug-bro.svg" alt="¿Comó podemos ayudarte?">
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
  <div class="container">
    <div class="row align-items-center" style="min-height: 500px;">
      <!-- Carrusel vertical personalizado -->
      <div class="col-md-6 d-flex justify-content-center mt-4">
        <div class="my-vertical-carousel" id="myVerticalCarousel">
          <div class="my-carousel-wrapper">
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
              <div class="my-carousel-item">
                <div class="testimonial-card">
                  <img src="<?= $src ?>" alt="Foto de <?= $nombre ?>">
                  <h5><?= $nombre ?></h5>
                  <div class="testimonial-stars"><?= $estrellas ?></div>
                  <p class="testimonial-text"><?= $opinion ?></p>
                  <span class="see-more">Ver más</span>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
          <div class="my-carousel-controls">
            <button class="my-carousel-btn prev">&uarr;</button>
            <button class="my-carousel-btn next">&darr;</button>
          </div>
        </div>
      </div>

      <!-- Formulario -->
      <div class="col-md-6 d-flex justify-content-center">
        <div class="rating-container text-center bg-light">
          <div class="rating-emoji"><img src="img/emoji-emoticon-happy-svgrepo-com.svg" class="w-25" alt=""></div>
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
              <textarea class="form-control bg-light" rows="3" id="opinion" name="opinion" placeholder="Cuéntanos tu experiencia"></textarea>
            </div>
            <input type="hidden" name="opcion" value="1">
            <button type="submit" class="submit-rating bg-dark mt-3 btn btn-dark likes-count">Enviar calificación</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <?php
  include $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/usuaria/footer.php';
  ?>

  <!-- Scripts -->
  <script src="peticiones(js)/carruselTestimonios.js"></script>
  <script src="peticiones(js)/testimonios.js"></script>
  <script src="peticiones(js)/return.js"></script>

</body>

</html>