<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

include_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/obtenerLink/obtenerLink.php';
$urlBase = getBaseUrl();

?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Shakti</title>

  <link rel="stylesheet" href="<?= $urlBase ?>css/estilos.css" />
  <link rel="stylesheet" href="<?= $urlBase ?>css/estiloscarrucel.css" />
  <link rel="stylesheet" href="<?= $urlBase ?>css/publicaciones.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/usuaria/navbar.php'; ?>

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
        <a href="#" class="btn btn-outline-secondary">Organizaciones</a>
      </div>
    <?php endif; ?>
  </main>


  <div class="container mt-5 mb-5 ">
    <h2 class="text-center mb-4">
      Contenido Destacado
    </h2>
    <div class="row g-4">
      <div class="col-12 col-md-4">
        <div class="card card-custom animate__animated animate__fadeInLeft animate__delay-1s animate__slow text-white">
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
        <div class="card card-custom animate__animated animate__fadeInLeft animate__delay-1s animate__slow text-white">
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
        <div class="card card-custom animate__animated animate__fadeInLeft animate__delay-1s animate__slow text-white">
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
  <section class="bg-light py-3 py-md-5 mb-5">
    <div class="container">
      <div class="row gy-5 gy-lg-0 align-items-lg-center">
        <div class="col-12 col-lg-6">
          <img class="img-fluid rounded animate__animated animate__fadeInLeft  animate__delay-1s animate__slow" loading="lazy" src="img/Woman thinking-amico.svg" alt="How can we help you?">
        </div>
        <div class="col-12 col-lg-6 animate__animated animate__fadeInRight  animate__delay-1s animate__slow">
          <div class="row justify-content-xl-end">
            <div class="col-12 col-xl-11">
              <h2 class="h1 mb-3">¿Cómo podemos ayudarte?</h2>
              <p class="lead text-secondary mb-5">Esperamos que hayas encontrado una respuesta a tu pregunta. Si necesitas ayuda, por favor busca tu consulta en nuestro Centro de Soporte o contáctanos por correo electrónico.</p>
              <div class="accordion accordion-flush" id="accordionFlushExample">
                <div class="accordion-item">
                  <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                      Accordion Item #1
                    </button>
                  </h2>
                  <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate the <code>.accordion-flush</code> class. This is the first item’s accordion body.</div>
                  </div>
                </div>
                <div class="accordion-item">
                  <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                      Accordion Item #2
                    </button>
                  </h2>
                  <div id="flush-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate the <code>.accordion-flush</code> class. This is the second item’s accordion body. Let’s imagine this being filled with some actual content.</div>
                  </div>
                </div>
                <div class="accordion-item">
                  <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                      Accordion Item #3
                    </button>
                  </h2>
                  <div id="flush-collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate the <code>.accordion-flush</code> class. This is the third item’s accordion body. Nothing more exciting happening here in terms of content, but just filling up the space to make it look, at least at first glance, a bit more representative of how this would look in a real-world application.</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

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