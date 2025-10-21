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
  header("Location: " . $urlBase . "vista/admin/");
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
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

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
                <h4>Nuestro objetivo</h4>
                <p>Promover la apertura emocional en hombres a través de un chatbot inteligente que brinde
                  apoyo inicial y motive la búsqueda de ayuda profesional.</p>
              </div>
              <div class="col-md-5 col-12 order-md-2 order-1">
                <img src="img/Chat bot-amico.svg" class="d-block mx-auto img-fluid" alt="slide">
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
                <h4>Misión</h4>
                <p>Empoderar a los hombres para que fortalezcan su bienestar emocional y mental mediante herramientas educativas,
                  interactivas y seguras, fomentando el aprendizaje, la reflexión y la conexión con una comunidad de apoyo confiable.
                  Buscamos brindar acompañamiento accesible que transforme hábitos, promueva la resiliencia y favorezca la salud integral.</p>
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
                <p>Ser la plataforma líder en apoyo emocional y educativo para hombres, reconocida por su enfoque inclusivo,
                  tecnología innovadora y compromiso con el bienestar integral.
                  Queremos crear una comunidad activa donde cada usuario se sienta acompañado, escuchado y motivado a crecer personal y emocionalmente.</p>
              </div>
              <div class="col-md-5 col-12 order-md-2 order-1">
                <img src="img/undraw_text-messages_978a.svg" class="d-block mx-auto img-fluid" alt="slide">
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
                <p>Empatía: Te escuchamos y te acompañamos.<br>

                  Confianza: Espacio seguro y privado.<br>

                  Comunidad: Conecta y apoya a otros como tú.</p>
              </div>
              <div class="col-md-5 col-12 order-md-2 order-1">
                <img src="img/Mind map-bro.svg" class="d-block mx-auto img-fluid" alt="slide">
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
      <div class="row mb-4" data-aos="fade-down">
        <div class="col text-center">
          <h2 class="display-5 fw-bold">Acerca de NexoH</h2>
          <p class="lead text-muted">Conoce lo que hacemos y cómo podemos ayudarte</p>
        </div>
      </div>

      <div class="row align-items-center">
        <div class="col-md-6 mb-4 mb-md-0" data-aos="fade-right">
          <img src="img/NexoH.png" class="img-fluid rounded shadow" alt="NexoH">
        </div>

        <div class="col-md-6" data-aos="fade-left">
          <p>
            Bienvenido a <strong>NexoH</strong>, tu espacio seguro para fortalecer tu bienestar emocional y mental.
            Aquí encontrarás herramientas interactivas, recursos prácticos y contenidos que te ayudarán a crecer
            día a día, aprender sobre ti mismo y conectar con una comunidad de apoyo.
          </p>
          <p>
            En <strong>NexoH</strong> creemos que cada paso hacia tu desarrollo personal cuenta.
            Nuestro objetivo es acompañarte con información confiable, servicios accesibles y soporte constante,
            adaptados a tu ritmo y necesidades.
          </p>
          <div class="d-flex justify-content-center mt-3" data-aos="zoom-in">
            <a href="#" class="btn btn-banner">Contáctanos</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Bienvenida -->
  <main class="hero p-5 text-center bg-light" data-aos="fade-up" data-aos-duration="1200">
    <?php if (!empty($_SESSION['correo'])): ?>
      <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Vista/usuaria/inicioUser.php'; ?>
    <?php else: ?>
      <div data-aos="zoom-in" data-aos-delay="200">
        <h1 class="fw-bold mb-3">Bienvenido a Nuestro Sitio <span class="text-primary">Shakti</span></h1>
        <p class="lead text-muted mb-4">Tu bienestar es primero</p>
      </div>

      <div class="hero-buttons d-flex flex-wrap justify-content-center" data-aos="fade-up" data-aos-delay="400">
        <a href="<?= $urlBase ?>/Vista/Contenido_1.php"
          class="btn btn-primary me-2 mt-2 mb-2 px-4 py-2 rounded-pill shadow-sm hover-scale">
          Más contenido...
        </a>
        <a href="<?= $urlBase ?>/Vista/Instituciones.php"
          class="btn btn-outline-secondary mt-2 mb-2 px-4 py-2 rounded-pill shadow-sm hover-scale">
          Instituciones
        </a>
        <a href="<?= $urlBase ?>/Vista/Sonidos.php"
          class="btn btn-outline-secondary mt-2 mb-2 px-4 py-2 rounded-pill shadow-sm hover-scale">
          Sonidos Relajantes
        </a>
      </div>
      <hr class="my-5">
    <?php endif; ?>
  </main>

  <!-- FAQ - ¿Cómo podemos ayudarte? -->
  <section class="py-3 py-md-5 bg-light" id="faq" data-aos="fade-up" data-aos-duration="1000">
    <div class="container">
      <div class="row gy-5 gy-lg-0 align-items-lg-center">

        <!-- Imagen con animación desde la izquierda -->
        <div class="col-12 col-lg-6 text-center" data-aos="fade-right" data-aos-duration="1200">
          <img
            class="img-fluid rounded shadow-sm"
            loading="lazy"
            src="img/Shrug-bro.svg"
            alt="¿Cómo podemos ayudarte?"
            style="max-width: 90%; border-radius: 20px;">
        </div>

        <!-- Contenido del FAQ -->
        <div class="col-12 col-lg-6" data-aos="fade-left" data-aos-duration="1200">
          <div class="row justify-content-xl-end">
            <div class="col-12 col-xl-11">

              <!-- Título -->
              <h2 class="h1 mb-3 fw-bold" data-aos="zoom-in" data-aos-delay="200">
                ¿Cómo podemos ayudarte?
              </h2>

              <!-- Descripción -->
              <p class="lead text-secondary mb-5" data-aos="fade-up" data-aos-delay="400">
                Esperamos que hayas encontrado una respuesta a tu pregunta.
                Si necesitas ayuda, por favor busca tu consulta en nuestro Centro de Soporte o contáctanos por correo electrónico.
              </p>

              <!-- Carrusel de preguntas -->
              <div id="carouselAcordeon" class="carousel slide" data-aos="zoom-in" data-aos-delay="600">
                <div class="carousel-inner">
                  <?php
                  require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Controlador/preguntasFrecuentesCtrl.php';
                  $preg = new preguntasFrecuentesMdl();
                  $preg->conectarBD();
                  $preg->mostrarTodas();
                  ?>
                </div>
              </div>

              <!-- Controles -->
              <div class="d-flex justify-content-center mt-4 gap-3" data-aos="fade-up" data-aos-delay="800">
                <button
                  class="btn btn-outline-dark d-flex align-items-center justify-content-center rounded-circle shadow-sm"
                  type="button" data-bs-target="#carouselAcordeon" data-bs-slide="prev"
                  style="width: 50px; height: 50px;">
                  <i class="fas fa-arrow-left"></i>
                </button>
                <button
                  class="btn btn-outline-dark d-flex align-items-center justify-content-center rounded-circle shadow-sm"
                  type="button" data-bs-target="#carouselAcordeon" data-bs-slide="next"
                  style="width: 50px; height: 50px;">
                  <i class="fas fa-arrow-right"></i>
                </button>
              </div>

            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- Testimonios -->
  <section id="testimonios" class="py-5 bg-white" data-aos="fade-up" data-aos-duration="1000">
    <div class="container">
      <div class="row align-items-center" style="min-height: 500px;">

        <!-- Carrusel vertical personalizado -->
        <div class="col-md-6 d-flex justify-content-center mt-4" data-aos="fade-right" data-aos-delay="200">
          <div class="my-vertical-carousel" id="myVerticalCarousel">
            <div class="my-carousel-wrapper">
              <?php foreach ($testimonios as $item): ?>
                <?php
                $foto = $item['foto'];
                $src = $foto
                  ? 'data:image/jpeg;base64,' . base64_encode($foto)
                  : 'img/undraw_chill-guy-avatar_tqsm.svg';
                $nombre = htmlspecialchars($item['nombre'] ?? 'Usuaria');
                $opinion = htmlspecialchars($item['opinion']);
                $calificacion = (int)$item['calificacion'];
                $estrellas = str_repeat("★", $calificacion) . str_repeat("☆", 5 - $calificacion);
                ?>
                <div class="my-carousel-item" data-aos="zoom-in" data-aos-delay="300">
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

            <div class="my-carousel-controls mt-3 d-flex flex-column align-items-center gap-2">
              <button class="my-carousel-btn prev btn btn-outline-dark rounded-circle shadow-sm" style="width:45px;height:45px;">
                &uarr;
              </button>
              <button class="my-carousel-btn next btn btn-outline-dark rounded-circle shadow-sm" style="width:45px;height:45px;">
                &darr;
              </button>
            </div>
          </div>
        </div>

        <!-- Formulario -->
        <div class="col-md-6 d-flex justify-content-center" data-aos="fade-left" data-aos-delay="300">
          <div class="rating-container text-center bg-light">
            <div class="rating-emoji" data-aos="zoom-in" data-aos-delay="400">
              <img src="img/emoji-emoticon-happy-svgrepo-com.svg" class="w-25" alt="">
            </div>
            <h3 class="rating-title" data-aos="fade-up" data-aos-delay="500">¿Cómo fue tu experiencia?</h3>

            <form id="formCalificacion" data-aos="fade-up" data-aos-delay="600">
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

              <div class="rating-feedback mt-3" data-aos="fade-up" data-aos-delay="700">
                <textarea class="form-control bg-light" rows="3" id="opinion" name="opinion" placeholder="Cuéntanos tu experiencia"></textarea>
              </div>

              <input type="hidden" name="opcion" value="1">
              <button type="submit" class="submit-rating bg-dark mt-3 btn btn-dark likes-count" data-aos="fade-up" data-aos-delay="800">
                Enviar calificación
              </button>
            </form>
          </div>
        </div>

      </div>
    </div>
  </section>

  <?php
  include $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/usuaria/footer.php';
  ?>

  <!-- Scripts -->
  <script src="peticiones(js)/carruselTestimonios.js"></script>
  <script src="peticiones(js)/testimonios.js"></script>
  <script src="peticiones(js)/return.js"></script>

  <script>
    AOS.init({
      duration: 1000, // Duración de la animación
      once: true, // Solo se anima una vez
      offset: 120, // Distancia antes de activarse
    });
  </script>


</body>

</html>