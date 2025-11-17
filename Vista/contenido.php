<?php
require_once '../Modelo/testimoniosMdl.php';
require_once '../Modelo/contenidoMdl.php';
date_default_timezone_set('America/Mexico_City');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/obtenerLink/obtenerLink.php';
$urlBase = getBaseUrl();

$cont = new Contenido();
$cont->conectarBD();
$contenidoTodo = $cont->obtenerTodoContenido();

function blobToBase64($blob)
{
    if (!$blob) return "";
    return "data:image/jpeg;base64," . base64_encode($blob);
}
function pdfBlobToBase64($blob)
{
    if (!$blob) return "";
    return "data:application/pdf;base64," . base64_encode($blob);
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Contenido - NexoH</title>
    <link rel="stylesheet" href="<?= $urlBase ?>/css/contacto.css">
    <link rel="stylesheet" href="<?= $urlBase ?>/css/animacionCarga.css" />
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/usuaria/navbar.php'; ?>
    <style>
        .contenedor-buscador {
            z-index: 1030;
            position: fixed;
            top: 3.5rem;
            left: 0;
            width: 100%;
            background-color: var(--color-fondo-claro);
        }

        .search-box {
            position: relative;
        }
    </style>
</head>

<body>
    <div class="contenedor-buscador mt-3">
        <div class="search-foro buscador-fijo mx-auto">
            <div class="search-box w-100">
                <i class="bi bi-search search-icon"></i>
                <input id="buscadorContenido"
                    type="text"
                    class="form-control search-input"
                    placeholder="Buscar por título, tipo o categoría...">
                </form>
            </div>
        </div>
    </div>

    <section class="contenido-personalizado-section container w-75 mt-5 mb-5 m-auto">
        <div class="contenido-grid row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mt-5">
            <?php while ($row = $contenidoTodo->fetch_assoc()): ?>

                <div class="col">
                    <div class="contenido-card card h-100 border-0 shadow-lg rounded-4 overflow-hidden"
                        data-titulo="<?= htmlspecialchars(mb_strtolower($row['titulo']), ENT_QUOTES) ?>"
                        data-descripcion="<?= htmlspecialchars(mb_strtolower($row['descripcion']), ENT_QUOTES) ?>"
                        data-categoria="<?= htmlspecialchars(mb_strtolower($row['categoria']), ENT_QUOTES) ?>"
                        data-tipo="<?= htmlspecialchars(mb_strtolower($row['tipo']), ENT_QUOTES) ?>">

                        <div class="contenido-img-wrapper position-relative">
                            <img src="<?= $urlBase . 'uploads/thumbnails/' . basename($row['thumbnail']) ?>"
                                class="contenido-img card-img-top" alt="thumbnail">
                        </div>

                        <div class="contenido-body card-body p-4">

                            <div class="mb-2 d-flex gap-2">
                                <span class="contenido-category
                                <?php
                                if ($row['categoria'] == 'Ansiedad') echo 'bg-success text-white';
                                elseif ($row['categoria'] == 'Depresión') echo 'bg-info text-white';
                                elseif ($row['categoria'] == 'Estrés') echo 'bg-warning text-dark'; ?>">
                                    <?= ucfirst($row['categoria']) ?>
                                </span>

                                <span class="contenido-category
                                <?php
                                if ($row['tipo'] == 'infografia') echo 'bg-primary text-white';
                                elseif ($row['tipo'] == 'video') echo 'bg-danger text-white';
                                else echo 'bg-warning text-dark';
                                ?>">
                                    <?= ucfirst($row['tipo']) ?>
                                </span>
                            </div>

                            <h4 class="contenido-card-title mt-3 fw-bold">
                                <?= $row['titulo'] ?>
                            </h4>

                            <p class="contenido-text contenido-descripcion-scroll text-muted">
                                <?= $row['descripcion'] ?>
                            </p>

                            <small class="text-muted d-block mb-3">
                                <i class="far fa-calendar-alt"></i>
                                <?= date("d M Y", strtotime($row['fecha_publicacion'])) ?>
                            </small>

                            <div class="contenido-author d-flex align-items-center mt-2">
                                <img src="../img/NexoH.png" class="rounded-circle me-3" width="40" height="40" alt="Author">
                                <a href="#" data-bs-placement="top" title="Ver contenido"
                                    class="contenido-link fs-5 ms-auto"
                                    data-tipo="<?= $row['tipo'] ?>"
                                    data-titulo="<?= htmlspecialchars(mb_strtolower($row['titulo']), ENT_QUOTES) ?>"
                                    data-cuerpo="<?= htmlspecialchars($row['cuerpo_html'], ENT_QUOTES) ?>"
                                    data-url="<?= htmlspecialchars($row['url_contenido'] ?? '') ?>"
                                    data-archivo="<?= pdfBlobToBase64($row['archivo']) ?>"
                                    data-img1="<?= blobToBase64($row['imagen1']) ?>"
                                    data-img2="<?= blobToBase64($row['imagen2']) ?>"
                                    data-img3="<?= blobToBase64($row['imagen3']) ?>">
                                    <i class="fas fa-arrow-right"></i>
                                    <div class="ratio"></div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        <div id="noResultados" style="display:none; text-align:center; margin-top:20px;">
            No se encontraron resultados
        </div>
    </section>

    <?php include 'modales/contenido.php'; ?>
    <script src="../peticiones(js)/contenido.js"></script>
    <script src="../peticiones(js)/buscadorContenido.js"></script>
    <script src="../peticiones(js)/return.js"></script>
    <?php include '../components/usuaria/footer.php'; ?>

</body>

</html>