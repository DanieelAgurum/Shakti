<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/obtenerLink/obtenerLink.php';
$urlBase = getBaseUrl();

require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/modelo/PublicacionModelo.php';
$publicacionModelo = new PublicacionModelo();
$publicaciones = $publicacionModelo->obtenerPublicacionesAdmin(); // Solo publicaciones admin
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Libre y Segura</title>
    <link rel="stylesheet" href="<?= $urlBase ?>css/estilos.css" />
    <link rel="stylesheet" href="<?= $urlBase ?>css/estiloscarrucel.css" />
    <link rel="stylesheet" href="<?= $urlBase ?>css/publicaciones.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/usuaria/navbar.php'; ?>
</head>

<body class="bg-white text-black">
    <h2 class="text-center w-100 mt-3">Libre y Segura</h2>

    <div class="search-wrapper w-100">
        <div class="search-box">
            <form method="GET">
                <i class="bi bi-search search-icon"></i>
                <input type="text" name="buscador" class="form-control search-input" placeholder="Buscar ...">
                <input type="hidden" name="opcion" value="admin">
            </form>
        </div>
    </div>

    <section class="container mb-5 d-flex flex-wrap justify-content-center gap-4">
        <?php if (!empty($publicaciones)) : ?>
            <?php foreach ($publicaciones as $pub) : ?>
                <article class="instagram-post">
                    <header class="post-header">
                        <div class="profile-info">
                            <img src="<?= htmlspecialchars($pub['foto_perfil'] ?? 'https://cdn1.iconfinder.com/data/icons/avatar-3/512/Secretary-512.png') ?>" alt="Foto" class="profile-pic">
                            <div class="profile-details">
                                <span class="username"><?= htmlspecialchars($pub['nickname'] ?? 'Administrador') ?></span>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-link p-0 shadow-none" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical text-black fs-5"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-start">
                                <li><a class="dropdown-item" href="#">Eliminar</a></li>
                                <li><a class="dropdown-item" href="#">Compartir</a></li>
                            </ul>
                        </div>
                    </header>

                    <div class="post-content">
                        <p class="ps-3 pt-2"><?= nl2br(htmlspecialchars($pub['contenido'])) ?></p>
                    </div>

                    <div class="post-actions">
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-danger btn-like" data-id="<?= $pub['id_publicacion'] ?>">
                                <i class="bi bi-suit-heart-fill"></i> Me gusta
                                <span class="badge bg-danger likes-count"><?= $pub['likes'] ?? 0 ?></span>
                            </button>
                            <button class="btn btn-sm btn-outline-secondary btn-toggle-comments" data-id="<?= $pub['id_publicacion'] ?>">
                                <i class="bi bi-chat"></i> Comentarios
                            </button>
                        </div>
                    </div>

                    <div class="comments-section mt-3 d-none" id="comments-<?= $pub['id_publicacion'] ?>">
                        <div class="existing-comments mb-3">
                            <p class="text-muted">Aún no hay comentarios.</p>
                        </div>
                        <form class="comment-form" data-id="<?= $pub['id_publicacion'] ?>">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" placeholder="Escribe un comentario..." required>
                                <button class="btn btn-sm btn-primary" type="submit">Enviar</button>
                            </div>
                        </form>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else : ?>
            <p class="text-center mt-4">No hay publicaciones del administrador por ahora.</p>
        <?php endif; ?>
    </section>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/usuaria/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="<?= $urlBase ?>peticiones(js)/mandarMetricas.js.php?vista=<?= urlencode(basename($_SERVER['PHP_SELF'])) ?>"></script>
</body>

</html>
