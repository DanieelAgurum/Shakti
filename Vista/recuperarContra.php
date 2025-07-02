<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/obtenerLink/obtenerLink.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/Modelo/conexion.php';
$conexion = new ConectarDB();
$con = $conexion->open();

$token = $_GET['token'];
$urlBase = getBaseUrl();

try {
    $sql = "SELECT * FROM tokens_contrasena t JOIN usuarias u ON u.id = t.id_usuaria WHERE t.token = :token";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() == 0) {
        header("Location: {$urlBase}/Vista/registro.php");
        exit;
    }
} catch (\Throwable $th) {
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Recuperar Contraseña - Shakti</title>
    <link rel="stylesheet" href="<?php echo $urlBase ?>css/styles.css" />
    <?php
    include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/usuaria/navbar.php';
    ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="d-flex flex-column">
    <main class="flex-grow-1 d-flex align-items-center justify-content-center">
        <div class="auth-container">
            <div class="auth-header text-center mb-4">
                <h1 class="h3 fw-bold text-secondary">Cambiar Contraseña</h1>
            </div>

            <form class="auth-form" id="registroForm" novalidate action="../Controlador/cambiarContraCorreo.php" method="post">
                <!-- Contraseña -->
                <div class="mb-3 position-relative">
                    <label for="contraseña" class="form-label">Contraseña</label>
                    <div class="input-group">
                        <input type="password" class="form-control" name="contraseña" id="contraseña" placeholder="Ingrese su contraseña" />
                    </div>
                    <small class="error" id="errorContraseña"></small>
                </div>

                <!-- Botón -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-purple w-100 shadow-sm fw-semibold" id="cambiar">Cambiar</button>
                </div>
            </form>

        </div>

        <?php if (isset($_GET['status']) && isset($_GET['message'])): ?>
            <script>
                Swal.fire({
                    icon: '<?= $_GET['status'] === 'success' ? 'success' : 'error' ?>',
                    title: '<?= $_GET['status'] === 'success' ? '¡Todo listo!' : 'Ups...' ?>',
                    text: '<?= htmlspecialchars(urldecode($_GET["message"]), ENT_QUOTES, "UTF-8") ?>',
                    confirmButtonText: 'Aceptar'
                });
            </script>
        <?php endif; ?>

        <script src="../validacionRegistro/validarContra.js"></script>
    </main>
    <?php include '../components/usuaria/footer.php'; ?>

</body>

</html>