<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/obtenerLink/obtenerLink.php';
$urlBase = getBaseUrl();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['id_rol'])) {
    switch ($_SESSION['id_rol']) {
        case 1:
            header("Location: " . $urlBase . "Vista/usuaria/perfil.php");
            exit;
        case 2:
            header("Location: " . $urlBase . "Vista/uespecialista/perfil.php");
            exit;
        case 3:
            header("Location: " . $urlBase . "Vista/admin/");
            exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Registro - Shakti</title>
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
                <h1 class="h3 fw-bold text-secondary">Crear cuenta</h1>
            </div>

            <form class="auth-form" id="registroForm" novalidate action="../Controlador/UsuariasControlador.php" method="post">

                <!-- Nombre -->
                <div class="mb-3 position-relative">
                    <label for="nombre" class="form-label">Nombre</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Ingrese su(s) nombre(s)" />
                    </div>
                    <small class="error" id="errorNombre"></small>
                </div>

                <!-- Apellidos -->
                <div class="mb-3 position-relative">
                    <label for="apellidos" class="form-label">Apellidos</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="apellidos" id="apellidos" placeholder="Ingrese sus apellidos" />
                    </div>
                    <small class="error" id="errorApellidos"></small>
                </div>

                <!-- Nickname -->
                <div class="mb-3 position-relative">
                    <label for="nickname" class="form-label">Nombre de usuario</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="nickname" id="nickname" placeholder="Ingrese su nombre de usuario" />
                    </div>
                    <small class="error" id="errorNickname"></small>
                </div>

                <!-- Correo -->
                <div class="mb-3 position-relative">
                    <label for="correo" class="form-label">Correo electrónico</label>
                    <div class="input-group">
                        <input type="email" class="form-control" name="correo" id="correo" placeholder="Ingrese su correo electrónico" />
                    </div>
                    <small class="error" id="errorCorreo"></small>
                </div>

                <!-- Contraseña -->
                <div class="mb-3 position-relative">
                    <label for="contraseña" class="form-label">Contraseña</label>
                    <div class="input-group">
                        <input type="password" class="form-control" name="contraseña" id="contraseña" placeholder="Ingrese su contraseña" />
                    </div>
                    <small class="error" id="errorContraseña"></small>
                </div>

                <!-- Confirmar contraseña -->
                <div class="mb-3 position-relative">
                    <label for="conContraseña" class="form-label">Confirmar contraseña</label>
                    <div class="input-group">
                        <input type="password" class="form-control" name="conContraseña" id="conContraseña" placeholder="Ingrese nuevamente su contraseña" />
                    </div>
                    <small class="error" id="errorConContraseña"></small>
                </div>

                <!-- Fecha de nacimiento -->
                <div class="mb-3 position-relative">
                    <label for="fecha_nac" class="form-label">Fecha de nacimiento</label>
                    <div class="input-group">
                        <input type="date" class="form-control" name="fecha_nac" id="fecha_nac" />
                    </div>
                    <small class="error" id="errorFecha_nac"></small>
                </div>

                <!-- Checkbox de términos y condiciones -->
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="terminosCheck" />
                        <label class="form-check-label" for="terminosCheck"> Acepto términos y condiciones</label>
                    </div>
                    <small class="error" id="errorTerminos"></small>
                </div>

                <!-- Checkbox para especialista -->
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="especialistaCheck" onchange="actualizarRol()" />
                        <label class="form-check-label" for="especialistaCheck"> Registrar como especialista</label>
                    </div>
                </div>

                <!-- Campos ocultos -->
                <input type="hidden" name="rol" id="rol" value="1" />
                <input type="hidden" name="opcion" value="1" />

                <!-- Botón -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-purple w-100 shadow-sm fw-semibold">Enviar</button>
                </div>
            </form>

            <div class="auth-footer text-center mt-3">
                <a href="<?php echo $urlBase ?>Vista/login.php">¿Ya tienes una cuenta? Inicia sesión</a>
            </div>
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

        <script src="../validacionRegistro/validacion.js"></script>
        <script src="<?= $urlBase ?>peticiones(js)/mandarMetricas.js.php?vista=<?= urlencode(basename($_SERVER['PHP_SELF'])) ?>"></script>
    </main>
    <?php include '../components/usuaria/footer.php'; ?>

</body>

</html>