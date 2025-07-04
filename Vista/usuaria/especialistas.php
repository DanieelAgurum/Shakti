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
    <style>
        .testimonial-card .card-up {
            height: 120px;
            overflow: hidden;
            border-top-left-radius: .25rem;
            border-top-right-radius: .25rem;
        }

        .aqua-gradient {
            background-image: linear-gradient(to top, #fad0c4 0%, #ffd1ff 100%) !important;
        }

        .testimonial-card .avatar {
            width: 150px;
            height: 150px;
            margin-top: -60px;
            overflow: hidden;
            border: 5px solid #fff;
            border-radius: 50%;
        }

        .search-wrapper {
            max-width: 600px;
            margin: 20px auto;
            border: 1px solid #e0e0e0;
            border-radius: 50px;
        }

        .search-box {
            position: relative;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .search-box:focus-within {
            box-shadow: 0 3px 20px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .search-input {
            border-radius: 50px;
            padding-left: 45px;
            padding-right: 20px;
            border: 2px solid transparent;
            transition: all 0.3s ease;
            height: 50px;
        }

        .search-input:focus {
            border-color: #4b0082;
            box-shadow: none;
        }

        .search-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            z-index: 10;
        }

        /* .card-animate {
            animation: fadeInUp 0.5s ease-in-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        } */


        /* .testimonial-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .testimonial-card:hover {
            transform: scale(1.03);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        } */


        .card-up::before {
            content: "";
            position: absolute;
            top: 0;
            left: -75%;
            width: 50%;
            height: 100%;
            background: rgba(255, 255, 255, 0.3);
            transform: skewX(-25deg);
            animation: shine 2s infinite;
        }

        @keyframes shine {
            0% {
                left: -75%;
            }

            100% {
                left: 125%;
            }
        }
    </style>
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
    <?php include '../modales/perfil.php'; ?>
    <?php include '../../components/usuaria/footer.php'; ?>
</body>

</html>