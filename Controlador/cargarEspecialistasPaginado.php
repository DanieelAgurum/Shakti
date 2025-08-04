<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Modelo/especialistaModelo.php';

header('Content-Type: application/json; charset=utf-8');

$pagina = isset($_POST['pagina']) ? (int)$_POST['pagina'] : 1;
$porPagina = 9;
$offset = ($pagina - 1) * $porPagina;

$modelo = new EspecialistaModelo();

$especialistas = $modelo->obtenerEspecialistasPaginados($offset, $porPagina);
$total = $modelo->contarTotalEspecialistas();
$totalPaginas = ceil($total / $porPagina);

$cards = '';
foreach ($especialistas as $row) {
    $foto = $row['foto'];
    $src = $foto ? 'data:image/jpeg;base64,' . base64_encode($foto) : 'https://cdn1.iconfinder.com/data/icons/avatar-3/512/Secretary-512.png';
    $id = $row['id'];

    $cards .= '
    <div class="col-md-4 mb-4">
        <div class="card testimonial-card animate__animated animate__backInUp animacion">
            <div class="card-up aqua-gradient"></div>
            <div class="avatar mx-auto white">
                <img src="' . $src . '" class="rounded-circle" width="150" height="150" alt="Especialista">
            </div>
            <div class="card-body text-center">
                <h4 class="card-title font-weight-bold">' . ucwords(htmlspecialchars($row['nombre'] . ' ' . $row['apellidos'])) . '</h4>
                <p style="max-height: 70px; overflow-y: auto;" class="descripcion-scroll">' . ucwords(htmlspecialchars($row['descripcion'])) . '</p>
                <hr>
                <button type="button" class="btn btn-outline-secondary mt-2" data-bs-toggle="modal" data-bs-target="#modalEspecialista' . $id . '">
                    <i class="bi bi-eye-fill"></i> Ver perfil
                </button>
                <a href="/Vista/chat.php" class="btn btn-outline-primary mt-2">
                <i class="bi bi-envelope-paper-heart"></i> Mensaje</a>
            </div>
        </div>
    </div>';

    ob_start();
    include $_SERVER['DOCUMENT_ROOT'] . '/Vista/modales/especialistas.php';
    $cards .= ob_get_clean();
}

$paginacion = '';
for ($i = 1; $i <= $totalPaginas; $i++) {
    $active = ($i == $pagina) ? 'active' : '';
    $paginacion .= '<li class="page-item ' . $active . '"><a class="page-link pag-btn" href="#" data-page="' . $i . '">' . $i . '</a></li>';
}

echo json_encode([
    'cards' => $cards,
    'paginacion' => $paginacion
]);
exit;
