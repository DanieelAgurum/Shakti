<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/obtenerLink/obtenerLink.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/modelo/conexion.php';
$urlBase = getBaseUrl();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['correo']) || $_SESSION['id_rol'] == 2) {
    header("Location: {$urlBase}Vista/especialista/perfil.php");
    exit;
} else if (empty($_SESSION['correo']) || $_SESSION['id_rol'] == 3) {
    header("Location: {$urlBase}Vista/admin");
    exit;
} else if (empty($_SESSION['correo']) || $_SESSION['id_rol'] != 1) {
    header("Location: {$urlBase}index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Especialistas - Shakti</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <?php include '../../components/usuaria/navbar.php'; ?>
</head>

<body>

    <!-- Buscador -->
    <div class="search-wrapper w-100">
        <div class="search-box">
            <i class="bi bi-search search-icon"></i>
            <input type="text" class="form-control search-input" name="especialista" placeholder="Busca a un especialista...">
        </div>
    </div>

    <!-- Cards -->
    <div class="container">
        <div class="row" id="resultados">
            <?php
            $db = (new ConectarDB())->open();
            $sql = "SELECT id, nombre, apellidos, correo, foto FROM usuarias WHERE estatus = 1 AND id_rol = 2";
            $stmt = $db->query($sql);

            foreach ($stmt as $row) {
            ?>
                <div class="col-md-4 mb-4">
                    <div class="card testimonial-card animate__animated animate__backInUp">
                        <div class="card-up aqua-gradient"></div>
                        <div class="avatar mx-auto white">
                            <?php
                            $foto = $row['foto'];
                            $src = $foto ? 'data:image/jpeg;base64,' . base64_encode($foto) : 'https://cdn1.iconfinder.com/data/icons/avatar-3/512/Secretary-512.png';
                            ?>
                            <img src="<?= $src ?>" class="rounded-circle" width="150" height="150" alt="Especialista">
                        </div>
                        <div class="card-body text-center">
                            <h4 class="card-title font-weight-bold">
                                <?= ucwords(htmlspecialchars($row['nombre'] . ' ' . $row['apellidos'])) ?>
                            </h4>
                            <hr>
                            <p><i class="fas fa-quote-left"></i> <?= htmlspecialchars($row['descripcion'] ?? 'Especialista en bienestar y atención a víctimas.') ?></p>
                            <a href="<?= $urlBase ?>Vista/usuaria/perfil_especialista.php?id=<?= $row['id'] ?>" class="btn btn-outline-success mt-2">Ver perfil</a>
                            <a href="<?= $urlBase ?>Vista/usuaria/perfil_especialista.php?id=<?= $row['id'] ?>" class="btn btn-outline-primary mt-2">Mensaje</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('input[name="especialista"]').on('keyup', function() {
                var especialista = $(this).val();

                $.ajax({
                    url: '../../modelo/buscar_especialistas.php',
                    type: 'GET',
                    data: {
                        especialista: especialista
                    },
                    success: function(response) {
                        $('#resultados').html(response);
                    }

                });
                console.log(especialista);
            });
        });
    </script>
    <script src="<?= $urlBase ?>peticiones(js)/mandarMetricas.js.php?vista=<?= urlencode(basename($_SERVER['PHP_SELF'])) ?>"></script>
    <?php include '../../components/usuaria/footer.php'; ?>
</body>

</html>