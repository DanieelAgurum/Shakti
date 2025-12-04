<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'Modelo/testimoniosMdl.php';
require_once 'Modelo/contenidoMdl.php';
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

$cont = new Contenido();
$cont->conectarBD();
$contenidoReciente = $cont->obtenerContenidoReciente();

function blobToBase64($blob)
{
  if (!$blob) return "";
  return "data:image/jpeg;base64," . base64_encode($blob);
}
function pdfBlobToBase64($blob)
{
  if (!$blob) return "";
  return "data:application/pdf;base64," . base64_encode($blob);
}

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
  <title>NexoH</title>
  <!-- ================== CSS ================== -->
  <!-- Tipografías / Iconos -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <!-- Animaciones -->
  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css" />
  <!-- Owl Carousel -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.theme.min.css">
  <!-- ================== JS ================== -->
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
  <!-- ================== NAVBAR ================== -->
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/usuaria/navbar.php';?>
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
                <h4 class="carousel-text">Nuestro objetivo</h4>
                <p class="carousel-text">Promover la apertura emocional en hombres a través de un chatbot inteligente que brinde
                  apoyo inicial y motive la búsqueda de ayuda profesional.</p>
              </div>
              <div class="col-md-5 col-12 order-md-2 order-1">
                <img src="img/unnamed-removebg-preview.png" class="d-block mx-auto img-fluid" alt="slide">
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
                <h4 class="carousel-text">Misión</h4>
                <p class="carousel-text">Empoderar a los hombres para que fortalezcan su bienestar emocional y mental mediante herramientas educativas,
                  interactivas y seguras, fomentando el aprendizaje, la reflexión y la conexión con una comunidad de apoyo confiable.
                  Buscamos brindar acompañamiento accesible que transforme hábitos, promueva la resiliencia y favorezca la salud integral.</p>
              </div>
              <div class="col-md-5 col-12 order-md-2 order-1">
                <img src="img/Gemini_Generated_Image_bzxazfbzxazfbzxa-removebg-preview.png" class="d-block mx-auto img-fluid" alt="slide">
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
                <h4 class="carousel-text">Visión</h4>
                <p class="carousel-text">Ser la plataforma líder en apoyo emocional y educativo para hombres, reconocida por su enfoque inclusivo,
                  tecnología innovadora y compromiso con el bienestar integral.
                  Queremos crear una comunidad activa donde cada usuario se sienta acompañado, escuchado y motivado a crecer personal y emocionalmente.</p>
              </div>
              <div class="col-md-5 col-12 order-md-2 order-1">
                <img src="img/Gemini_Generated_Image_ong2lvong2lvong2-removebg-preview.png" class="d-block mx-auto img-fluid" alt="slide">
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
                <h4 class="carousel-text">Nuestros valores</h4>
                <p class="carousel-text">Empatía: Te escuchamos y te acompañamos.<br>

                  Confianza: Espacio seguro y privado.<br>

                  Comunidad: Conecta y apoya a otros como tú.</p>
              </div>
              <div class="col-md-5 col-12 order-md-2 order-1">
                <img src="img/Gemini_Generated_Image_7vskkq7vskkq7vsk-removebg-preview.png" class="d-block mx-auto img-fluid" alt="slide">
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
        <div class="col-md-6 mb-4 mb-md-0" data-aos="fade-up">
          <div class="container w-50">
            <img src="img/NexoH.png" class="img-fluid rounded shadow" alt="NexoH">
          </div>
        </div>

        <div class="col-md-6" data-aos="fade-up">
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
            <a href="<?= $urlBase ?>Vista/contacto" class="btn btn-banner">Contáctanos</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Bienvenida -->
  <main class="hero p-5 text-center bg-light" data-aos="fade-up" data-aos-duration="1200">
    <?php if (!empty($_SESSION['correo'])): ?>
      <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Vista/usuaria/inicioUser.php'; ?>
    <?php else: ?>
      <div data-aos="zoom-in" data-aos-delay="200">
        <h1 class="fw-bold mb-3">Bienvenido a Nuestro Sitio <span class="text-primary">NexoH</span></h1>
        <p class="lead text-muted mb-4">Tu bienestar es primero</p>
      </div>

      <div class="hero-buttons d-flex flex-wrap justify-content-center" data-aos="fade-up" data-aos-delay="400">
        <a href="#contenido"
          class="btn btn-primary me-2 mt-2 mb-2 px-4 py-2 rounded-pill shadow-sm hover-scale">
          Más contenido...
        </a>
        <a href="<?= $urlBase ?>Vista/Instituciones"
          class="btn btn-outline-secondary mt-2 mb-2 px-4 py-2 rounded-pill shadow-sm hover-scale">
          Instituciones
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
        <div class="col-12 col-lg-6 text-center" data-aos="fade-up" data-aos-duration="1200">
          <img
            class="img-fluid rounded shadow-sm"
            loading="lazy"
            src="img/Shrug-bro.svg"
            alt="¿Cómo podemos ayudarte?"
            style="max-width: 90%; border-radius: 20px;">
        </div>

        <!-- Contenido del FAQ -->
        <div class="col-12 col-lg-6" data-aos="fade-up" data-aos-duration="1200">
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
                  include_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Controlador/preguntasFrecuentesCtrl.php';
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

  <!-- Contenido para ti -->
  <section id="contenido" class="contenido-personalizado-section container w-75 mt-5 mb-5 m-auto">
    <h1 class="contenido-title fw-bold mb-4 text-center display-5" data-aos="fade-up" data-aos-duration="500">
      Contenido <span class="contenido-title-accent">para ti</span>
    </h1>
    <div class="contenido-grid row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" data-aos="fade-up" data-aos-duration="1200">
      <?php while ($row = $contenidoReciente->fetch_assoc()): ?>
        <div class="col">
          <div class="contenido-card card h-100 border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="contenido-img-wrapper position-relative">
              <img src="<?= $urlBase . 'uploads/thumbnails/' . basename($row['thumbnail']); ?>"
                class="contenido-img card-img-top"
                alt="thumbnail">
              <span class="contenido-badge">Nuevo</span>
            </div>
            <div class="contenido-body card-body p-4">

              <div class="mb-2 d-flex gap-2">
                <span class="contenido-category
                <?php
                if ($row['categoria'] == 'Ansiedad') echo 'bg-success text-white';
                elseif ($row['categoria'] == 'Depresión') echo 'bg-info text-white';
                elseif ($row['categoria'] == 'Estrés') echo 'bg-warning text-dark'; ?>">
                  <?= ucfirst($row['categoria']) ?>
                </span>
                <span class="contenido-category
                <?php
                if ($row['tipo'] == 'infografia') echo 'bg-primary text-white';
                elseif ($row['tipo'] == 'video') echo 'bg-danger text-white';
                else echo 'bg-warning text-dark';
                ?>">
                  <?= ucfirst($row['tipo']) ?>
                </span>
              </div>


              <h4 class="contenido-card-title mt-3 fw-bold">
                <?= $row['titulo'] ?>
              </h4>

              <p class="contenido-text contenido-descripcion-scroll text-muted">
                <?= $row['descripcion'] ?>
              </p>

              <small class="text-muted d-block mb-3">
                <i class="far fa-calendar-alt"></i>
                <?= date("d M Y", strtotime($row['fecha_publicacion'])) ?>
              </small>

              <div class="contenido-author d-flex align-items-center mt-2">
                <img src="img/NexoH.png" class="rounded-circle me-3" width="40" height="40" alt="Author">
                <a href="#" data-bs-placement="top" title="Ver contenido" class="contenido-link fs-5 ms-auto"
                  data-tipo="<?= $row['tipo'] ?>"
                  data-titulo="<?= htmlspecialchars($row['titulo']) ?>"
                  data-cuerpo="<?= htmlspecialchars($row['cuerpo_html'], ENT_QUOTES) ?>"
                  data-url="<?= htmlspecialchars($row['url_contenido'] ?? '') ?>"
                  data-archivo="<?= pdfBlobToBase64($row['archivo']) ?>"
                  data-img1="<?= blobToBase64($row['imagen1']) ?>"
                  data-img2="<?= blobToBase64($row['imagen2']) ?>"
                  data-img3="<?= blobToBase64($row['imagen3']) ?>">
                  <i class="fas fa-arrow-right"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
    <div class="contenido-more text-center mt-4" data-aos="fade-up" data-aos-duration="1200">
      <a class="contenido-more-link fw-bold text-primary" href="Vista/contenido.php">Más contenido</a>
    </div>
  </section>

  <!-- Testimonios -->
  <section id="testimonios" class="py-5" data-aos="fade-up" data-aos-duration="1000">
    <div class="container">
      <div class="row align-items-center" style="min-height: 500px;">

        <!-- Carrusel vertical personalizado -->
        <div class="col-md-6 d-flex justify-content-center mt-4" data-aos="fade-up" data-aos-delay="200">
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
        <div class="col-md-6 d-flex justify-content-center" data-aos="fade-up" data-aos-delay="300">
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

  <?php include 'Vista/modales/contenido.php'; ?>

  <?php
  include $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/usuaria/footer.php';
  ?>

  <!-- Scripts -->
  <script src="peticiones(js)/contenido.js"></script>
  <script src="peticiones(js)/carruselTestimonios.js"></script>
  <script src="peticiones(js)/testimonios.js"></script>
  <script src="peticiones(js)/return.js"></script>

  <script>
    AOS.init({
      duration: 1000,
      once: true,
      offset: 120,
    });
  </script>


</body>

</html>