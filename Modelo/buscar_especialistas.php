<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$servername = "localhost";
$username = "root";
$password = "";
$database = "shakti";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("❌ Error en la conexión: " . $conn->connect_error);
}

$especialista = isset($_GET['especialista']) ? $conn->real_escape_string($_GET['especialista']) : "";

if (!empty($especialista)) {
    $sql = "SELECT * FROM usuarias 
            WHERE estatus = 1 AND id_rol = 2 AND (
                nombre LIKE '%$especialista%' OR
                apellidos LIKE '%$especialista%' OR
                correo LIKE '%$especialista%' OR
                descripcion LIKE '%$especialista%'
            )";
} else {
    $sql = "SELECT * FROM usuarias WHERE estatus = 1 AND id_rol = 2";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $foto = $row['foto'];
        $src = $foto ? 'data:image/jpeg;base64,' . base64_encode($foto) : 'https://cdn1.iconfinder.com/data/icons/avatar-3/512/Secretary-512.png';

        $nombreCompleto = ucwords(htmlspecialchars($row['nombre'] . ' ' . $row['apellidos']));

        $descripcion = !empty($row['descripcion'])
            ? htmlspecialchars($row['descripcion'])
            : 'Especialista en bienestar y atención a víctimas.';

        echo '
        <div class="col-md-4 mb-4">
            <div class="card testimonial-card animate__animated animate__backInUp">
                <div class="card-up aqua-gradient"></div>
                <div class="avatar mx-auto white">
                    <img src="' . $src . '"  class="rounded-circle" width="150" height="150" alt="Especialista">
                </div>
                <div class="card-body text-center">
                    <h4 class="card-title font-weight-bold">' . $nombreCompleto . '</h4>
                    <p style="max-height: 70px; overflow-y: auto;" class="descripcion-scroll">
                        ' . ucwords(htmlspecialchars($row['descripcion'])) . '
                    </p>
                    <hr>
                    <button type="button" class="btn btn-outline-secondary mt-2" data-bs-toggle="modal" data-bs-target="#modalEspecialista' . $row['id'] . '">
                        <i class="bi bi-eye-fill"></i> Ver perfil
                    </button>
                    <button type="button" class="btn btn-outline-primary mt-2" data-bs-toggle="modal" data-bs-target="#modalEspecialista' . $row['id'] . '">
                        <i class="bi bi-envelope-paper-heart"></i> Mensaje
                    </button>
                </div>
            </div>
        </div>';
        include '../Vista/modales/especialistas.php';
    }
} else {
    echo '<div class="w-100 m-auto"><h3 class="text-center">No se encontraron resultados</h3></div>';
}

$conn->close();
