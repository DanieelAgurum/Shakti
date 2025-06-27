<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['correo']) || $_SESSION['id_rol'] != 1) {
    header("Location: ../../index.php");
    exit;
}


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Perfil - Shakti</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <?php include '../../components/usuaria/navbar.php'; ?>
</head>

<body>

    <div class="container mt-5">
        <div class="main-body">
            <div class="row gutters-sm">
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-column align-items-center text-center">
                                <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Admin" class="rounded-circle" width="150">
                                <div class="mt-3">
                                    <h4><?php echo isset($_SESSION['nombre']) ? ucwords(strtolower($_SESSION['nombre'])) : " " ?></h4>
                                    <p class="text-secondary mb-1"><?php echo isset($_SESSION['nombre_rol']) ? ucwords(strtolower($_SESSION['nombre_rol'])) : " " ?></p>
                                    <p class="text-muted font-size-sm"><?php echo isset($_SESSION['direccion']) ? ucwords(strtolower($_SESSION['direccion'])) : " " ?></p>
                                    <button class="btn btn-outline-primary">Mensaje</button>
                                    <button class="btn btn-secondary">Diario</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Nombre completo</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <?php
                                    echo (isset($_SESSION['nombre']) && isset($_SESSION['apellidos']))
                                        ? ucwords(strtolower($_SESSION['nombre'])) . ' ' . ucwords(strtolower($_SESSION['apellidos']))
                                        : " ";
                                    ?>
                                </div>

                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Correo eléctronico</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <?php echo isset($_SESSION['correo']) ? $_SESSION['correo'] : " "; ?>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Teléfono</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <?php echo isset($_SESSION['telefono']) ? $_SESSION['telefono'] : " "; ?>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Dirección</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <?php echo isset($_SESSION['direccion']) ? $_SESSION['direccion'] : " "; ?>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Nickname</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <?php echo isset($_SESSION['nickname']) ? $_SESSION['nickname'] : ""  ?>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-12">
                                    <a href="#addNew" class="btn btn-primary" data-toggle="modal" style="margin-bottom: 8px;"><i class="fa-solid fa-circle-plus"></i> Editar perfil</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include '../../components/usuaria/footer.php'; ?>
</body>

</html>