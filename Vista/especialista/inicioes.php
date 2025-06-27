<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['correo']) || $_SESSION['id_rol'] != 2) {
    header("Location: ../../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Panel Especialista - Shakti</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

  <!-- CSS personalizado -->
  <link rel="stylesheet" href="/Shakti/css/estilos.css" />
</head>

<body>

  <?php include '../../components/especialista/navbar.php'; ?>

  <!-- Espacio para que el contenido no quede oculto por la navbar fija -->
  <div style="margin-top: 70px;"></div>

  <!-- Aquí va tu contenido -->
  <div class="container">
    <h1>Bienvenido especialista <?php echo htmlspecialchars($_SESSION['nombre']); ?></h1>
    <!-- Más contenido -->
  </div>

  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
