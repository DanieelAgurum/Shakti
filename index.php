<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

include_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/obtenerLink/obtenerLink.php';
$urlBase = getBaseUrl();

require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/modelo/PublicacionModelo.php';
$publicacionModelo = new PublicacionModelo();
$publicaciones = $publicacionModelo->obtenerTodasConNickname();
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

  <?php include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/usuaria/navbar.php'; ?>

  <style>
    .swiper {
      width: 100%;
      height: 400px;
    }

    .swiper-slide img {
      object-fit: cover;
      height: 100%;
      width: 100%;
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
        <a href="#" class="btn btn-primary me-2">Conocer más</a>
        <a href="#" class="btn btn-outline-secondary">Contáctanos</a>
      </div>
    <?php endif; ?>
  </main>

  <!-- Publicaciones recientes -->
  <section class="container mb-5 d-flex flex-wrap justify-content-center gap-4">
    <h2 class="text-center w-100 mb-4">Publicaciones recientes</h2>

    <?php if (!empty($publicaciones)): ?>
      <?php foreach ($publicaciones as $publicacion): ?>
        <article class="instagram-post">
          <header class="post-header">
            <div class="profile-info">
              <img src="<?= $urlBase ?>img/usuario.jpg" alt="Foto de perfil" class="profile-pic" />
              <div class="profile-details">
                <span class="username"><?= htmlspecialchars($publicacion['nickname']) ?></span>
                <span class="follow-text"> • Seguir</span>
              </div>
            </div>
            <i class="fas fa-ellipsis-h dots-icon"></i>
          </header>

          <div class="post-content">
            <p class="ps-3 pt-2"><?= nl2br(htmlspecialchars($publicacion['contenido'])) ?></p>
          </div>

          <div class="post-actions">
            <div class="d-flex gap-2">
              <button class="btn btn-sm btn-outline-primary btn-like" data-id="<?= $publicacion['id_publicacion'] ?>">
                <i class="bi bi-hand-thumbs-up"></i> Me gusta
                <span class="badge bg-primary likes-count">0</span>
              </button>
              <button class="btn btn-sm btn-outline-secondary btn-toggle-comments" data-id="<?= $publicacion['id_publicacion'] ?>">
                <i class="bi bi-chat"></i> Comentarios
              </button>
            </div>
          </div>

          <div class="comments-section mt-3 d-none" id="comments-<?= $publicacion['id_publicacion'] ?>">
            <div class="existing-comments mb-3">
              <p class="text-muted">Aún no hay comentarios.</p>
            </div>
            <form class="comment-form" data-id="<?= $publicacion['id_publicacion'] ?>">
              <div class="input-group">
                <input type="text" class="form-control form-control-sm" placeholder="Escribe un comentario..." required />
                <button class="btn btn-sm btn-primary" type="submit">Enviar</button>
              </div>
            </form>
          </div>
        </article>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="text-center w-100">No hay publicaciones todavía.</p>
    <?php endif; ?>
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