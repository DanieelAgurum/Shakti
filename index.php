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
  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/usuaria/navbar.php'; ?>
</head>

<body class="bg-white text-black">

  <!-- Carrusel de imágenes -->
  <div class="swiper-container hero-carousel">
    <div class="swiper-wrapper">
      <div class="swiper-slide"><img src="https://elcomercio.pe/resizer/v2/VCID6SJK6BCZ5FKGBYLNA7BJII.jpg" alt="Yoga"><div class="slide-overlay"></div></div>
      <div class="swiper-slide"><img src="https://media.glamour.mx/photos/63effc578b641025c85eb38a/16:9/w_2560.jpg" alt="Marcha"><div class="slide-overlay"></div></div>
      <div class="swiper-slide"><img src="http://www.fundipax.org/wp-content/uploads/2023/03/dia-de-la-mujer-8-de-marzo-fundipax.jpg" alt="Meditación"><div class="slide-overlay"></div></div>
      <div class="swiper-slide"><img src="https://museodelacuerdo.cultura.gob.ar/media/uploads/site-22/destacados/.thumbnails/mujeres-de-espaldas-unidas.jpg" alt="Unidas"><div class="slide-overlay"></div></div>
      <div class="swiper-slide"><img src="https://img.freepik.com/vector-premium/mujeres-multiculturales-bandera-horizontal_255494-1226.jpg" alt="Multiculturales"><div class="slide-overlay"></div></div>
      <div class="swiper-slide"><img src="https://bonisimo.es/img/cms/BLOG/dia_internacional_de_la_mujer-cabecera.png" alt="Comunidad"><div class="slide-overlay"></div></div>
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
      <div class="hero-buttons mt-4">
        <a href="#" class="btn btn-primary me-2">Conocer más</a>
        <a href="#" class="btn btn-outline-secondary">Contáctanos</a>
      </div>
    <?php endif; ?>
  </main>

  <!-- Publicaciones recientes -->
  <section class="container mt-5 mb-5">
    <h2 class="text-center mb-4">Publicaciones recientes</h2>

    <?php if (!empty($publicaciones)): ?>
      <?php foreach ($publicaciones as $publicacion): ?>
        <div class="card mb-3 shadow-sm card-publicacion">
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($publicacion['titulo']) ?></h5>
            <p class="card-text"><?= nl2br(htmlspecialchars($publicacion['contenido'])) ?></p>
            <p class="card-text">
              <small class="text-muted">
                Publicado por <?= htmlspecialchars($publicacion['nickname']) ?> el
                <?= date('d/m/Y H:i', strtotime($publicacion['fecha_publicacion'])) ?>
              </small>
            </p>

            <!-- Botones de interacción -->
            <div class="d-flex gap-2">
              <button class="btn btn-sm btn-outline-primary btn-like" data-id="<?= $publicacion['id_publicacion'] ?>">
                <i class="bi bi-hand-thumbs-up"></i> Me gusta
                <span class="badge bg-primary likes-count">0</span>
              </button>
              <button class="btn btn-sm btn-outline-secondary btn-toggle-comments" data-id="<?= $publicacion['id_publicacion'] ?>">
                <i class="bi bi-chat"></i> Comentarios
              </button>
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

          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="text-center">No hay publicaciones todavía.</p>
    <?php endif; ?>
  </section>

  <?php include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/usuaria/footer.php'; ?>

  <!-- Scripts -->
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const swiper = new Swiper('.hero-carousel', {
      effect: 'fade',
      fadeEffect: { crossFade: true },
      loop: true,
      autoplay: { delay: 4000, disableOnInteraction: false },
      pagination: { el: '.swiper-pagination', clickable: true },
      navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' }
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
  </script>
</body>
</html>
