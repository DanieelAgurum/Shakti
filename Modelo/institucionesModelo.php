<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/obtenerLink/obtenerLink.php';

class organizacionesModelo
{
    private $nombre;
    private $descripcion;
    private $numero;
    private $domicilio;
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
    public function inicializar($nombre, $descripcion, $numero, $domicilio, $imagen = null)
    {
        $this->nombre = trim($nombre);
        $this->descripcion = trim($descripcion);
        $this->numero = trim($numero);
        $this->domicilio = trim($domicilio);

        // Guardamos directamente el array de $_FILES para procesarlo luego en agregarOrganizacion
        if ($imagen && isset($imagen['error']) && $imagen['error'] === 0) {
            $check = getimagesize($imagen['tmp_name']);
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

            if ($check !== false && in_array($check['mime'], $allowedMimeTypes)) {
                $this->imagen = $imagen; 
            } else {
                return json_encode(['opcion' => 0, 'mensaje' => 'Formato de imagen no válido.']);
            }
        } else {
            $this->imagen = null;
        }
    }
    public function agregarOrganizacion()
    {
        if (empty($this->nombre) || empty($this->descripcion) || empty($this->numero)) {
            return json_encode(['opcion' => 0, 'mensaje' => 'Los campos no pueden estar vacíos.']);
        }

        $this->conectarBD();

        $check = "SELECT nombre, numero FROM organizaciones WHERE nombre = :nombre OR numero = :numero";
        $stmtCheck = $this->conexion->prepare($check);
        $stmtCheck->bindParam(':nombre', $this->nombre);
        $stmtCheck->bindParam(':numero', $this->numero);
        $stmtCheck->execute();
        $conflictos = $stmtCheck->fetchAll();

        if (!empty($conflictos)) {
            $mensaje = [];
            foreach ($conflictos as $c) {
                if ($c['nombre'] === $this->nombre) $mensaje[] = "ya existe esta institución con este nombre";
                if ($c['numero'] === $this->numero) $mensaje[] = "ya existe esta institución con este número";
            }
            return json_encode(['opcion' => 0, 'mensaje' => implode(" y ", $mensaje)]);
        }

        // Procesar imagen si se envía
        if ($this->imagen && isset($this->imagen['tmp_name']) && is_uploaded_file($this->imagen['tmp_name'])) {
            $imgInfo = getimagesize($this->imagen['tmp_name']);
            if ($imgInfo) {
                $ext = strtolower(pathinfo($this->imagen['name'], PATHINFO_EXTENSION));
                $maxWidth = 1500;
                $maxHeight = 1500;
                $width = $imgInfo[0];
                $height = $imgInfo[1];
                $ratio = min($maxWidth / $width, $maxHeight / $height, 1);
                $newWidth = (int)($width * $ratio);
                $newHeight = (int)($height * $ratio);

                switch ($imgInfo['mime']) {
                    case 'image/jpeg':
                    case 'image/jpg':
                        $original = imagecreatefromjpeg($this->imagen['tmp_name']);
                        break;
                    case 'image/png':
                        $original = imagecreatefrompng($this->imagen['tmp_name']);
                        break;
                    case 'image/gif':
                        $original = imagecreatefromgif($this->imagen['tmp_name']);
                        break;
                    case 'image/webp':
                        $original = imagecreatefromwebp($this->imagen['tmp_name']);
                        break;
                    default:
                        $original = null;
                }

                if ($original) {
                    $thumb = imagecreatetruecolor($newWidth, $newHeight);
                    if (in_array($ext, ['png', 'webp'])) {
                        imagealphablending($thumb, false);
                        imagesavealpha($thumb, true);
                        $trans = imagecolorallocatealpha($thumb, 0, 0, 0, 127);
                        imagefill($thumb, 0, 0, $trans);
                    }
                    imagecopyresampled($thumb, $original, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

                    ob_start();
                    switch ($ext) {
                        case 'png':
                            imagepng($thumb);
                            break;
                        case 'gif':
                            imagegif($thumb);
                            break;
                        case 'webp':
                            imagewebp($thumb, null, 80);
                            break;
                        default:
                            imagejpeg($thumb, null, 80);
                    }
                    $this->imagen = ob_get_clean();
                    imagedestroy($original);
                    imagedestroy($thumb);
                } else {
                    $this->imagen = null;
                }
            } else {
                $this->imagen = null;
            }
        } else {
            $this->imagen = null;
        }

        // Insertar registro
        $registro = "INSERT INTO organizaciones (nombre, descripcion, numero, domicilio, imagen)
                 VALUES (:nombre, :descripcion, :numero, :domicilio, :imagen)";
        $agregar = $this->conexion->prepare($registro);
        $agregar->bindParam(':nombre', $this->nombre);
        $agregar->bindParam(':descripcion', $this->descripcion);
        $agregar->bindParam(':numero', $this->numero);
        $agregar->bindParam(':domicilio', $this->domicilio);
        $agregar->bindParam(':imagen', $this->imagen, PDO::PARAM_LOB);

        if ($agregar->execute()) {
            return json_encode(['opcion' => 1, 'mensaje' => 'Institución agregada con éxito.']);
        } else {
            return json_encode(['opcion' => 0, 'mensaje' => 'Error al guardar en la base de datos.']);
        }
    }
    public function modificarOrganizacion($idHash, $nombre, $descripcion, $numero, $domicilio, $imagen = null)
    {
        $this->nombre = trim($nombre);
        $this->descripcion = trim($descripcion);
        $this->numero = trim($numero);
        $this->domicilio = trim($domicilio);

        $this->conectarBD();

        // Obtener ID real desde hash
        $sqlId = "SELECT id FROM organizaciones WHERE SHA2(id, 256) = :idHash";
        $stmtId = $this->conexion->prepare($sqlId);
        $stmtId->bindParam(':idHash', $idHash);
        $stmtId->execute();
        $row = $stmtId->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return json_encode(['opcion' => 0, 'mensaje' => 'Institución no encontrada.']);
        }

        $id = $row['id'];

        // Verificar duplicados
        $check = $this->conexion->prepare("
        SELECT id FROM organizaciones 
        WHERE (nombre = :nombre OR domicilio = :domicilio) AND id != :id
    ");
        $check->bindParam(':nombre', $this->nombre);
        $check->bindParam(':domicilio', $this->domicilio);
        $check->bindParam(':id', $id, PDO::PARAM_INT);
        $check->execute();

        if ($check->rowCount() > 0) {
            return json_encode([
                'opcion' => 0,
                'mensaje' => 'Ya existe otra organización con el mismo nombre o domicilio.'
            ]);
        }

        // Procesar imagen si se envía una nueva
        if ($imagen && isset($imagen['tmp_name']) && is_uploaded_file($imagen['tmp_name']) && $imagen['error'] === UPLOAD_ERR_OK) {
            $imgInfo = getimagesize($imagen['tmp_name']);
            if (!$imgInfo) {
                return json_encode(['opcion' => 0, 'mensaje' => 'El archivo no es una imagen válida.']);
            }

            $ext = strtolower(pathinfo($imagen['name'], PATHINFO_EXTENSION));
            $extPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (!in_array($ext, $extPermitidas)) {
                $ext = 'jpg';
            }

            $width = $imgInfo[0];
            $height = $imgInfo[1];
            $maxWidth = 1500;
            $maxHeight = 1500;
            $ratio = min($maxWidth / $width, $maxHeight / $height, 1);
            $newWidth = (int)($width * $ratio);
            $newHeight = (int)($height * $ratio);

            switch ($imgInfo['mime']) {
                case 'image/jpeg':
                case 'image/jpg':
                    $original = imagecreatefromjpeg($imagen['tmp_name']);
                    break;
                case 'image/png':
                    $original = imagecreatefrompng($imagen['tmp_name']);
                    break;
                case 'image/gif':
                    $original = imagecreatefromgif($imagen['tmp_name']);
                    break;
                case 'image/webp':
                    $original = imagecreatefromwebp($imagen['tmp_name']);
                    break;
                default:
                    return json_encode(['opcion' => 0, 'mensaje' => 'Formato de imagen no soportado.']);
            }

            if ($original) {
                $thumb = imagecreatetruecolor($newWidth, $newHeight);

                // Preservar transparencia
                if (in_array($ext, ['png', 'webp'])) {
                    imagealphablending($thumb, false);
                    imagesavealpha($thumb, true);
                    $transparente = imagecolorallocatealpha($thumb, 0, 0, 0, 127);
                    imagefill($thumb, 0, 0, $transparente);
                }

                imagecopyresampled($thumb, $original, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

                // Comprimir según tamaño
                $pesoOriginal = $imagen['size'];
                $calidadAlta = 80;
                $calidadBaja = 40;
                $calidadFinal = ($pesoOriginal > (20 * 1024 * 1024)) ? $calidadBaja : $calidadAlta;

                // Guardar temporalmente en buffer
                ob_start();
                switch ($ext) {
                    case 'png':
                        $nivel = ($pesoOriginal > (20 * 1024 * 1024)) ? 9 : 4;
                        imagepng($thumb, null, $nivel);
                        break;
                    case 'gif':
                        imagegif($thumb);
                        break;
                    case 'webp':
                        imagewebp($thumb, null, $calidadFinal);
                        break;
                    default:
                        imagejpeg($thumb, null, $calidadFinal);
                        break;
                }
                $this->imagen = ob_get_clean();

                imagedestroy($original);
                imagedestroy($thumb);
            } else {
                $this->imagen = null;
            }
        } else {
            $this->imagen = null;
        }

        // 🔹 UPDATE dinámico
        $sql = "UPDATE organizaciones 
            SET nombre = :nombre,
                descripcion = :descripcion,
                numero = :numero,
                domicilio = :domicilio";

        if ($this->imagen !== null) {
            $sql .= ", imagen = :imagen";
        }

        $sql .= " WHERE id = :id";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':descripcion', $this->descripcion);
        $stmt->bindParam(':numero', $this->numero);
        $stmt->bindParam(':domicilio', $this->domicilio);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($this->imagen !== null) {
            $stmt->bindParam(':imagen', $this->imagen, PDO::PARAM_LOB);
        }

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return json_encode(['opcion' => "modifico", 'mensaje' => 'Institución modificada con éxito.']);
        } else {
            return json_encode(['opcion' => "sinCambios", 'mensaje' => 'No se detectaron cambios en la institución.']);
        }
    }
    public function eliminarOrganizacion($id)
    {
        $this->conectarBD();

        $eliminar = "DELETE FROM organizaciones WHERE SHA2(id, 256) = :idHash";
        $delete = $this->conexion->prepare($eliminar);
        $delete->bindParam(':idHash', $id, PDO::PARAM_STR);

        if ($delete->execute()) {
            return json_encode(['opcion' => "eliminado", 'mensaje' => 'Se eliminó la organización.']);
        } else {
            return json_encode(['opcion' => "error", 'mensaje' => 'Inténtelo más tarde.']);
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

        while ($row = $stmt->fetch()) {
            $token = hash('sha256', $row['id'] . $claveSecreta . random_bytes(8));

            $imagen = !empty($row['imagen'])
                ? 'data:image/jpeg;base64,' . base64_encode($row['imagen'])
                : 'data:image/png;base64,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';

            $instituciones[] = [
                'token' => $token,
                'registro' => hash('sha256', $row['id']),
                'nombre' => htmlspecialchars($row['nombre']),
                'descripcion' => htmlspecialchars($row['descripcion']),
                'telefono' => htmlspecialchars($row['numero']) ?? "Sin Numero de Télefono",
                'domicilio' => htmlspecialchars($row['domicilio']) ?? "Sin Domicilio",
                'imagen' => $imagen,
                'link' => '#'
            ];
        }

        echo json_encode(['sinDatos' => false, 'datos' => $instituciones]);
    }
}
