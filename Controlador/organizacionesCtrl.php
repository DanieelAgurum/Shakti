<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/Modelo/organizacionesModelo.php';

$preg = new organizacionesModelo();
$preg->conectarBD();

if (isset($_REQUEST['opcion'])) {
    switch ($_REQUEST['opcion']) {
        case 1:
            $preg->inicializar($_REQUEST['nombre'], $_REQUEST['descripcion'], $_REQUEST['numero']);
            $resultado = $preg->agregarOrganizacion();
            echo $resultado;
            break;

        case 2:
            $resultado = $preg->modificarOrganizacion($_REQUEST['id'], $_REQUEST['nombre'], $_REQUEST['descripcion'], $_REQUEST['numero']);
            echo $resultado;
            break;
        case 3:
            $preg->eliminarOrganizacion($_REQUEST['id']);
            break;
        default:
            echo json_encode(['opcion' => 0, 'mensaje' => 'Opción no válida.']);
            break;
    }
}

// require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/Modelo/organizacionesModelo.php';
// // Controlador para manejar las organizaciones
// // Este archivo maneja la lógica de negocio relacionada con las organizaciones
// include('../Modelo/organizacionesModelo.php');
// $organizacion = new organizacionesModelo();
// // $organizacion->conectarBD(); // Removed because conectarBD() is undefined or unnecessary

// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     if (isset($_POST['action']) && $_POST['action'] == 'create') {
//         $nombre = $_POST['nombre'];
//         $descripcion = $_POST['descripcion'];
//         $numero = $_POST['numero'];
//         $imagen = file_get_contents($_FILES['imagen']['tmp_name']);
//         if ($organizacion->create($nombre, $descripcion, $numero, $imagen)) {
//             $statusMsg = 'Organización creada correctamente.';
//         } else {
//             $statusMsg = 'Error al crear la organización.';
//         }
//     } elseif (isset($_POST['action']) && $_POST['action'] == 'update') {
//         $id = $_POST['id'];
//         $nombre = $_POST['nombre'];
//         $descripcion = $_POST['descripcion'];
//         $numero = $_POST['numero'];
//         $imagen = !empty($_FILES['imagen']['tmp_name']) ? file_get_contents($_FILES['imagen']['tmp_name']) : null;
//         if ($organizacion->update($id, $nombre, $descripcion, $numero, $imagen)) {
//             $statusMsg = 'Organización actualizada correctamente.';
//         } else {
//             $statusMsg = 'Error al actualizar la organización.';
//         }
//     }
// } elseif (isset($_GET['delete'])) {
//     $id = $_GET['delete'];
//     if ($organizacion->delete($id)) {
//         $statusMsg = 'Organización eliminada correctamente.';
//         header("Location: ?status=eliminada");
//         exit;
//     } else {
//         $statusMsg = 'Error al eliminar la organización.';
//         header("Location: ?status=error_eliminar");
//         exit;
//     }
// }


// // Obtener todas las organizaciones para mostrar en la vista
// $organizaciones = $organizacion->getAll();
// if (isset($_GET['status'])) {
//     $status = $_GET['status'];
//     switch ($status) {
//         case 'creada':
//             $imagen = file_get_contents($_FILES['imagen']['tmp_name']);
//             $organizacion->create($_POST['nombre'], $_POST['descripcion'], $_POST['numero'], $imagen);
//             echo "Organización creada correctamente.";
//             break;
//         case 'actualizada':
//             $imagen = !empty($_FILES['imagen']['tmp_name']) ? file_get_contents($_FILES['imagen']['tmp_name']) : null;
//             if ($organizacion->update($_REQUEST['id'], $_REQUEST['nombre'], $_REQUEST['descripcion'], $_REQUEST['numero'], $imagen)) {
//                 echo "Organización actualizada correctamente.";
//             } else {
//                 echo "Error al actualizar la organización.";
//             }
//             break;
//         case 'eliminada':
//             $organizacion->delete($_REQUEST['id']);
//             break;
//         default:
//             echo json_encode(['opcion' => 0, 'mensaje' => 'Opción no válida.']);
//             break;
//     }
// }
