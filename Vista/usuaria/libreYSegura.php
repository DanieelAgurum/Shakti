<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/obtenerLink/obtenerLink.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Modelo/libre_seguraMdl.php';
$urlBase = getBaseUrl();

$busqueda = $_GET['buscador'] ?? '';

// Usar la clase Legales para obtener resultados
$legales = new Legales();
$resultados = $legales->buscar($busqueda);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Libre y Segura</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?= $urlBase ?>peticiones(js)/mandarReporte.js"></script>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/usuaria/navbar.php'; ?>
    <style>
        /* ====== MODAL PERSONALIZADO DE DOCUMENTOS ====== */
        .custom-modal-content {
            background: rgba(20, 20, 20, 0.95);
            border-radius: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(8px);
        }

        .custom-modal-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .custom-modal-title {
            font-weight: 600;
            letter-spacing: 0.5px;
            color: #fff;
        }

        .custom-modal-body {
            height: 80vh;
            padding: 0;
        }

        .custom-iframe-documento {
            width: 100%;
            height: 100%;
            border-radius: 0.8rem;
        }

        .custom-modal-documento .modal-backdrop.show {
            opacity: 0.7;
            background-color: #000;
        }
    </style>
</head>

<body class="bg-white text-black">

    <div class="container mt-5 mb-5">
        <h2 class="text-center w-100 mt-3">Libre y Segura</h2>
        <div class="search-wrapper w-100">
            <div class="search-box search-foro">
                <form method="GET">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" name="buscador" class="form-control search-input" placeholder="Buscar ..." value="<?= htmlspecialchars($_GET['buscador'] ?? '') ?>">
                </form>
            </div>
        </div>

        <div class="text-center text-muted fs-5 mb-4">
            <i class="bi bi-shield-check text-primary me-2"></i>
            Porque conocer la ley también es una forma de cuidarte. Aquí puedes consultar tus derechos, recursos legales y orientaciones para vivir con dignidad, libertad y seguridad.
        </div>

        <div class="row g-4">
            <?php if (!empty($resultados)) : ?>
                <?php foreach ($resultados as $row) : ?>
                    <div class="col-12 col-md-4">
                        <div class="card card-custom animate__animated animate__fadeInLeft animate__slow animacion text-white">
                            <?php if ($row['portada']) : ?>
                                <img src="data:image/jpeg;base64,<?= base64_encode($row['portada']) ?>" class="card-img" alt="<?= htmlspecialchars($row['titulo']) ?>" />
                            <?php else : ?>
                                <img src="https://via.placeholder.com/400x200?text=Sin+portada" class="card-img" alt="Sin portada" />
                            <?php endif; ?>

                            <div class="card-img-overlay">
                                <h5 class="card-title title-content"><?= htmlspecialchars($row['titulo']) ?></h5>
                                <p class="card-text text-content"><?= htmlspecialchars($row['descripcion']) ?></p>
                                <div class="card-date">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                        <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-2 .89-2 2v12a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6c0-1.11-.89-2-2-2zM5 20V9h14v11H5z" />
                                    </svg>
                                    Última actualización: <?= date("d/m/Y H:i", strtotime($row['fecha'])) ?>
                                </div>
                                <button class="btn btn-outline-light mt-3 read-more-btn ver-documento"
                                    data-id="<?= $row['id_legal'] ?>"
                                    data-title="<?= htmlspecialchars($row['titulo']) ?>">
                                    Leer más
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="text-center text-muted">No se encontraron resultados.</div>
            <?php endif; ?>
        </div>
    </div>

    <?php include '../modales/documentoLegal.php' ?>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const botones = document.querySelectorAll(".ver-documento");
            const modalEl = document.getElementById("modalDocumento");
            const iframe = document.getElementById("iframeDocumento");
            const modal = new bootstrap.Modal(modalEl);
            const modalTitle = document.getElementById("modalDocumentoLabel");

            botones.forEach(boton => {
                boton.addEventListener("click", function() {
                    const id = this.getAttribute("data-id");
                    const title = this.getAttribute("data-title");

                    iframe.src = "<?= $urlBase ?>Modelo/ver_contenido.php?id_legal=" + id;

                    modalTitle.textContent = title;

                    modal.show();
                });
            });

            modalEl.addEventListener("hidden.bs.modal", () => {
                iframe.src = "";
            });
        });
    </script>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/shakti/components/usuaria/footer.php'; ?>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="<?= $urlBase ?>peticiones(js)/mandarMetricas.js.php?vista=<?= urlencode(basename($_SERVER['PHP_SELF'])) ?>"></script>
</body>

</html>