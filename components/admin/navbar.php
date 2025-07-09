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