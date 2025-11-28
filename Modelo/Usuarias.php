<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Shakti/obtenerLink/obtenerLink.php';
$urlBase = getBaseUrl();
class Usuarias
{
    private $urlBase;
    private $nombre;
    private $apellidos;
    private $nickname;
    private $correo;
    private $contraseña;
    private $conContraseña;
    private $fecha_nac;
    private $rol;
    private $foto;
    private $nombreN;
    private $apellidosN;
    private $nicknameN;
    private $correoN;
    private $contraseñaN;
    private $fecha_nacN;
    private $telefono;
    private $direccion;
    private $descripcion;


    public function conectarBD()
    {
        $con = mysqli_connect("localhost", "root", "", "shakti") or die("Problemas con la conexión a la base de datos");
        return $con;
    }

    public function __construct()
    {
        $this->urlBase = getBaseUrl();
    }

    public function inicializar($nom, $ape, $nick, $cor, $cont, $contC, $fec, $rol)
    {
        $this->nombre = $nom;
        $this->apellidos = $ape;
        $this->nickname = $nick;
        $this->correo = $cor;
        $this->contraseña = $cont;
        $this->conContraseña = $contC;
        $this->fecha_nac = $fec;
        $this->rol = $rol;
    }
    public function agregarUsuaria()
    {
        $con = $this->conectarBD();

        // Validaciones de campos vacíos
        if (
            empty(trim($this->nombre)) ||
            empty(trim($this->apellidos)) ||
            empty(trim($this->nickname)) ||
            empty(trim($this->correo)) ||
            empty(trim($this->contraseña)) ||
            empty(trim($this->conContraseña)) ||
            empty(trim($this->fecha_nac))
        ) {
            header("Location: ../Vista/registro?status=error&message=" . urlencode("Todos los campos obligatorios deben estar llenos"));
            exit;
        }

        // Validación de correo
        if (!filter_var($this->correo, FILTER_VALIDATE_EMAIL)) {
            header("Location: ../Vista/registro?status=error&message=" . urlencode("Correo electrónico inválido"));
            exit;
        }

        // Validación de contraseñas
        if ($this->contraseña !== $this->conContraseña) {
            header("Location: ../Vista/registro?status=error&message=" . urlencode("Las contraseñas no coinciden"));
            exit;
        }

        // Validación de duplicados
        $correo = mysqli_real_escape_string($con, $this->correo);

        // Buscar cualquier usuaria con el mismo correo
        $stmt = $con->prepare("SELECT id FROM usuarias WHERE correo = ? LIMIT 1");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            header("Location: ../Vista/registro?status=error&message=" . urlencode("Este correo ya está en uso. Inicia sesión o utiliza otro correo."));
            exit;
        }

        $nickname = mysqli_real_escape_string($con, $this->nickname);
        $nickDuplicado = mysqli_query($con, "SELECT 1 FROM usuarias WHERE nickname = '$nickname'");
        if (mysqli_fetch_array($nickDuplicado)) {
            header("Location: ../Vista/registro?status=error&message=" . urlencode("Este nombre de usuario ya está en uso"));
            exit;
        }

        $hash = password_hash($this->contraseña, PASSWORD_DEFAULT);
        $nombre = mysqli_real_escape_string($con, $this->nombre);
        $apellidos = mysqli_real_escape_string($con, $this->apellidos);
        $fecha = mysqli_real_escape_string($con, $this->fecha_nac);
        $rol = (int)$this->rol;

        $insertar = mysqli_query($con, "
        INSERT INTO usuarias (nombre, apellidos, nickname, correo, contraseña, fecha_nac, id_rol, verificado)
        VALUES ('$nombre', '$apellidos', '$nickname', '$correo', '$hash', '$fecha', $rol, 0)
    ") or die("Error al insertar usuaria: " . mysqli_error($con));

        $id_nueva = mysqli_insert_id($con);

        require_once '../Modelo/confirmarCorreo.php';
        $correoConfirmacion = new ConfirmarCorreo();
        $correoConfirmacion->inicializar($this->correo, $this->nombre, $this->urlBase, $id_nueva);
        $enviado = $correoConfirmacion->enviarCorreoVerificacion();

        if (!$enviado) {
            error_log("No se pudo enviar el correo de verificación a: " . $this->correo);
        }
        return $id_nueva;
    }

    public function actualizarDatos($nomN, $apeN, $nickN, $corN, $contN, $fec, $tel, $dir, $desc, $idUsuaria)
    {
        $con = $this->conectarBD();

        // Verificar si el nickname ya está en uso
        $nickNuevo = mysqli_real_escape_string($con, $nickN);
        $nickQuery = "SELECT id FROM usuarias WHERE nickname = '$nickNuevo' AND id != $idUsuaria LIMIT 1";
        $nickResult = mysqli_query($con, $nickQuery);

        if ($nickResult && mysqli_num_rows($nickResult) > 0) {
            session_start();
            $msg = "El nombre de usuaria ya está en uso, por favor elige otro.";
            if (isset($_SESSION['id_rol'])) {
                if ($_SESSION['id_rol'] == 1) {
                    header("Location: ../Vista/usuaria/perfil?status=error&message=" . urlencode($msg));
                } else if ($_SESSION['id_rol'] == 2) {
                    header("Location: ../Vista/especialista/perfil?status=error&message=" . urlencode($msg));
                } else {
                    header("Location: ../Vista/login?status=error&message=" . urlencode("Rol no reconocido"));
                }
            } else {
                header("Location: ../Vista/login?status=error&message=" . urlencode("Sesión no iniciada"));
            }
            exit;
        }

        // Obtener datos actuales
        $result = mysqli_query($con, "SELECT * FROM usuarias WHERE id = $idUsuaria");
        if (!$result || mysqli_num_rows($result) == 0) {
            die("Error: no se encontró la usuaria.");
        }
        $actual = mysqli_fetch_assoc($result);

        session_start();
        $campos = [];

        if ($nomN !== $actual['nombre']) $campos[] = "nombre = '" . mysqli_real_escape_string($con, $nomN) . "'";
        if ($apeN !== $actual['apellidos']) $campos[] = "apellidos = '" . mysqli_real_escape_string($con, $apeN) . "'";
        if ($nickN !== $actual['nickname']) $campos[] = "nickname = '" . mysqli_real_escape_string($con, $nickN) . "'";
        if ($corN !== $actual['correo']) $campos[] = "correo = '" . mysqli_real_escape_string($con, $corN) . "'";
        if ($fec !== $actual['fecha_nac']) $campos[] = "fecha_nac = '" . mysqli_real_escape_string($con, $fec) . "'";
        if ($tel !== $actual['telefono']) $campos[] = "telefono = '" . mysqli_real_escape_string($con, $tel) . "'";
        if ($dir !== $actual['direccion']) $campos[] = "direccion = '" . mysqli_real_escape_string($con, $dir) . "'";
        if ($desc !== $actual['descripcion']) $campos[] = "descripcion = '" . mysqli_real_escape_string($con, $desc) . "'";

        if (!empty($contN)) {
            $hash = password_hash($contN, PASSWORD_DEFAULT);
            $campos[] = "contraseña = '" . mysqli_real_escape_string($con, $hash) . "'";
        }

        if (empty($campos)) {
            if (isset($_SESSION['id_rol'])) {
                if ($_SESSION['id_rol'] == 1) {
                    header("Location: ../Vista/usuaria/perfil");
                } else if ($_SESSION['id_rol'] == 2) {
                    header("Location: ../Vista/especialista/perfil");
                } else {
                    header("Location: ../Vista/login?status=error&message=Rol+no+reconocido");
                }
            } else {
                header("Location: ../Vista/login?status=error&message=Sesión+no+iniciada");
            }
            exit;
        }

        $setClause = implode(', ', $campos);
        $query = "UPDATE usuarias SET $setClause WHERE id = $idUsuaria";
        $consulta = mysqli_query($con, $query) or die("Error al actualizar: " . mysqli_error($con));

        // Actualizar variables de sesión
        if ($consulta) {
            $result = mysqli_query($con, "SELECT * FROM usuarias WHERE id = $idUsuaria");
            $actual = mysqli_fetch_assoc($result);

            $_SESSION['nombre'] = $actual['nombre'];
            $_SESSION['apellidos'] = $actual['apellidos'];
            $_SESSION['nickname'] = $actual['nickname'];
            $_SESSION['correo'] = $actual['correo'];
            $_SESSION['fecha_nacimiento'] = $actual['fecha_nac'];
            $_SESSION['telefono'] = $actual['telefono'];
            $_SESSION['direccion'] = $actual['direccion'];
            $_SESSION['descripcion'] = $actual['descripcion'];
        }

        // Redirigir según rol
        if (isset($_SESSION['id_rol'])) {
            if ($_SESSION['id_rol'] == 1) {
                header("Location: ../Vista/usuaria/perfil?status=success&message=" . urlencode("Datos actualizados correctamente"));
            } else if ($_SESSION['id_rol'] == 2) {
                header("Location: ../Vista/especialista/perfil?status=success&message=" . urlencode("Datos actualizados correctamente"));
            } else {
                header("Location: ../Vista/login?status=error&message=" . urlencode("Rol no reconocido"));
            }
        } else {
            header("Location: ../Vista/login?status=error&message=" . urlencode("Sesión no iniciada"));
        }
        exit;
    }

    public function cambiarFotoPerfil($idUsuaria, $foto)
    {
        $con = $this->conectarBD();

        if (!$foto || $foto['error'] !== 0) {
            return ['status' => 'error', 'message' => 'No se recibió ninguna imagen o hubo un error al subirla'];
        }

        $fotoBin = file_get_contents($foto['tmp_name']);
        if ($fotoBin === false) {
            return ['status' => 'error', 'message' => 'No se pudo leer el archivo'];
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->buffer($fotoBin);
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/jpg', 'image/pjpeg'];

        if (!in_array($mime, $allowedMimeTypes)) {
            return ['status' => 'error', 'message' => 'Tipo de imagen no permitido'];
        }

        $fotoBinEscaped = mysqli_real_escape_string($con, $fotoBin);
        $query = "UPDATE usuarias SET foto = '$fotoBinEscaped' WHERE id = $idUsuaria";

        if (!mysqli_query($con, $query)) {
            return ['status' => 'error', 'message' => 'Error al actualizar la foto en la base de datos: ' . mysqli_error($con)];
        }

        session_start();
        $_SESSION['foto'] = $fotoBin;
        return ['status' => 'success', 'message' => 'Foto de perfil actualizada correctamente'];
    }

    public function eliminarFotoPerfil($idUsuaria)
    {
        $con = $this->conectarBD();

        $query = "UPDATE usuarias SET foto = NULL WHERE id = ?";
        $stmt = mysqli_prepare($con, $query);

        if (!$stmt) {
            mysqli_close($con);
            return ['status' => 'error', 'message' => 'Error en la preparación de la consulta: ' . mysqli_error($con)];
        }

        mysqli_stmt_bind_param($stmt, "i", $idUsuaria);

        $resultado = [];
        if (mysqli_stmt_execute($stmt)) {
            session_start();
            $_SESSION['foto'] = null;
            $resultado = ['status' => 'success', 'message' => 'Foto de perfil eliminada correctamente'];
        } else {
            $resultado = ['status' => 'error', 'message' => 'Error al eliminar la foto: ' . mysqli_error($con)];
        }

        mysqli_stmt_close($stmt);
        mysqli_close($con);

        return $resultado;
    }

    public function eliminarUsuaria($id)
    {
        $con = $this->conectarBD();
        $sql = "DELETE FROM usuarias WHERE id = ?";
        $sqlDocumentos  = "DELETE FROM documentos WHERE id_usuaria = ?";

        $stmt = mysqli_prepare($con, $sql);
        $stmtDoc = mysqli_prepare($con, $sqlDocumentos);

        if ($stmt && $stmtDoc) {
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_bind_param($stmtDoc, 'i', $id);
            if (mysqli_stmt_execute($stmt) && mysqli_stmt_execute($stmtDoc)) {
                header("Location: " . $this->urlBase . "/Vista/admin/usuarias?eliminado=" . urlencode("Se eliminó la usuaria correctamente"));
                exit;
            } else {
                header("Location: " . $this->urlBase . "/Vista/admin/usuarias?eliminado=" . urlencode("No se pudo eliminar o ya fue eliminada"));
                exit;
            }
        } else {

            die("Error en prepare: " . mysqli_error($con));
        }
    }
}
