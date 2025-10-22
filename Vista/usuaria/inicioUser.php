<div class="container text-center">
  <h1 class="fw-bold mb-3">¡Hola, <span class="text-primary"><?= htmlspecialchars($_SESSION['nickname']) ?>!</span></h1>
  <p class="lead">Nos alegra tenerte aquí. Accede a nuestros recursos y herramientas pensadas para tu bienestar.</p>
  <div class="hero-buttons d-flex flex-wrap justify-content-center" data-aos="fade-up" data-aos-delay="400">
    <a href="<?= $urlBase ?>Vista/Contenido"
      class="btn btn-primary me-2 mt-2 mb-2 px-4 py-2 rounded-pill shadow-sm hover-scale">
      Más contenido...
    </a>
    <a href="<?= $urlBase ?>Vista/Instituciones"
      class="btn btn-outline-secondary mt-2 mb-2 px-4 py-2 rounded-pill shadow-sm hover-scale">
      Instituciones
    </a>
  </div>
  <hr class="my-5">
</div>