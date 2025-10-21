<?php

$videos = [
    [
        'id' => 'ugYrYMZi0J4',
        'titulo' => 'Video 1: Sonidos de la Naturaleza ',
        'descripcion' => 'Relajación: Música para Relajarse y Calmar la Mente.'
    ],
    [
        'id' => 'aVS7PHjSxdI',
        'titulo' => 'Video 2: Sonidos de la naturaleza',
        'descripcion' => 'Relajantes sonidos de la naturaleza y suaves cantos de pájaros. '
    ],
    [
        'id' => 'c2NmyoXBXmE',
        'titulo' => 'Video 3: Sonidos de la naturaleza',
        'descripcion' => 'Nature Sounds of a Forest River for Relaxing-Natural meditation music of a Waterfall & Bird Sounds.'
    ],
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sonidos relajantes</title>
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
  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../Css/styleSonidos.css" />
  
</head>

<body>
<div class="container py-5">
  <h1 class="text-center mb-4">Sonidos relajantes</h1>
  <div class="row g-4">
    <?php foreach ($videos as $video): ?>
      <div class="col-md-4">
        <div class="card h-100 shadow card-video">
          <div class="video-thumbnail" data-bs-toggle="modal" data-bs-target="#videoModal" data-video-id="<?= $video['id'] ?>">
            <img src="https://img.youtube.com/vi/<?= $video['id'] ?>/hqdefault.jpg" class="card-img-top" alt="<?= htmlspecialchars($video['titulo']) ?>">
          </div>
          <div class="card-body d-flex flex-column">
            <h5 class="card-title"><?= htmlspecialchars($video['titulo']) ?></h5>
            <p class="card-text"><?= htmlspecialchars($video['descripcion']) ?></p>
            <button class="btn btn-primary mt-auto" data-bs-toggle="modal" data-bs-target="#videoModal" data-video-id="<?= $video['id'] ?>">
              Ver Video
            </button>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content modal-video">
        <div class="modal-header">
          <h5 class="modal-title" id="videoModalLabel">Reproducción del Video</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <div class="ratio ratio-16x9">
            <iframe id="videoIframe" src="" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Audios -->
  <h2 class="text-center my-5">Audios de Relajación</h2>
  <div class="row g-4 justify-content-center">
    <div class="col-md-6">
      <div class="card card-audio shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Audio 1</h5>
          <p class="card-text">Música relajante para reducir la ansierdad, calmar la mente y dejar de pensar</p>
          <audio controls>
            <source src="../Audios/MÚSICA RELAJANTE ZEN PARA REDUCIR LA ANSIEDAD CALMAR LA MENTE Y DEJAR DE PENSAR - MEDITACION.mp3" type="audio/mpeg">
            Tu navegador no soporta el audio.
          </audio>
          <a href="../Audios/MÚSICA RELAJANTE ZEN PARA REDUCIR LA ANSIEDAD CALMAR LA MENTE Y DEJAR DE PENSAR - MEDITACION.mp3" download class="btn btn-sm btn-outline-primary btn-download">Descargar Audio</a>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card card-audio shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Audio 2</h5>
          <p class="card-text">Calmar y Eliminar la Ansiedad_ Música Relajante Arpa y Naturaleza</p>
          <audio controls>
            <source src="../Audios/Calmar y Eliminar la Ansiedad_ Música Relajante Arpa y Naturaleza.mp3" type="audio/mpeg">
            Tu navegador no soporta el audio.
          </audio>
          <a href="../Audios/Calmar y Eliminar la Ansiedad_ Música Relajante Arpa y Naturaleza.mp3" download class="btn btn-sm btn-outline-primary btn-download">Descargar Audio</a>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card card-audio shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Audio 3</h5>
          <p class="card-text">Relajación_ Música para Relajarse y Calmar la Mente - Música de la Naturaleza</p>
          <audio controls>
            <source src="../Audios/Relajación_ Música para Relajarse y Calmar la Mente - Música de la Naturaleza.mp3" type="audio/mpeg">
            Tu navegador no soporta el audio.
          </audio>
          <a href="../Audios
          
          
          /Relajación_ Música para Relajarse y Calmar la Mente - Música de la Naturaleza.mp3" download class="btn btn-sm btn-outline-primary btn-download">Descargar Audio</a>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card card-audio shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Audio 4</h5>
          <p class="card-text">Sonidos del Bosque - Relajantes - Sonidos de la Naturaleza</p>
          <audio controls>
            <source src="../Audios/Sonidos del Bosque - Relajarse - Sonidos de la Naturaleza.mp3" type="audio/mpeg">
            Tu navegador no soporta el audio.
          </audio>
          <a href="../Audios/Sonidos del Bosque - Relajarse - Sonidos de la Naturaleza.mp3" download class="btn btn-sm btn-outline-primary btn-download">Descargar Audio</a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const videoModal = document.getElementById('videoModal');
  const videoIframe = document.getElementById('videoIframe');

  videoModal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget;
    const videoId = button.getAttribute('data-video-id');
    videoIframe.src = `https://www.youtube.com/embed/${videoId}?autoplay=1`;
  });

  videoModal.addEventListener('hidden.bs.modal', () => {
    videoIframe.src = '';
  });
</script>
<?php
  include $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/usuaria/footer.php';
  ?>

</body>
</html>
