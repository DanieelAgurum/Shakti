<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shakti//obtenerLink/obtenerLink.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/shakti//Modelo/conexion.php';

$conexion = new ConectarDB();
$con = $conexion->open();

$token = $_GET['token'] ?? null;
$urlBase = getBaseUrl();

try {
    if (!$token) {
        header("Location: {$urlBase}Vista/registro.php");
        exit;
    }

    $sql = "SELECT u.id, t.fecha, t.token 
            FROM tokens_contrasena t 
            JOIN usuarias u ON u.id = t.id_usuaria 
            WHERE t.token = :token 
              AND t.fecha <= DATE_SUB(NOW(), INTERVAL 15 MINUTE)  
            LIMIT 1";

    $stmt = $con->prepare($sql);
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() == 0) {
        header("Location: {$urlBase}Vista/registro.php");
        exit;
    }
} catch (\Throwable $th) {
    error_log("Error en validación de token: " . $th->getMessage());
    header("Location: {$urlBase}Vista/registro.php");
    exit;
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
    include $_SERVER['DOCUMENT_ROOT'] . '/shakti//components/usuaria/navbar.php';
    ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="d-flex flex-column">
    <main class="flex-grow-1 d-flex align-items-center justify-content-center">
        <div class="auth-container">
            <div class="auth-header text-center mb-4">
                <h1 class="h3 fw-bold text-secondary">Cambiar Contraseña</h1>
            </div>
            <form class="auth-form" id="registroForm" novalidate action="../Controlador/cambiarContraCorreo.php?opcion=2" method="post">
                <div class="mb-3 position-relative">
                    <label for="contraseña" class="form-label">Contraseña</label>
                    <div class="input-group">
                        <input type="password" class="form-control" name="contraseña" id="contraseña" placeholder="Ingrese su nueva contraseña" />
                        <button class="btn btn-outline" type="button" id="togglePassword">
                            <i class="bi bi-eye-fill" id="iconPassword"></i>
                        </button>
                        <input type="hidden" name="token" value="<?php echo isset($token) ? htmlspecialchars($token) : "" ?>">
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-purple w-100 shadow-sm fw-semibold" id="cambiar">Cambiar</button>
                </div>
            </form>
        </div>
        <script src="<?= $urlBase ?>peticiones(js)/verContra.js"></script>
        <script src="<?= $urlBase ?>validacionRegistro/validarContra.js"></script>
        <script src="<?= $urlBase ?>peticiones(js)/mandarMetricas.js.php?vista=<?= urlencode(basename($_SERVER['PHP_SELF'])) ?>"></script>
    </main>
    <?php include '../components/usuaria/footer.php'; ?>

</body>

</html>