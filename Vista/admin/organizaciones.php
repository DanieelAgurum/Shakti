<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/obtenerLink/obtenerLink.php';
$urlBase = getBaseUrl();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['correo'])) {
    header("Location: {$urlBase}");
    exit;
}

require_once 'organizacionesModelo.php';
$organizacion = new organizacionesModelo();
$organizaciones = $organizacion->getAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organizaciones - Shakti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        table {
            background-color: #ecf0f1;
        }
        th, td {
            text-align: center;
            vertical-align: middle;
        }
        img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 50%;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Nuestras Organizaciones</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Numero</th>
                    <th>Imagen</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($organizaciones as $org): ?>
                    <tr>
                        <td><?php echo $org['nombre']; ?></td>
                        <td><?php echo $org['descripcion']; ?></td>
                        <td><?php echo $org['numero']; ?></td>
                        <td>
                            <?php if (!empty($org['imagen'])): ?>
                                <img src="data:image/*;base64,<?php echo base64_encode($org['imagen']); ?>" alt="<?php echo $org['nombre']; ?>">
                            <?php else: ?>
                                <img src="https://via.placeholder.com/60" alt="Default Image">
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>