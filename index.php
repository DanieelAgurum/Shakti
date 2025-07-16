<?php
require_once 'modelo/testimoniosMdl.php';
date_default_timezone_set('America/Mexico_City');
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

include_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/obtenerLink/obtenerLink.php';
$urlBase = getBaseUrl();

$temp = new Testimonios(null);
$db = $temp->conectarBD();
$testimonio = new Testimonios($db);
$testimonios = $testimonio->obtenerTestimonios();

?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Shakti</title>

  <link rel="stylesheet" href="<?= $urlBase ?>css/estilos.css" />
  <link rel="stylesheet" href="<?= $urlBase ?>css/estiloscarrucel.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


  <!-- 
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick-theme.css" />
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script> -->
  <script src="<?= $urlBase ?>peticiones(js)/likesContar.js"></script>

  <?php include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/usuaria/navbar.php'; ?>
  <style>
    .square-button {
      width: 50px;
      height: 50px;
      background-color: white;
      color: black;
      border: none;
      border-radius: 0;
      font-size: 20px;
      transition: background-color 0.3s ease;
    }

    .square-button:hover {
      background-color: #b0b0b0ff;
      ;
    }

    @media (max-width: 576px) {
      .animacion {
        animation: none !important;
      }
    }

    .opiniones {
      background: linear-gradient(145deg, #f3f4f6, #ffffff);
      border-radius: 40px;
      transition: transform 0.3s ease;
    }

    .quote-icon {
      font-size: 4rem;
      color: #4b0082;
      opacity: 0.2;
    }

    .fotoOpinion {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      object-fit: cover;
      border: 4px solid #ffffff;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .carousel {
      background-color: transparent;
    }

    .carousel-control-prev,
    .carousel-control-next {
      width: 40px;
      height: 40px;
      background-image: linear-gradient(45deg, #ff9a9e 0%, #fad0c4 99%, #fad0c4 100%);
      border-radius: 50%;
      top: 50%;
      transform: translateY(-50%);
    }

    .carousel-control-prev {
      left: -20px;
    }

    .carousel-control-next {
      right: -20px;
    }

    .carousel-indicators {
      bottom: -50px;
    }

    .carousel-indicators button {
      width: 12px;
      height: 12px;
      border-radius: 50%;
      background-color: #6366f1;
      opacity: 0.5;
    }

    .carousel-indicators .active {
      opacity: 1;
    }

    div.stars {
      display: inline-block;
    }

    input.star {
      display: none;
    }

    label.star {
      float: right;
      padding: 10px;
      font-size: 16px;
      color: #4A148C;
      transition: all .2s;
    }

    input.star:checked~label.star:before {
      content: '\f005';
      color: #FD4;
      transition: all .25s;
    }

    input.star-5:checked~label.star:before {
      color: #FE7;
      text-shadow: 0 0 20px #952;
    }

    input.star-1:checked~label.star:before {
      color: #F62;
    }

    label.star:hover {
      transform: rotate(-15deg) scale(1.3);
    }

    label.star:before {
      content: '\f006';
      font-family: FontAwesome;
    }

    .custom-rating {
      background: linear-gradient(135deg, #fff9c4, #ffe082);
      /* amarillo suave */
      color: #5d4037;
      /* café oscuro para buen contraste */
      padding: 0.5rem 1.2rem;
      border: 2px solid #ffca28;
      /* borde dorado */
      border-radius: 30px;
      font-weight: 600;
      box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 1rem;
    }

    .custom-rating i {
      color: #fbc02d;
      font-size: 1.2rem;
    }

    .rating-container {
      border: solid #4b0082;
      border-radius: 20px;
      padding: 2rem;
      max-width: 400px;
      margin: 2rem auto;
    }

    .rating-title {
      color: #2d3436;
      font-weight: 600;
      margin-bottom: 1.5rem;
    }

    .rating-feedback {
      margin-top: 1.5rem;
      color: #636e72;
    }

    .submit-rating {
      background: linear-gradient(145deg, #f7b731, #f0932b);
      border: none;
      padding: 10px 25px;
      border-radius: 25px;
      color: white;
      font-weight: 600;
      margin-top: 1rem;
      transition: all 0.3s ease;
    }

    .submit-rating:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(247, 183, 49, 0.4);
    }

    .rating-emoji {
      font-size: 2rem;
      margin-bottom: 1rem;
    }
  </style>
</head>

<body class="bg-white text-black">
  

  <!-- Carrusel de imágenes -->
  <div class="swiper mySwiper hero-carousel mb-5">
    <div class="swiper-wrapper">
      <div class="swiper-slide">
        <img src="<?= $urlBase ?>img/1carr.jpg" alt="Yoga" class="img-fluid w-100 h-100 h-100 rounded shadow-sm" style="height: 300px; object-fit: cover;">
      </div>
      <div class="swiper-slide">
        <img src="<?= $urlBase ?>img/2carr.jpg" alt="Marcha" class="img-fluid w-100 h-100 rounded shadow-sm" style="height: 300px; object-fit: cover;">
      </div>
      <div class="swiper-slide">
        <img src="<?= $urlBase ?>img/3carr.png" alt="Meditación" class="img-fluid w-100 h-100 rounded shadow-sm" style="height: 300px; object-fit: cover;">
      </div>
      <div class="swiper-slide">
        <img src="<?= $urlBase ?>img/4carr.jpeg" alt="Unidas" class="img-fluid w-100 h-100 rounded shadow-sm" style="height: 300px; object-fit: cover;">
      </div>
      <div class="swiper-slide">
        <img src="<?= $urlBase ?>img/5carr.png" alt="Multiculturales" class="img-fluid w-100 h-100 rounded shadow-sm" style="height: 300px; object-fit: cover;">
      </div>
      <div class="swiper-slide">
        <img src="<?= $urlBase ?>img/6carr.png" alt="Comunidad" class="img-fluid w-100 h-100 rounded shadow-sm" style="height: 300px; object-fit: cover;">
      </div>
    </div>
    <div class="swiper-pagination"></div>
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
  </div>

  <!-- Bienvenida -->
  <main class="hero p-5 text-center">
    <?php if (!empty($_SESSION['correo'])): ?>
      <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/Vista/usuaria/inicioUser.php'; ?>
    <?php else: ?>
      <h1>Bienvenido a Nuestro Sitio Shakti</h1>
      <p class="lead">Tu bienestar es primero</p>
      <div class="hero-buttons ">
        <a href="#" class="btn btn-primary me-2">Más contenido....</a>
        <a href="<?= $urlBase ?>Vista/admin/organizaciones.php" class="btn btn-outline-secondary">Organizaciones</a>
      </div>
    <?php endif; ?>
  </main>


  <div class="container mt-5 mb-5 ">
    <h2 class="text-center mb-4">
      Contenido Destacado
    </h2>
    <div class="row g-4">
      <div class="col-12 col-md-4">
        <div class="card card-custom animate__animated animate__fadeInLeft animate__slow animacion text-white">
          <img src="https://tse1.mm.bing.net/th/id/OIP.4tfg8I67q3CueL5oCkv8KAHaE8?r=0&cb=thvnext&rs=1&pid=ImgDetMain&o=7&rm=3" class="card-img" alt="Imagen 1" />
          <div class="card-img-overlay">
            <h5 class="card-title title-content">Moda Urbana 2025</h5>
            <p class="card-text text-content">Descubre las últimas tendencias en ropa casual con un toque fresco y moderno para esta temporada.</p>
            <div class="card-date">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-2 .89-2 2v12a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6c0-1.11-.89-2-2-2zM5 20V9h14v11H5z" />
              </svg>
              Última actualización hace 3 min
            </div>
            <a href="#" class="btn btn-outline-light mt-3 read-more-btn">Leer más</a>
          </div>
        </div>
      </div>

      <!-- Repite para las otras dos tarjetas -->
      <div class="col-12 col-md-4">
        <div class="card card-custom animate__animated animate__fadeInLeft animate__slow animacion text-white">
          <img src="https://tse1.mm.bing.net/th/id/OIP.4tfg8I67q3CueL5oCkv8KAHaE8?r=0&cb=thvnext&rs=1&pid=ImgDetMain&o=7&rm=3" class="card-img" alt="Imagen 2" />
          <div class="card-img-overlay">
            <h5 class="card-title title-content">Tecnología y Estilo</h5>
            <p class="card-text text-content">Cómo la tecnología está influyendo en el diseño de prendas con materiales inteligentes y sostenibles.</p>
            <div class="card-date">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-2 .89-2 2v12a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6c0-1.11-.89-2-2-2zM5 20V9h14v11H5z" />
              </svg>
              Última actualización hace 5 min
            </div>
            <a href="#" class="btn btn-outline-light mt-3 read-more-btn">Leer más</a>
          </div>
        </div>
      </div>

      <div class="col-12 col-md-4">
        <div class="card card-custom animate__animated animate__fadeInLeft animate__slow animacion text-white">
          <img src="https://tse1.mm.bing.net/th/id/OIP.4tfg8I67q3CueL5oCkv8KAHaE8?r=0&cb=thvnext&rs=1&pid=ImgDetMain&o=7&rm=3" class="card-img" alt="Imagen 3" />
          <div class="card-img-overlay">
            <h5 class="card-title title-content">Consejos de Estilo</h5>
            <p class="card-text text-content">Ideas para combinar colores y accesorios que realcen tu personalidad y estilo único.</p>
            <div class="card-date">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-2 .89-2 2v12a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6c0-1.11-.89-2-2-2zM5 20V9h14v11H5z" />
              </svg>
              Última actualización hace 10 min
            </div>
            <a href="#" class="btn btn-outline-light mt-3 read-more-btn">Leer más</a>
          </div>
        </div>
      </div>
    </div>
    <div class="d-flex justify-content-end mt-4">
      <button type="button" class="btn btn-outline-dark px-4 py-2">
        Ver más
      </button>
    </div>
  </div>

  <!-- FAQ 1 - Bootstrap Brain Component -->
  <section class="py-3 py-md-5 mb-5">
    <div class="container">
      <div class="row gy-5 gy-lg-0 align-items-lg-center">
        <div class="col-12 col-lg-6">
          <img class="img-fluid rounded animate__animated animate__fadeInLeft  animate__delay-1s animate__slow animacion" loading="lazy" src="img/Woman thinking-amico.svg" alt="How can we help you?">
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
                  require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/Controlador/preguntasFrecuentesCtrl.php';
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
  <div class="w-75">
    <div class="container py-5">
      <h2 class="text-center mb-5">Lo que dicen nuestras usuarias</h2>
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

  <script>
    document.getElementById("formCalificacion").addEventListener("submit", function(e) {
      e.preventDefault();

      const calificacion = document.querySelector('input[name="calificacion"]:checked');
      const opinion = document.getElementById("opinion").value.trim();
      const btn = this.querySelector("button[type=submit]");

      if (!calificacion) {
        Swal.fire({
          icon: "warning",
          title: "Calificación requerida",
          text: "Por favor selecciona una estrella antes de enviar.",
        });
        return;
      }
      if (opinion === "") {
        Swal.fire({
          icon: "warning",
          title: "Opinión requerida",
          text: "Por favor escribe tu experiencia.",
        });
        return;
      }

      btn.disabled = true;
      const formData = new FormData();
      formData.append("opcion", 1);
      formData.append("calificacion", calificacion.value);
      formData.append("opinion", opinion);

      fetch("Controlador/testimoniosCtrl.php", {
          method: "POST",
          body: formData,
        })
        .then(() => {
          Swal.fire({
            icon: "success",
            title: "¡Gracias!",
            text: "Tu calificación fue enviada correctamente.",
            timer: 2000,
            showConfirmButton: false
          });
          document.getElementById("formCalificacion").reset();
          btn.disabled = false;
        })
        .catch(() => {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: "Ocurrió un problema al enviar tu calificación.",
            timer: 2000,
            showConfirmButton: false
          });
          btn.disabled = false;
        });
    });
  </script>




  <?php
  include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/usuaria/footer.php';
  ?>

  <!-- Scripts -->
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  <script src="<?= $urlBase ?>peticiones(js)/mandarMetricas.js.php?vista=<?= urlencode(basename($_SERVER['PHP_SELF'])) ?>"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const swiper = new Swiper('.mySwiper', {
        effect: 'fade',
        loop: true,
        autoplay: {
          delay: 4000,
          disableOnInteraction: false,
        },
        pagination: {
          el: '.swiper-pagination',
          clickable: true,
        },
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        },
      });

      document.querySelectorAll('.btn-like').forEach(btn => {
        btn.addEventListener('click', () => {
          const badge = btn.querySelector('.likes-count');
          let count = parseInt(badge.textContent) || 0;
          badge.textContent = ++count;
          btn.classList.add('btn-primary');
          btn.classList.remove('btn-outline-primary');
          btn.disabled = true;
        });
      });

      document.querySelectorAll('.btn-toggle-comments').forEach(btn => {
        btn.addEventListener('click', () => {
          const pubId = btn.dataset.id;
          const comments = document.getElementById('comments-' + pubId);
          comments.classList.toggle('d-none');
        });
      });

      document.querySelectorAll('.comment-form').forEach(form => {
        form.addEventListener('submit', e => {
          e.preventDefault();
          const input = form.querySelector('input[type="text"]');
          const comment = input.value.trim();
          if (!comment) return;

          const container = form.previousElementSibling;
          const p = document.createElement('p');
          p.textContent = comment;
          p.classList.add('comment');
          container.appendChild(p);
          input.value = '';
        });
      });
    });
  </script>
</body>

</html>