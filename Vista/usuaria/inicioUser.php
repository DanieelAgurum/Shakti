<div class="container mt-5 text-center">
  <h1>¡Hola, <?= htmlspecialchars($_SESSION['nickname']) ?>!</h1>
  <p class="lead">Nos alegra tenerte aquí. Accede a nuestros recursos y herramientas pensadas para tu bienestar.</p>

  <div class="row mt-4">
    <div class="col-md-6">
      <h3>Mi perfil</h3>
      <p>Consulta o edita tu información personal.</p>
      <a href="<?= $urlBase ?>views/usuaria/perfil.php" class="btn btn-primary">Ir al perfil</a>
    </div>
    <div class="col-md-6">
      <h3>Recursos disponibles</h3>
      <p>Consulta artículos, herramientas y más contenido exclusivo.</p>
      <a href="#" class="btn btn-outline-secondary">Ver recursos</a>
    </div>
  </div>
</div>
