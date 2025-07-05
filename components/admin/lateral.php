<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <a class="nav-link text-dark" href="<?php echo $urlBase ?>Vista/admin">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-house text-dark"></i></div>
                    Inicio
                </a>
                <a class="nav-link collapsed text-dark" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-bars text-dark"></i></div>
                    Opciones
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down text-dark"></i></div>
                </a>
                <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                        <a class="nav-link collapsed text-dark" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseError" aria-expanded="false" aria-controls="pagesCollapseError">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-table text-dark"></i></div>
                            Tablas
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down text-dark"></i></div>
                        </a>
                        <div class="collapse" id="pagesCollapseError" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link text-dark" href="<?php echo $urlBase  ?>Vista/admin/usuarias.php">Usuarias</a>
                                <a class="nav-link text-dark" href="<?php echo $urlBase  ?>Vista/admin/especialistas.php">Especialistas</a>
                                <a class="nav-link text-dark" href="#">Publicaciones</a>
                                <a class="nav-link text-dark" href="#">Contenido</a>
                                <a class="nav-link text-dark" href="#">Documentos</a>
                                <a class="nav-link text-dark" href="#">Organizaciones</a>
                                <a class="nav-link text-dark" href="#">Reportes</a>
                                <a class="nav-link text-dark" href="#">Comentarios</a>
                                <a class="nav-link text-dark" href="<?php echo $urlBase  ?>Vista/admin/metricas.php">Métricas</a>
                            </nav>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </nav>
</div>