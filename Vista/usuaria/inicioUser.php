<div class="container text-center">
  <h1>¡Hola, <?= htmlspecialchars($_SESSION['nickname']) ?>!</h1>
  <p class="lead">Nos alegra tenerte aquí. Accede a nuestros recursos y herramientas pensadas para tu bienestar.</p>
  <div class="hero-buttons ">
    <a href="#" class="btn btn-primary me-2">Más contenido....</a>
    <a href="<?= $urlBase ?>Vista/organizacionVista.php" class="btn btn-outline-secondary">Organizaciones</a>
  </div>
  <hr class="my-5">
</div>