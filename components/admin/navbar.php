<link href="https://fonts.googleapis.com/css2?family=Fuggles&family=Lato&family=Mooli&display=swap" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
<link href="../../components/admin/datatables.min.css" rel="stylesheet" />
<link href="../../components/admin/styles.css" rel="stylesheet" />
<script src="https://kit.fontawesome.com/3c934cb418.js" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
<script src="../../components/admin/js/datatables.min.js"></script>
<script src="../../components/admin/js/scripts.js"></script>
<script src="../../components/admin/js/chart-area-demo.js"></script>
<script src="../../components/admin/js/chart-bar-demo.js"></script>
<script src="../../components/admin/js/datatables-simple-demo.js"></script>

<nav class="sb-topnav navbar navbar-expand">
    <a class="navbar-brand ps-3" href="<?php echo $urlBase ?>Vista/admin"></a>
    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        <div class="input-group"></div>
    </form>
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-dark" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-user fa-fw text-dark"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item text-danger" href="<?php echo $urlBase ?>/Controlador/loginCtrl.php?opcion=2">Cerrar Sesión <i class="bi bi-door-open-fill"></i></a></li>
            </ul>
        </li>
    </ul>
</nav>