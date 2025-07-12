 <div id="layoutSidenav_nav">
     <nav class="sb-sidenav accordion" id="sidenavAccordion">
         <div class="sb-sidenav-menu">
             <div class="nav">
                 <!-- Inicio -->
                 <a class="nav-link text-dark" href="<?php echo $urlBase ?>Vista/admin">
                     <div class="sb-nav-link-icon"><i class="fa-solid fa-house text-dark"></i></div>
                     Inicio
                 </a>

                 <!-- Opciones (menú principal) -->
                 <a class="nav-link collapsed text-dark" href="#" data-bs-toggle="collapse" data-bs-target="#collapseOpciones" aria-expanded="false" aria-controls="collapseOpciones">
                     <div class="sb-nav-link-icon"><i class="fa-solid fa-bars text-dark"></i></div>
                     Opciones
                     <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down text-dark"></i></div>
                 </a>

                 <!-- Submenús dentro de Opciones -->
                 <div class="collapse" id="collapseOpciones" aria-labelledby="headingOpciones" data-bs-parent="#sidenavAccordion">
                     <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionOpciones">

                         <!-- Submenú: Tablas -->
                         <a class="nav-link collapsed text-dark" href="#" data-bs-toggle="collapse" data-bs-target="#submenuTablas" aria-expanded="false" aria-controls="submenuTablas">
                             <div class="sb-nav-link-icon"><i class="fa-solid fa-table text-dark"></i></div>
                             Tablas
                             <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down text-dark"></i></div>
                         </a>
                         <div class="collapse" id="submenuTablas" data-bs-parent="#sidenavAccordionOpciones">
                             <nav class="sb-sidenav-menu-nested nav">
                                 <a class="nav-link text-dark" href="<?php echo $urlBase ?>Vista/admin/usuarias.php">Usuarias</a>
                                 <a class="nav-link text-dark" href="<?php echo $urlBase ?>Vista/admin/especialistas.php">Especialistas</a>
                                 <a class="nav-link text-dark" href="#">Publicaciones</a>
                                 <a class="nav-link text-dark" href="#">Contenido</a>
                                 <a class="nav-link text-dark" href="#">Documentos</a>
                                 <a class="nav-link text-dark" href="#">Organizaciones</a>
                                 <a class="nav-link text-dark" href="<?php echo $urlBase ?>Vista/admin/preguntas_frecuentes.php">Preguntas Frecuentes</a>
                                 <a class="nav-link text-dark" href="#">Comentarios</a>
                             </nav>
                         </div>

                         <!-- Submenú: Administrar -->
                         <a class="nav-link collapsed text-dark" href="#" data-bs-toggle="collapse" data-bs-target="#submenuAdministrar" aria-expanded="false" aria-controls="submenuAdministrar">
                             <div class="sb-nav-link-icon"><i class="fa-solid fa-gear text-dark"></i></div>
                             Administrar
                             <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down text-dark"></i></div>
                         </a>
                         <div class="collapse" id="submenuAdministrar" data-bs-parent="#sidenavAccordionOpciones">
                             <nav class="sb-sidenav-menu-nested nav">
                                 <a class="nav-link text-dark" href="<?php echo $urlBase ?>Vista/admin/reportes.php">Reportes</a>
                                 <a class="nav-link text-dark" href="<?php echo $urlBase ?>Vista/admin/tipos_reportes.php">Tipo de Reportes</a>
                                 <a class="nav-link text-dark" href="<?php echo $urlBase ?>Vista/admin/metricas.php">Métricas</a>
                                 <a class="nav-link text-dark" href="<?php echo $urlBase ?>Vista/admin/publicaciones.php">CRUD Publicaciones</a>
                             </nav>
                         </div>

                     </nav>
                 </div>
             </div>
         </div>
     </nav>
 </div>