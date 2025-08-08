<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Modelo/contenidoMdl.php';

// Crea una instancia del modelo.
$contenido = new contenidoMdl();
// Conecta a la base de datos a través del método del modelo.
$contenido->conectarBD();

// Usa un switch para manejar las diferentes opciones de la solicitud.
switch ($_REQUEST['opcion']) {
    case 1:
        // Lógica para AGREGAR nuevo contenido.
        $titulo = $_REQUEST['titulo'] ?? '';
        $descripcion = $_REQUEST['descripcion'] ?? '';
        $url = $_REQUEST['url'] ?? '';
        // Carga la imagen solo si se ha subido un archivo.
        $imagen = !empty($_FILES['imagen']['tmp_name']) ? file_get_contents($_FILES['imagen']['tmp_name']) : null;
        
        // Inicializa el modelo sin un ID, ya que es un nuevo registro.
        $contenido->inicializar($titulo, $descripcion, $url, $imagen);
        
        // Llama al método para agregar el contenido.
        $contenido->agregarContenido();
        break;

    case 2:
        // Lógica para EDITAR contenido existente.
        // Se necesita el ID para identificar el registro a actualizar.
        $id = $_REQUEST['id'] ?? null;
        $titulo = $_REQUEST['titulo'] ?? '';
        $descripcion = $_REQUEST['descripcion'] ?? '';
        $url = $_REQUEST['url'] ?? '';
        
        // Carga la nueva imagen si se ha subido una. Si no, $imagen será null.
        $imagen = !empty($_FILES['imagen']['tmp_name']) ? file_get_contents($_FILES['imagen']['tmp_name']) : null;
        
        if ($id) {
            // Inicializa el modelo con todos los datos, incluido el ID.
            $contenido->inicializar($titulo, $descripcion, $url, $imagen, $id);
            // Llama al método de actualización del modelo.
            $contenido->actualizarContenido();
        } else {
            // Maneja el caso de que no se proporcione un ID.
            echo "<script>alert('Error: No se proporcionó un ID para actualizar.'); window.location.href='../Vista/admin/contenido.php';</script>";
        }
        break;

    case 3:
        // Lógica para ELIMINAR contenido.
        // Se necesita el ID para identificar el registro a eliminar.
        $id = $_REQUEST['id'] ?? null;
        
        if ($id) {
            // Llama al método de eliminación del modelo con el ID.
            $contenido->eliminarContenido($id);
        } else {
            // Maneja el caso de que no se proporcione un ID.
            echo "<script>alert('Error: No se proporcionó un ID para eliminar.'); window.location.href='../Vista/admin/contenido.php';</script>";
        }
        break;

    default:
        // Mensaje de error si la opción no es válida.
        echo json_encode(['opcion' => 0, 'mensaje' => 'Opción no válida']);
        break;
}
?>

