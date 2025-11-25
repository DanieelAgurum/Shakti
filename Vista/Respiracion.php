<?php
$videos = [
    [
        'id' => 'tHM0iSG2d3Q',
        'titulo' => 'Video 1: Calma la ansiedad en 8 minutos ',
        'descripcion' => 'Respiración guiada para la ansiedad.'
    ],
    [
        'id' => 'EGO5m_DBzF8',
        'titulo' => 'Video 2: Respiración para reducir la ansiedad',
        'descripcion' => 'Técnica 478 '
    ],
    [
        'id' => 'G-72baPOkoQ',
        'titulo' => 'Video 3: Ejercicios respiratorios',
        'descripcion' => '6 ejercicios respiratorios para el estres y la ansiedad.'
    ],
    [
        'id' => 'FdOB06fuyLE',
        'titulo' => 'Video 4: Maditación guiada',
        'descripcion' => 'Para soltar estrés y ansiedad.'
    ],
    [
        'id' => 'c_2M8SifyKo',
        'titulo' => 'Video 5: 10 respiraciones guiadas',
        'descripcion' => 'Alivia la ansiedad con este ejercicio de respiración.'

    ],
    [
        'id' => 'mxOOva336Xk',
        'titulo' => 'Video 6: Respiración 4 7 8',
        'descripcion' => 'Control de ansiedad y conciliar el sueño.'
    ],
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="Css/style.css"  href="Css/styleActividades.css" >
  <title>Ejercicios de respiración - NexoH</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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

<div class="container py-5">
  <h1 class="text-center mb-4">Ejercicios de respiración</h1>
  <div class="row g-4">
    <?php foreach ($videos as $video): ?>
      <div class="col-md-4">
        <div class="card h-100 shadow">
          <img src="https://img.youtube.com/vi/<?= $video['id'] ?>/hqdefault.jpg" class="card-img-top" alt="<?= htmlspecialchars($video['titulo']) ?>">
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
</div>

<!-- Modal -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Reproducción del Video</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div class="ratio ratio-16x9">
          <iframe id="videoIframe" src="" title="Video" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
      </div>
    </div>
  </div>
</div>

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
