<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/obtenerLink/obtenerLink.php';

class organizacionesModelo
{
    private $nombre;
    private $descripcion;
    private $numero;
    private $imagen;
    private $conexion;
    private $urlBase;

    public function __construct()
    {
        $this->urlBase = function_exists('getBaseUrl') ? getBaseUrl() : '';
    }

    public function conectarBD()
    {
        try {
            $this->conexion = new PDO('mysql:host=localhost;dbname=shakti;charset=utf8', 'root', '');
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo json_encode(['opcion' => 0, 'mensaje' => 'Error de conexión: ' . $e->getMessage()]);
            exit;
        }
    }

    public function inicializar($nombre, $descripcion, $numero, $imagen = null)
    {
        $this->nombre = trim($nombre);
        $this->descripcion = trim($descripcion);
        $this->numero = trim($numero);

        if ($imagen && isset($imagen['error']) && $imagen['error'] === 0) {
            $check = getimagesize($imagen['tmp_name']);
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

            if ($check !== false && in_array($check['mime'], $allowedMimeTypes)) {
                $this->imagen = file_get_contents($imagen['tmp_name']);
            } else {
                // Devuelve un error si el formato no es válido
                return json_encode(['opcion' => 0, 'mensaje' => 'Formato de imagen no válido.']);
            }
        }
    }

    public function agregarOrganizacion()
    {
        if (empty($this->nombre) || empty($this->descripcion) || empty($this->numero)) {
            return json_encode(['opcion' => 0, 'mensaje' => 'Los campos no pueden estar vacíos.']);
        }

        if ($this->imagen === null) {
            return json_encode(['opcion' => 0, 'mensaje' => 'La imagen es requerida.']);
        }

        $this->conectarBD();

        $consulta = "SELECT COUNT(*) FROM organizaciones WHERE nombre = :nombre";
        $verifica = $this->conexion->prepare($consulta);
        $verifica->bindParam(':nombre', $this->nombre);
        $verifica->execute();

        if ($verifica->fetchColumn() > 0) {
            return json_encode(['opcion' => 0, 'mensaje' => 'La organización ya existe.']);
        }

        $registro = "INSERT INTO organizaciones (nombre, descripcion, numero, imagen)
                     VALUES (:nombre, :descripcion, :numero, :imagen)";
        $agregar = $this->conexion->prepare($registro);
        $agregar->bindParam(':nombre', $this->nombre);
        $agregar->bindParam(':descripcion', $this->descripcion);
        $agregar->bindParam(':numero', $this->numero);
        $agregar->bindParam(':imagen', $this->imagen, PDO::PARAM_LOB);

        // CORRECCIÓN: Se eliminó la redirección con header() y se retorna una respuesta JSON.
        // Esto es necesario para que la llamada AJAX funcione correctamente.
        if ($agregar->execute()) {
            return json_encode(['opcion' => 1, 'mensaje' => 'Organización agregada con éxito']);
        } else {
            return json_encode(['opcion' => 0, 'mensaje' => 'Error al guardar en la base de datos.']);
        }
    }

    // La función modificarOrganizacion ya estaba bien para AJAX, no necesita cambios.
    public function modificarOrganizacion($id, $nombre, $descripcion, $numero)
    {
        if (empty($nombre) || empty($descripcion) || empty($numero)) {
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
                'mensaje' => 'Ya existe otra organización con ese nombre.'
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

    // La función eliminarOrganizacion usa una redirección simple, lo cual está bien para su implementación actual.
    public function eliminarOrganizacion($id)
    {
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
    public function mostrarTodos($offset, $limit)
    {
        $this->conectarBD();

        $sql = "SELECT id, nombre, descripcion, numero, imagen, domicilio 
            FROM organizaciones 
            ORDER BY id DESC 
            LIMIT :offset, :limit";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            echo json_encode(['sinDatos' => true]);
            return;
        }

        $instituciones = [];
        $claveSecreta = 'NexoH_Instituciones_2025';

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $token = hash('sha256', $row['id'] . $claveSecreta . random_bytes(8));

            $imagen = !empty($row['imagen'])
                ? 'data:image/jpeg;base64,' . base64_encode($row['imagen'])
                : 'data:image/png;base64,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';

            $instituciones[] = [
                'token' => $token,
                'nombre' => htmlspecialchars($row['nombre']) ?? "",
                'descripcion' => htmlspecialchars($row['descripcion'] ?? ""),
                'telefono' => htmlspecialchars($row['numero']) ?? "",
                'domicilio' => htmlspecialchars($row['domicilio'] ?? ""),
                'imagen' => $imagen,
                'link' => '' ?? '#'
            ];
        }
        echo json_encode(['sinDatos' => false, 'datos' => $instituciones]);
    }
}
