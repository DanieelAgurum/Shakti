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
    private $link;

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
    public function inicializar($nombre, $descripcion, $numero, $domicilio, $imagen = null, $link = null)
    {
        $this->nombre = trim($nombre);
        $this->descripcion = trim($descripcion);
        $this->numero = trim($numero);
        $this->domicilio = trim($domicilio);
        $this->link = trim($link);

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
        $registro = "INSERT INTO organizaciones 
(nombre, descripcion, numero, domicilio, link, imagen)
VALUES (:nombre, :descripcion, :numero, :domicilio, :link, :imagen)";

        $agregar = $this->conexion->prepare($registro);
        $agregar->bindParam(':nombre', $this->nombre);
        $agregar->bindParam(':descripcion', $this->descripcion);
        $agregar->bindParam(':numero', $this->numero);
        $agregar->bindParam(':domicilio', $this->domicilio);
        $agregar->bindParam(':link', $this->link);
        $agregar->bindParam(':imagen', $this->imagen, PDO::PARAM_LOB);

        if ($agregar->execute()) {
            return json_encode(['opcion' => 1, 'mensaje' => 'Institución agregada con éxito.']);
        } else {
            return json_encode(['opcion' => 0, 'mensaje' => 'Error al guardar en la base de datos.']);
        }
    }
    public function modificarOrganizacion($idHash, $nombre, $descripcion, $numero, $domicilio, $imagen = null, $link = null)
    {
        $this->nombre = trim($nombre);
        $this->descripcion = trim($descripcion);
        $this->numero = trim($numero);
        $this->domicilio = trim($domicilio);
        $this->link = trim($link);

        $this->conectarBD();

        // ===============================
        // 🔍 1. Obtener ID real por HASH
        // ===============================
        $sqlId = "SELECT id FROM organizaciones WHERE SHA2(id, 256) = :idHash";
        $stmtId = $this->conexion->prepare($sqlId);
        $stmtId->bindParam(':idHash', $idHash);
        $stmtId->execute();
        $row = $stmtId->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return json_encode(['opcion' => 0, 'mensaje' => 'Institución no encontrada.']);
        }

        $id = $row['id'];

        // ======================================
        // 🔎 2. Validar duplicados (nombre/domicilio)
        // ======================================
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

        // ======================================
        // 📸 3. Procesamiento de imagen (opcional)
        // ======================================
        $this->imagen = null;

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

            // Crear imagen según formato
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

                // Compresión
                $pesoOriginal = $imagen['size'];
                $calidadFinal = ($pesoOriginal > (20 * 1024 * 1024)) ? 40 : 80;

                ob_start();
                if ($ext === 'png') {
                    $nivel = ($pesoOriginal > (20 * 1024 * 1024)) ? 9 : 4;
                    imagepng($thumb, null, $nivel);
                } elseif ($ext === 'gif') {
                    imagegif($thumb);
                } elseif ($ext === 'webp') {
                    imagewebp($thumb, null, $calidadFinal);
                } else {
                    imagejpeg($thumb, null, $calidadFinal);
                }

                $this->imagen = ob_get_clean();
                imagedestroy($original);
                imagedestroy($thumb);
            }
        }

        // ===============================
        // 📝 4. Construcción del UPDATE
        // ===============================

        if ($this->imagen !== null) {
            $sql = "UPDATE organizaciones SET 
            nombre = :nombre,
            descripcion = :descripcion,
            numero = :numero,
            domicilio = :domicilio,
            link = :link,
            imagen = :imagen
            WHERE id = :id";
        } else {
            $sql = "UPDATE organizaciones SET 
            nombre = :nombre,
            descripcion = :descripcion,
            numero = :numero,
            domicilio = :domicilio,
            link = :link
            WHERE id = :id";
        }

        $stmt = $this->conexion->prepare($sql);

        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':descripcion', $this->descripcion);
        $stmt->bindParam(':numero', $this->numero);
        $stmt->bindParam(':domicilio', $this->domicilio);
        $stmt->bindParam(':link', $this->link);
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
        try {
            $this->conectarBD();
            $offset = (int)$offset;
            $limit  = (int)$limit;

            $sql = "SELECT *
                FROM organizaciones
                ORDER BY id DESC
                LIMIT :offset, :limit";

            $stmt = $this->conexion->prepare($sql);

            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);

            $stmt->execute();

            $instituciones = [];
            $claveSecreta = 'NexoH_Instituciones_2025';

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                $token = hash('sha256', $row['id'] . $claveSecreta . random_bytes(8));

                // Imagen BLOB o NULL
                if (!empty($row['imagen'])) {
                    $imgInfo = @getimagesizefromstring($row['imagen']);
                    $mime = $imgInfo['mime'] ?? 'image/jpeg';

                    $imagen = 'data:' . $mime . ';base64,' . base64_encode($row['imagen']);
                } else {
                    $imagen = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAOEAAADhCAMAAAAJbSJIAAAAb1BMVEX///9mZmYAAAClpaVdXV1jY2NgYGDr6+toaGjJyclbW1upqalXV1f6+vqAgIC8vLzT09ORkZFxcXHk5OTv7+/Y2NiamppQUFB6enqgoKC0tLR0dHRHR0caGho2NjYqKipBQUEMDAyHh4eNjY3Dw8OCd7QeAAA...';
                }

                $nombre = $row['nombre'] ? htmlspecialchars($row['nombre']) : "Sin nombre";
                $descripcion = $row['descripcion'] ? htmlspecialchars($row['descripcion']) : "Sin descripción disponible";
                $telefono = $row['numero'] ? htmlspecialchars($row['numero']) : "Sin número de teléfono";
                $link = $row['link'] ? htmlspecialchars($row['link']) : "";
                $domicilio = $row['domicilio'] ? htmlspecialchars($row['domicilio']) : "Sin domicilio";
                $fecha = $row['fecha'] ? htmlspecialchars($row['fecha']) : "Sin fecha registrada";

                $instituciones[] = [
                    'token' => $token,
                    'registro' => hash('sha256', $row['id']),
                    'nombre' => $nombre,
                    'descripcion' => $descripcion,
                    'telefono' => $telefono,
                    'link' => $link,
                    'domicilio' => $domicilio,
                    'fecha' => $fecha,
                    'imagen' => $imagen
                ];
            }

            if (empty($instituciones)) {
                echo json_encode(['sinDatos' => true]);
                return;
            }

            echo json_encode([
                'sinDatos' => false,
                'datos' => $instituciones
            ]);
        } catch (Exception $e) {

            echo json_encode([
                'sinDatos' => true,
                'error' => true,
                'mensaje' => $e->getMessage()
            ]);
        }
    }
}