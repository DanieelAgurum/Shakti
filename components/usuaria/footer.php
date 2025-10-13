<footer class="footer mt-auto py-5 border-top custom-footer">
  <div class="container">
    <div class="row gy-4 align-items-start text-center text-md-start">

      <!-- Marca y descripción -->
      <div class="col-12 col-md-4">
        <a class="navbar-brand nexo-logo text-decoration-none text-white fw-bold fs-5 d-block mb-2" href="<?= $urlBase ?>index">
          <i class="bi bi-heart-pulse me-2"></i> NexoH
        </a>
        <p class="text-white-50 small mb-0">
          Promovemos la salud mental masculina a través del acompañamiento, la información y el desarrollo personal.
        </p>
      </div>

      <!-- Navegación -->
      <div class="col-12 col-md-4">
        <h6 class="text-uppercase text-white fw-bold mb-3">Explora</h6>
        <nav class="nav flex-column flex-md-row justify-content-center justify-content-md-start gap-2">
          <a href="<?= $urlBase ?>Vista/planes.php" class="nav-link px-2 text-white-50 hover-link">Suscríbete</a>
          <a href="<?= $urlBase ?>Vista/contacto.php" class="nav-link px-2 text-white-50 hover-link">Contáctanos</a>
          <a href="<?= $urlBase ?>Vista/politicas.php" class="nav-link px-2 text-white-50 hover-link">Privacidad</a>
          <a href="<?= $urlBase ?>Vista/glosario.php" class="nav-link px-2 text-white-50 hover-link">Glosario</a>
        </nav>
      </div>

      <!-- Redes sociales -->
      <div class="col-12 col-md-4">
        <h6 class="text-uppercase text-white fw-bold mb-3 text-md-end text-center">Síguenos</h6>
        <div class="d-flex justify-content-center justify-content-md-end gap-3">
          <a href="#" class="social-link" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
          <a href="#" class="social-link" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
          <a href="#" class="social-link" aria-label="Correo"><i class="bi bi-envelope-fill"></i></a>
        </div>
      </div>

    </div>

    <hr class="my-4 text-white-50">

    <!-- Créditos -->
    <div class="text-center text-white-50 small">
      © <?= date("Y") ?> NexoH — Promoviendo la salud mental masculina.
    </div>
  </div>
</footer>