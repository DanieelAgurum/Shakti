<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instituciones</title>
    <!-- Librerías adicionales en el head del navbar -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.theme.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

    <!-- Scripts únicos -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/usuaria/navbar.php'; ?>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="css/styles_in_con.css">
</head>

<body class="recurso-pagina-fondo">

    <div class="recurso-pagina-principal container my-5">
        <h1 class="recurso-titulo-principal text-center mb-5"> Instituciones</h1>

        <div class="recurso-tarjetas-fila row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">

            <div class="col">
                <div class="recurso-card-base card h-100 shadow-sm">
                    <div class="recurso-card-img-container">
                        <img src="https://plus.unsplash.com/premium_photo-1723928563034-93381fd4f60d?ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&q=80&w=871" class="recurso-card-img-top card-img-top" alt="Imagen representativa de GENDES">
                    </div>
                    <div class="recurso-card-body card-body d-flex flex-column">
                        <h5 class="recurso-card-titulo card-title">GENDES (Género y Desarrollo, A.C.)</h5>
                        <p class="recurso-card-texto-breve card-text text-muted">Programas "Hombres trabajando(se)".</p>
                        <button type="button" class="recurso-card-boton btn btn-primary mt-auto" data-bs-toggle="modal" data-bs-target="#modalCard1">
                            Ver más...
                        </button>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="recurso-card-base card h-100 shadow-sm">
                    <div class="recurso-card-img-container">
                        <img src="https://plus.unsplash.com/premium_photo-1723928563034-93381fd4f60d?ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8aG9tYnJlcyUyMHBsYXRpY2FuZG98ZW58MHx8MHx8fDA%3D&auto=format&fit=crop&q=60&w=500" class="recurso-card-img-top card-img-top" alt="Imagen representativa ">
                    </div>
                    <div class="recurso-card-body card-body d-flex flex-column">
                        <h5 class="recurso-card-titulo card-title">Clínicas del Hombre (Clinicasdelhombre.com)</h5>
                        <p class="recurso-card-texto-breve card-text text-muted">Psicología especializada para hombres.</p>
                        <button type="button" class="recurso-card-boton btn btn-primary mt-auto" data-bs-toggle="modal" data-bs-target="#modalCard2">
                            Ver más...
                        </button>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="recurso-card-base card h-100 shadow-sm">
                    <div class="recurso-card-img-container">
                        <img src="https://plus.unsplash.com/premium_photo-1723928563034-93381fd4f60d?ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8aG9tYnJlcyUyMHBsYXRpY2FuZG98ZW58MHx8MHx8fDA%3D&auto=format&fit=crop&q=60&w=500" class="recurso-card-img-top card-img-top" alt="Imagen representativa de Hombres por la Equidad">
                    </div>
                    <div class="recurso-card-body card-body d-flex flex-column">
                        <h5 class="recurso-card-titulo card-title">HOMBRES POR LA EQUIDAD, A.C. (HxE)</h5>
                        <p class="recurso-card-texto-breve card-text text-muted">Terapia y capacitación para la equidad.</p>
                        <button type="button" class="recurso-card-boton btn btn-primary mt-auto" data-bs-toggle="modal" data-bs-target="#modalCard3">
                            Ver más...
                        </button>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="recurso-card-base card h-100 shadow-sm">
                    <div class="recurso-card-img-container">
                        <img src="https://plus.unsplash.com/premium_photo-1723928563034-93381fd4f60d?ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8aG9tYnJlcyUyMHBsYXRpY2FuZG98ZW58MHx8MHx8fDA%3D&auto=format&fit=crop&q=60&w=500" class="recurso-card-img-top card-img-top" alt="Imagen representativa de DIF CDMX">
                    </div>
                    <div class="recurso-card-body card-body d-flex flex-column">
                        <h5 class="recurso-card-titulo card-title">DIF CDMX - Servicio Psicológico y Reeducativo</h5>
                        <p class="recurso-card-texto-breve card-text text-muted">Atención reeducativa para la no-violencia.</p>
                        <button type="button" class="recurso-card-boton btn btn-primary mt-auto" data-bs-toggle="modal" data-bs-target="#modalCard4">
                            Ver más...
                        </button>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="recurso-card-base card h-100 shadow-sm">
                    <div class="recurso-card-img-container">
                        <img src="https://plus.unsplash.com/premium_photo-1723928563034-93381fd4f60d?ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8aG9tYnJlcyUyMHBsYXRpY2FuZG98ZW58MHx8MHx8fDA%3D&auto=format&fit=crop&q=60&w=500" class="recurso-card-img-top card-img-top" alt="Imagen representativa de CUSA UNAM">
                    </div>
                    <div class="recurso-card-body card-body d-flex flex-column">
                        <h5 class="recurso-card-titulo card-title">Centro de Salud Mental y Género, UNAM (CUSA)</h5>
                        <p class="recurso-card-texto-breve card-text text-muted">Atención psicológica y psiquiátrica.</p>
                        <button type="button" class="recurso-card-boton btn btn-primary mt-auto" data-bs-toggle="modal" data-bs-target="#modalCard5">
                            Ver más...
                        </button>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="recurso-card-base card h-100 shadow-sm">
                    <div class="recurso-card-img-container">
                        <img src="https://plus.unsplash.com/premium_photo-1723928563034-93381fd4f60d?ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8aG9tYnJlcyUyMHBsYXRpY2FuZG98ZW58MHx8MHx8fDA%3D&auto=format&fit=crop&q=60&w=500" class="recurso-card-img-top card-img-top" alt="Imagen representativa de Asociación SELF">
                    </div>
                    <div class="recurso-card-body card-body d-flex flex-column">
                        <h5 class="recurso-card-titulo card-title">Asociación SELF</h5>
                        <p class="recurso-card-texto-breve card-text text-muted">Consulta psicológica, psiquiátrica, orientación social.</p>
                        <button type="button" class="recurso-card-boton btn btn-primary mt-auto" data-bs-toggle="modal" data-bs-target="#modalCard6">
                            Ver más...
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="modalCard1" tabindex="-1" aria-labelledby="modalCard1Label" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content recurso-modal-contenido">
                <div class="modal-header recurso-modal-header">
                    <h5 class="modal-title recurso-modal-titulo" id="modalCard1Label">GENDES (Género y Desarrollo, A.C.)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body recurso-modal-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3">
                            <img src="" alt="Imagen ampliada de GENDES">
                            <span class="badge bg-primary recurso-badge-servicio">Terapia</span>
                            <span class="badge bg-primary recurso-badge-servicio">Reeducación</span>
                        </div>
                        <div class="col-md-8 recurso-modal-texto-contenido">
                            <p class="recurso-modal-subtitulo fw-bold">Programas "Hombres trabajando(se)"</p>
                            <p>Organización con programas que promueven relaciones igualitarias y la no violencia. Ofrecen terapia y programas reeducativos.</p>
                            <h6 class="mt-4 recurso-modal-seccion-titulo">Servicios Clave:</h6>
                            <ul class="recurso-modal-lista-servicios">
                                <li>Programas reeducativos grupales</li>
                                <li>Terapia individual con enfoque de género</li>
                            </ul>
                            <div class="recurso-modal-contacto mt-3 p-3 border-start border-3 bg-light">
                                <p class="fw-bold mb-1">Contacto Rápido:</p>
                                <p class="mb-1">
                                    Línea de Apoyo GENDES: <a href="tel:+525547579288" class="fw-bold text-success">55 4757 9288</a> (Servicio sin costo, 24 horas, 365 días).
                                </p>
                            </div>
                            <p class="mt-3">
                                <a href="https://gendes.org.mx/" target="_blank" class="recurso-modal-boton-web btn btn-sm btn-outline-secondary">Ir al Sitio Web</a>
                                <span class="text-muted">(Sito web: GENDES A.C.)</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer recurso-modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalCard2" tabindex="-1" aria-labelledby="modalCard2Label" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content recurso-modal-contenido">
                <div class="modal-header recurso-modal-header">
                    <h5 class="modal-title recurso-modal-titulo" id="modalCard2Label">Clínicas del Hombre (Clinicasdelhombre.com)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body recurso-modal-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3">
                            <img src="https://images.unsplash.com/photo-1542838703-9acb76e2d1fe?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="recurso-modal-img img-fluid" alt="Imagen ampliada de Clínicas del Hombre">
                            <span class="badge bg-primary recurso-badge-servicio">Terapia Individual</span>
                            <span class="badge bg-info recurso-badge-servicio">Cognitivo-Conductual</span>
                        </div>
                        <div class="col-md-8 recurso-modal-texto-contenido">
                            <p class="recurso-modal-subtitulo fw-bold">Especialistas en Salud Mental Masculina</p>
                            <p>Clínica con psicólogos que se especializan en la salud mental del hombre y en problemáticas que les son comunes, ofreciendo terapia cognitiva conductual y psicoanalítica.</p>
                            <h6 class="mt-4 recurso-modal-seccion-titulo">Servicios Clave:</h6>
                            <ul class="recurso-modal-lista-servicios">
                                <li>Terapia Cognitivo-Conductual (TCC)</li>
                                <li>Terapia Psicoanalítica y Gestalt</li>
                            </ul>
                            <div class="recurso-modal-contacto mt-3 p-3 border-start border-3 bg-light">
                                <p class="fw-bold mb-1">Contacto Rápido:</p>
                                <p class="mb-1">
                                    **Teléfono CDMX:** <a href="tel:+525588547316" class="fw-bold text-success">55 8854 7316</a>
                                </p>
                            </div>
                            <p class="mt-3">
                                <a href="https://www.clinicasdelhombre.com" target="_blank" class="recurso-modal-boton-web btn btn-sm btn-outline-secondary">Ir al Sitio Web</a>
                                <span class="text-muted">(Sito web: Clinicasdelhombre.com)</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer recurso-modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalCard3" tabindex="-1" aria-labelledby="modalCard3Label" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content recurso-modal-contenido">
                <div class="modal-header recurso-modal-header">
                    <h5 class="modal-title recurso-modal-titulo" id="modalCard3Label">HOMBRES POR LA EQUIDAD, A.C. </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body recurso-modal-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3">
                            <img src="https://images.unsplash.com/photo-1517457210348-b7c53b708605?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="recurso-modal-img img-fluid" alt="Imagen ampliada de Hombres por la Equidad">
                            <span class="badge bg-primary recurso-badge-servicio">Terapia de Género</span>
                        </div>
                        <div class="col-md-8 recurso-modal-texto-contenido">
                            <p class="recurso-modal-subtitulo fw-bold">Investigación y Terapias para la Equidad de Género</p>
                            <p>Institución dedicada a la investigación, capacitación, cursos y terapias para la equidad de género, cuestionando la masculinidad tradicional.</p>
                            <h6 class="mt-4 recurso-modal-seccion-titulo">Servicios Clave:</h6>
                            <ul class="recurso-modal-lista-servicios">
                                <li>Investigación sobre masculinidades</li>
                                <li>Capacitación y cursos de equidad</li>
                                <li>Terapia con perspectiva de género</li>
                            </ul>
                            <p class="mt-3">
                                Sitio web para contacto:
                                <a href="https://hombresporlaequidad.org/contacto/" target="_blank" class="recurso-modal-boton-web btn btn-sm btn-primary">Ir al Sitio Web</a>
                                <span class="text-muted">(Hombres por la Equidad)</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer recurso-modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalCard4" tabindex="-1" aria-labelledby="modalCard4Label" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content recurso-modal-contenido">
                <div class="modal-header recurso-modal-header">
                    <h5 class="modal-title recurso-modal-titulo" id="modalCard4Label">DIF CDMX - Servicio de Atención Psicológica y Reeducativa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body recurso-modal-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3">
                            <img src="https://images.unsplash.com/photo-1550796339-4467c64267e7?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="recurso-modal-img img-fluid" alt="Imagen ampliada de DIF CDMX">
                            <span class="badge bg-primary recurso-badge-servicio">Gratuito</span>
                            <span class="badge bg-warning text-dark recurso-badge-servicio">No-Violencia</span>
                        </div>
                        <div class="col-md-8 recurso-modal-texto-contenido">
                            <p class="recurso-modal-subtitulo fw-bold">Atención a Hombres que Ejercen Violencia de Género</p>
                            <p>Programa específico del gobierno de la CDMX (DIF) enfocado en la atención reeducativa para la no-violencia, que también aborda la salud mental de los hombres.</p>
                            <h6 class="mt-4 recurso-modal-seccion-titulo">Servicios Clave:</h6>
                            <ul class="recurso-modal-lista-servicios">
                                <li>Atención Psicológica</li>
                                <li>Talleres y pláticas reeducativas</li>
                            </ul>
                            <div class="recurso-modal-contacto mt-3 p-3 border-start border-3 bg-light">
                                <p class="fw-bold mb-1">Contacto Rápido:</p>
                                <p class="mb-1">
                                    **Teléfono:** <a href="tel:+525550063807" class="fw-bold text-success">55 5006 3807</a>
                                </p>
                            </div>
                            <p class="mt-3">
                                <a href="https://www.dif.cdmx.gob.mx/servicios/servicio/servicio-de-atencion-psicologica-y-reeducativa-a-hombres-que-ejercen-violencia-de-genero" target="_blank" class="recurso-modal-boton-web btn btn-sm btn-outline-secondary">Ir a la Información Oficial</a>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer recurso-modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalCard5" tabindex="-1" aria-labelledby="modalCard5Label" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content recurso-modal-contenido">
                <div class="modal-header recurso-modal-header">
                    <h5 class="modal-title recurso-modal-titulo" id="modalCard5Label">CUSA UNAM - Centro de Salud Mental y Género</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body recurso-modal-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3">
                            <img src="https://images.unsplash.com/photo-1556761175-5722421d034a?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="recurso-modal-img img-fluid" alt="Imagen ampliada de CUSA UNAM">
                            <span class="badge bg-success recurso-badge-costo">Costo Accesible</span>
                            <span class="badge bg-primary recurso-badge-servicio">Psiquiatría</span>
                        </div>
                        <div class="col-md-8 recurso-modal-texto-contenido">
                            <p class="recurso-modal-subtitulo fw-bold">Clínicas Universitarias para la Atención a la Salud</p>
                            <p>Ofrecen atención psicológica y psiquiátrica. Tienen varias sedes (Aurora, Reforma, Benito Juárez, Los Reyes, Zaragoza, etc.).</p>
                            <h6 class="mt-4 recurso-modal-seccion-titulo">Servicios Clave:</h6>
                            <ul class="recurso-modal-lista-servicios">
                                <li>Terapia Individual y de Pareja</li>
                                <li>Evaluación Psicológica y Psiquiátrica</li>
                            </ul>
                            <div class="recurso-modal-contacto mt-3 p-3 border-start border-3 bg-light">
                                <p class="fw-bold mb-1">Teléfonos de Referencia (Sedes):</p>
                                <ul>
                                    <li>Aurora: <a href="tel:+525557340976" class="fw-bold text-success">55 5734 0976</a></li>
                                    <li>Reforma: <a href="tel:+525557425393" class="fw-bold text-success">55 5742 5393</a></li>
                                </ul>
                            </div>
                            <p>
                                <a href="https://www.cusa.unam.mx/servicios" target="_blank" class="recurso-modal-boton-web btn btn-sm btn-outline-secondary">Buscar más Sedes y Contacto</a>
                                <span class="text-muted">(Sitio web UNAM CUSA)</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer recurso-modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalCard6" tabindex="-1" aria-labelledby="modalCard6Label" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content recurso-modal-contenido">
                <div class="modal-header recurso-modal-header">
                    <h5 class="modal-title recurso-modal-titulo" id="modalCard6Label">Asociación SELF</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body recurso-modal-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3">
                            <img src="https://images.unsplash.com/photo-1542744198-d8f99e846059?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="recurso-modal-img img-fluid" alt="Imagen ampliada de Asociación SELF">
                            <span class="badge bg-primary recurso-badge-servicio">Apoyo Social</span>
                        </div>
                        <div class="col-md-8 recurso-modal-texto-contenido">
                            <p class="recurso-modal-subtitulo fw-bold">Asociación SELF</p>
                            <p>Es una AC en la CDMX conformada por profesionales (psiquiatría, psicología clínica y comunitaria) que trabaja con personas que han vivido violencia o están en alta vulnerabilidad social.</p>
                            <h6 class="mt-4 recurso-modal-seccion-titulo">Servicios Clave:</h6>
                            <ul class="recurso-modal-lista-servicios">
                                <li>Consulta Psicológica y Psiquiátrica</li>
                                <li>Orientación Social</li>
                            </ul>
                            <p>
                                <a href="https://asociacionself.org/?utm_source=chatgpt.com" target="_blank" class="recurso-modal-boton-web btn btn-sm btn-outline-secondary mt-3">Sitio web</a>
                                <span class="text-muted">(Asociación SELF)</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer recurso-modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <?php
    include $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/usuaria/footer.php';
    ?>
</body>

</html>