<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/obtenerLink/obtenerLink.php';

class organizacionesModelo {
    private $nombre;
    private $descripcion;
    private $numero;
    private $imagen;
    private $conexion;
    private $urlbase;
    


    public function __construct()
    {
        $this->base();
    }

    public function base()
    {
        $this->urlBase = function_exists('getBaseUrl') ? getBaseUrl() : '';
    }

    public function conectarBD()
    {
        try {
            $this->conexion = new PDO('mysql:host=localhost;dbname=shakti;charset=utf8', 'root', '');
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Opcional: para que fetch por defecto devuelva arrays asociativos
            $this->conexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Mejor devolver JSON con error para que la app lo pueda interpretar
            echo json_encode([
                'opcion' => 0,
                'mensaje' => 'Error de conexión: ' . $e->getMessage()
            ]);
            exit;
        }
    }

    public function inicializar($nombre, $descripcion, $numero){
        $this->nombre=trim($nombre);
        $this->descripcion=trim($descripcion);
        $this->numero=trim($numero);
        // $this->imagen=trim($imagen);
    }

    public function agregarOrganizacion(){
        
        if (empty($this->nombre) || empty($this->descripcion) || empty($this->numero)){
            return json_encode([
                'opcion' => 0,
                'mensaje' => 'Los campos no pueden estar vacíos.'
            ]);
        }

        $this->conectarBD();

        $consulta= "SELECT COUNT(*) FROM organizaciones WHERE nombre = :nombre";
        $verifica = $this->conexion->prepare($consulta);
        $verifica->bindParam(':nombre', $this->nombre);
        $verifica->execute();
        $existe = $verifica->fetchColumn();

        if ($existe > 0) {
            return json_encode([
                'opcion' => 0,
                'mensaje' => 'La organizacion ya existe.'
            ]);
        }

        // $imagen = $_FILES['imagen']['tmp_name'];
        // $imgContent = addslashes(file_get_contents($imagen));
        $registro =  "INSERT INTO organizaciones (nombre, descripcion, numero) VALUES (:nombre, :descripcion, :numero)";
        // if (isset($_POST["cargar"])) {
        //     $check = getimagesize($_FILES["imagen"]["tmp_name"]);
        //     if ($check !== false) {
        //         //Insertar la imagen dentro de la BD
        //         if ($registro) {
        //             echo "Se subio la imagen exitosamente.";
        //         } else {
        //             echo "Error al subir la imagen.";
        //         }
        //     } else {
        //         echo "Seleccione una archivo con imagen para subir.";
        //     }
        //}
        $agregar = $this->conexion->prepare($registro);
        $agregar->bindParam(':nombre', $this->nombre);
        $agregar->bindParam(':descripcion', $this->descripcion);
        $agregar->bindParam(':numero', $this->numero);
        
        if ($agregar->execute()) {
            return json_encode([
                'opcion' => 1,
                'mensaje' => 'ok'
            ]);
        } else {
            return json_encode([
                'opcion' => 0,
                'mensaje' => 'Inténtelo más tarde.'
            ]);
        }
    }

    public function modificarOrganizacion($id, $nombre, $descripcion, $numero){
        if (empty($nombre) || empty($descripcion) || empty($numero)){
            return json_encode([
                'opcion' => 0,
                'mensaje' => 'Los campos no pueden estar vacíos.'
            ]);
        }

        $this->conectarBD();
        $consul = "SELECT COUNT(*) FROM organizaciones WHERE nombre = :nombre AND id != :id ";
        $com = $this->conexion->prepare($consul);
        $com->bindParam(':nombre', $nombre);
        $com->bindParam(':id', $id, PDO::PARAM_INT);
        $com->execute();
        $resul = $com->fetchColumn();

        if ($resul > 0) {
            return json_encode([
                'opcion' => 0,
                'mensaje' => 'Ya existe otra pregunta con ese contenido.'
            ]);
        }

        $act = "UPDATE organizaciones SET nombre = :nombre, descripcion = :descripcion, numero = :numero WHERE id = :id";
        $update = $this->conexion->prepare($act);
        $update->bindParam(':nombre', $nombre);
        $update->bindParam(':descripcion', $descripcion);
        $update->bindParam(':numero', $numero);
        $update->bindParam(':id', $id, PDO::PARAM_INT);

        if ($update->execute()) {
            return json_encode([
                'opcion' => 1,
                'mensaje' => 'ok'
            ]);
        } else {
            return json_encode([
                'opcion' => 0,
                'mensaje' => 'No se pudo actualizar. Inténtalo más tarde.'
            ]);
        }
    }

    public function eliminarOrganizacion($id){
        $this->conectarBD();

        $eliminar = "DELETE FROM organizaciones WHERE id = :id";
        $delete = $this->conexion->prepare($eliminar);
        $delete->bindParam(':id', $id, PDO::PARAM_INT);

        if ($delete->execute()) {
            header("Location: " . $this->urlBase . "/Vista/admin/organizaciones.php?estado=eliminado");
            exit;
        } else {
            header("Location: " . $this->urlBase . "/Vista/admin/organizaciones.php?estado=error");
            exit;
        }

        
    }

    public function mostrarTodos(){
        $this->conectarBD();
        $mostrar = "SELECT * FROM organizaciones ORDER BY id DESC";
        $resul = $this->conexion->prepare($mostrar);
        $resul->execute();

        if ($resul->rowCount() === 0) {
            echo '<tr><td colspan="4" class="text-center"><h4>Sin registros</h4></td></tr>';
            return;
        }
        $num=1;
        while ($row = $resul->fetch(PDO::FETCH_ASSOC)) {
            $id = (int)$row['id'];
            $nombre = addslashes($row['nombre']);
            $descripcion = addslashes($row['descripcion']);
            $numero = addslashes($row['numero']);
            $imagen = !empty($row['imagen']) ? base64_encode($row['imagen']) : null;

            echo '<tr>';
            echo '<td>' . htmlspecialchars($num, ENT_QUOTES, 'UTF-8') . '</td>';
            echo '<td>' . htmlspecialchars($row['nombre'], ENT_QUOTES, 'UTF-8') . '</td>';
            echo '<td>' . htmlspecialchars($row['descripcion'], ENT_QUOTES, 'UTF-8') . '</td>';
            echo '<td>' . htmlspecialchars($row['numero'], ENT_QUOTES, 'UTF-8') . '</td>';
            if ($imagen) {
                echo '<td><img src="data:image/jpeg;base64,' . $imagen . '" alt="Imagen" style="width: 100px; height: auto;"></td>';
            } else {
                echo '<td>No disponible</td>';
            }
            echo '<td class="text-center">';
            echo '<button type="button" onclick="modificarDatos(' . $id . ', \'' . $nombre . '\', \'' . $descripcion . '\', \'' . $numero . '\')" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modificarModal">';
            echo '<i class="fa-solid fa-pen"></i> Editar';
            echo '</button> ';
            echo '<button type="button" class="btn btn-danger btn-sm btnEliminar" 
        data-id="' . $id . '" 
        data-nombre="' . htmlspecialchars($row['nombre'], ENT_QUOTES, 'UTF-8') . '" 
        data-bs-toggle="modal" 
        data-bs-target="#miModal">';
            echo '<i class="fa-solid fa-eraser"></i> Eliminar';
            echo '</button>';
            echo '</td>';
            echo '</tr>';

            $num++;
        }
    }
}