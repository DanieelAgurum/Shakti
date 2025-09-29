<?php
require_once '../obtenerLink/obtenerLink.php';
$urlBase = getBaseUrl();
$status = isset($_GET['status']) ? $_GET['status'] : '';
$message = isset($_GET['message']) ? $_GET['message'] : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confirmar Correo - Shakti</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .container {
            max-width: 450px;
            margin-top: 100px;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container text-center">
        <h2>Confirmar Correo</h2>
        
        <?php if ($status && $message): ?>
            <div class="alert alert-<?= $status === 'success' ? 'success' : 'danger' ?>">
                <?= htmlspecialchars($message) ?>
            </div>
            <?php if($status === 'success'): ?>
                <script>
                    // Redirigir automáticamente al login después de 3 segundos
                    setTimeout(() => {
                        window.location.href = "<?= $urlBase ?>/Vista/login.php";
                    }, 3000);
                </script>
            <?php endif; ?>
        <?php endif; ?>
        
        <form action="<?= $urlBase ?>/Controlador/confirmarCorreo.php?opcion=1" method="POST">
            <div class="mb-3 text-start">
                <label for="correo" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="correo" name="correo" required placeholder="tu@email.com">
            </div>
            <button type="submit" class="btn btn-primary w-100">Enviar Correo de Confirmación</button>
        </form>
    </div>
</body>
</html>
