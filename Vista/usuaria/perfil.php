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
    <title>Perfil <?php echo isset($_SESSION['nombre']) ? $_SESSION['nombre'] : " " ?> - Shakti</title>
    <link rel="stylesheet" href="../css/estilos.css" />
    <link rel="stylesheet" href="../css/registro.css" />
    <link rel="stylesheet" href="../css/perfil.css" />
    <link rel="stylesheet" href="../css/footer.css" />
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
                                    <h4><?php echo isset($_SESSION['nombre']) ? $_SESSION['nombre'] : " " ?></h4>
                                    <!-- <p class="text-secondary mb-1">Full Stack Developer</p> -->
                                    <p class="text-muted font-size-sm"><?php echo isset($_SESSION['direccion']) ? $_SESSION['direccion'] : " "; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form action="../../Controlador/loginCtrl.php" method="post" class="mt-4">
                        <input type="hidden" name="opcion" value="2">
                        <input type="submit" value="Cerrar Sesion">
                    </form>
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
                                        ? $_SESSION['nombre'] . ' ' . $_SESSION['apellidos']
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

                    <div class="row gutters-sm">
                        <div class="col-sm-12 mb-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="d-flex align-items-center mb-3"><i class="material-icons text-info mr-2">Documentos</i>Subidos</h6>
                                    <div class="col-sm-12">
                                        <a href="#addNew" class="btn btn-primary" data-toggle="modal" style="margin-bottom: 8px;"><i class="fa-solid fa-circle-plus"></i>Editar documentos</a>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <small>Website Markup</small>
                                        <div>
                                            <i class="bi bi-pencil-square mx-1"></i>
                                            <i class="bi bi-trash3 mx-1"></i>
                                        </div>
                                    </div>
                                    <div class="progress mb-3" style="height: 5px">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 72%" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>

                                    <div class="progress mb-3" style="height: 5px">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 72%" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small>One Page</small>
                                    <div class="progress mb-3" style="height: 5px">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 89%" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small>Mobile Template</small>
                                    <div class="progress mb-3" style="height: 5px">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 55%" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small>Backend API</small>
                                    <div class="progress mb-3" style="height: 5px">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 66%" aria-valuenow="66" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog w-125" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-center"> <strong> Agregar producto </strong></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <form method="post" action="../../control/productoCtrl.php" enctype="multipart/form-data">
                            <div class="row form-group">
                                <div class="col-sm-2">
                                    <label class="control-label">Foto:</label>
                                </div>
                                <div class="col-sm-10">
                                    <input type="file" class="form-control" name="imagenProduc" required>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-2">
                                    <label class="control-label">Nombre:</label>
                                </div>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="nombreProduc" required>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-2">
                                    <label class="control-label">Categoría:</label>
                                </div>
                                <div class="col-sm-10">
                                    <select name="categoriaProduc" class="form-select" aria-label=".form-select-sm example" required>
                                    </select>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-2">
                                    <label class="control-label">Precio:</label>
                                </div>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name="precioProduc" required>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-2">
                                    <label class="control-label">Stock:</label>
                                </div>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name="stockProduc" required>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-2">
                                    <label class="control-label">Descripción: </label>
                                </div>
                                <div class="col-sm-13">
                                    <textarea name="descripcionProduc" class="form-control" id="exampleFormControlTextarea1" rows="3" required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa-solid fa-ban"></i> Cancelar</button>
                                <input type="hidden" value="1" name="opcion">
                                <button type="submit" name="add" value="addNew" class="btn btn-primary"><i class="fa-solid fa-circle-check"></i> Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" integrity="sha384-7qAoOXltbVP82dhxHAUje59V5r2YsVfBafyUDxEdApLPmcdhBPg1DKg1ERo0BZlK" crossorigin="anonymous"></script>
    <?php include '../components/usuaria/footer.php'; ?>
</body>

</html>