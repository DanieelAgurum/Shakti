<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/obtenerLink/obtenerLink.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Modelo/conexion.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$conexion = new ConectarDB();
$con = $conexion->open();

$token = $_GET['token'] ?? null;
$urlBase = getBaseUrl();

try {
    if (!$token) {
        header("Location: {$urlBase}Vista/registro");
        exit;
    }

    $sql = "SELECT u.id, t.fecha, t.token 
            FROM tokens_contrasena t 
            JOIN usuarias u ON u.id = t.id_usuaria 
            WHERE t.token = :token 
              AND t.fecha >= DATE_SUB(NOW(), INTERVAL 15 MINUTE)
            LIMIT 1";

    $stmt = $con->prepare($sql);
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() === 0) {
        header("Location: {$urlBase}Vista/registro");
        exit;
    }
} catch (Throwable $th) {
    error_log("Error en validación de token: " . $th->getMessage());
    header("Location: {$urlBase}Vista/registro");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Recuperar Contraseña - NexoH</title>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/usuaria/navbar.php'; ?>
    <link rel="stylesheet" href="<?php echo $urlBase ?>css/styles.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="d-flex flex-column">
    <main class="flex-grow-1 d-flex align-items-center justify-content-center">
        <div class="auth-container">
            <div class="auth-header text-center mb-4">
                <h1 class="h3 fw-bold text-secondary">Cambiar Contraseña</h1>
            </div>
            
            <!-- Formulario -->
            <form class="auth-form" id="registroForm" novalidate
                action="<?php echo $urlBase ?>/Controlador/cambiarContraCorreo.php?opcion=2" method="post">

                <!-- Campo oculto para correo/usuario (requerido por gestores de contraseña) -->
                <input type="email" name="correo" id="correo" autocomplete="username" value="" hidden>

                <div class="mb-3 position-relative">
                    <label for="contraseña" class="form-label">Nueva contraseña</label>
                    <div class="input-group">
                        <input type="password" class="form-control" name="contraseña" id="contraseña"
                            placeholder="Ingrese su nueva contraseña" autocomplete="new-password" required />

                        <button class="btn btn-outline" type="button" id="togglePassword">
                            <i class="bi bi-eye-fill" id="iconPassword"></i>
                        </button>

                        <input type="hidden" name="token"
                            value="<?php echo isset($token) ? htmlspecialchars($token) : ''; ?>">
                    </div>
                    <div class="invalid-feedback d-block text-danger mt-1" id="errorContraseña"></div>
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-purple w-100 shadow-sm fw-semibold" id="cambiar">
                        Cambiar contraseña
                    </button>
                </div>
            </form>
        </div>
    </main>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/usuaria/footer.php'; ?>
    <script src="<?= $urlBase ?>peticiones(js)/verContra.js"></script>
    <script src="<?= $urlBase ?>validacionRegistro/validarContra.js"></script>
    <script src="<?= $urlBase ?>peticiones(js)/mandarMetricas.js.php?vista=<?= urlencode(basename($_SERVER['PHP_SELF'])) ?>"></script>
</body>

</html>