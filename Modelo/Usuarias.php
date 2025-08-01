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
            header("Location: ../Vista/registro.php?status=error&message=" . urlencode("Todos los campos obligatorios deben estar llenos"));
            exit;
        }

        // Validación de correo
        if (!filter_var($this->correo, FILTER_VALIDATE_EMAIL)) {
            header("Location: ../Vista/registro.php?status=error&message=" . urlencode("Correo electrónico inválido"));
            exit;
        }

        // Validación de contraseñas
        if ($this->contraseña !== $this->conContraseña) {
            header("Location: ../Vista/registro.php?status=error&message=" . urlencode("Las contraseñas no coinciden"));
            exit;
        }

        // Validación de duplicados
        $correo = mysqli_real_escape_string($con, $this->correo);
        $correoDuplicado = mysqli_query($con, "SELECT 1 FROM usuarias WHERE correo = '$correo'");
        if (mysqli_fetch_array($correoDuplicado)) {
            header("Location: ../Vista/registro.php?status=error&message=" . urlencode("Este correo ya pertenece a una cuenta"));
            exit;
        }

        $nickname = mysqli_real_escape_string($con, $this->nickname);
        $nickDuplicado = mysqli_query($con, "SELECT 1 FROM usuarias WHERE nickname = '$nickname'");
        if (mysqli_fetch_array($nickDuplicado)) {
            header("Location: ../Vista/registro.php?status=error&message=" . urlencode("Este nombre de usuario ya está en uso"));
            exit;
        }

        $hash = password_hash($this->contraseña, PASSWORD_DEFAULT);
        $nombre = mysqli_real_escape_string($con, $this->nombre);
        $apellidos = mysqli_real_escape_string($con, $this->apellidos);
        $fecha = mysqli_real_escape_string($con, $this->fecha_nac);
        $rol = (int)$this->rol;

        $insertar = mysqli_query($con, "
            INSERT INTO usuarias (nombre, apellidos, nickname, correo, contraseña, fecha_nac, id_rol)
            VALUES ('$nombre', '$apellidos', '$nickname', '$correo', '$hash', '$fecha', $rol)
        ") or die("Error al insertar usuaria: " . mysqli_error($con));

        $id_nueva = mysqli_insert_id($con);

        $query = "
            SELECT u.*, r.nombre_rol
            FROM usuarias u
            JOIN roles r ON u.id_rol = r.id_rol
            WHERE u.id = $id_nueva
        ";
        $resultado = mysqli_query($con, $query) or die("Error al obtener datos de la usuaria: " . mysqli_error($con));
        $usuaria = mysqli_fetch_assoc($resultado);

        // Iniciar sesión
        session_start();
        $_SESSION['id'] = $usuaria['id'];
        $_SESSION['id_usuaria'] = $usuaria['id'];
        $_SESSION['id_rol'] = $usuaria['id_rol'];
        $_SESSION['nombre_rol'] = $usuaria['nombre_rol'];
        $_SESSION['nombre'] = $usuaria['nombre'];
        $_SESSION['apellidos'] = $usuaria['apellidos'];
        $_SESSION['nickname'] = $usuaria['nickname'];
        $_SESSION['correo'] = $usuaria['correo'];
        $_SESSION['fecha_nacimiento'] = $usuaria['fecha_nac'];
        $_SESSION['telefono'] = $usuaria['telefono'];
        $_SESSION['direccion'] = $usuaria['direccion'];
        $_SESSION['descripcion'] = $usuaria['descripcion'];

        // Redirigir según rol
        switch ($usuaria['id_rol']) {
            case 1:
                header("Location: ../Vista/usuaria/perfil.php?status=success&message=" . urlencode("Cuenta creada exitosamente"));
                break;
            case 2:
                header("Location: ../Vista/especialista/perfil.php?status=success&message=" . urlencode("Cuenta creada exitosamente, completa tu perfil"));
                break;
            default:
                header("Location: ../Vista/registro.php?status=error&message=" . urlencode("Rol no válido"));
                break;
        }
        exit;
    }

    public function actualizarDatos($foto, $nomN, $apeN, $nickN, $corN, $contN, $fec, $tel, $dir, $desc, $idUsuaria)
    {
        $con = $this->conectarBD();

        $nickNuevo = mysqli_real_escape_string($con, $nickN);
        $nickQuery = "SELECT id FROM usuarias WHERE nickname = '$nickNuevo' AND id != $idUsuaria LIMIT 1";
        $nickResult = mysqli_query($con, $nickQuery);

        if ($nickResult && mysqli_num_rows($nickResult) > 0) {

            session_start();
            if (isset($_SESSION['id_rol'])) {
                $msg = "El nombre de usuaria ya está en uso, por favor elige otro.";
                if ($_SESSION['id_rol'] == 1) {
                    header("Location: ../Vista/usuaria/perfil.php?status=error&message=" . urlencode($msg));
                } else if ($_SESSION['id_rol'] == 2) {
                    header("Location: ../Vista/especialista/perfil.php?status=error&message=" . urlencode($msg));
                } else {
                    header("Location: ../Vista/login.php?status=error&message=" . urlencode("Rol no reconocido"));
                }
            } else {
                header("Location: ../Vista/login.php?status=error&message=" . urlencode("Sesión no iniciada"));
            }
            exit;
        }

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

        if ($foto && isset($foto['error']) && $foto['error'] === 0) {
            $check = getimagesize($foto['tmp_name']);
            if ($check !== false) {
                $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/jpg', 'image/pjpeg'];
                if (in_array($check['mime'], $allowedMimeTypes)) {
                    $fotoBin = file_get_contents($foto['tmp_name']);
                    $fotoBinEscaped = mysqli_real_escape_string($con, $fotoBin);
                    $campos[] = "foto = '$fotoBinEscaped'";
                    $_SESSION['foto'] = $fotoBin;
                } else {
                    // Imagen inválida: redirigir según rol
                    $msg = "Ingrese una imagen válida";
                    if (session_status() == PHP_SESSION_NONE) {
                        session_start();
                    }
                    if (isset($_SESSION['id_rol'])) {
                        if ($_SESSION['id_rol'] == 1) {
                            header("Location: ../Vista/usuaria/perfil.php?status=error&message=" . urlencode($msg));
                        } else if ($_SESSION['id_rol'] == 2) {
                            header("Location: ../Vista/especialista/perfil.php?status=error&message=" . urlencode($msg));
                        } else {
                            header("Location: ../Vista/login.php?status=error&message=" . urlencode("Rol no reconocido"));
                        }
                    } else {
                        header("Location: ../Vista/login.php?status=error&message=" . urlencode("Sesión no iniciada"));
                    }
                    exit;
                }
            } else {
                // No es imagen válida
                header("Location: ../Vista/usuaria/perfil.php?status=error&message=" . urlencode("El archivo no es una imagen válida"));
                exit;
            }
        }

        if (empty($campos)) {
            if ($_SESSION['id_rol'] == 1) {
                header("Location: ../Vista/usuaria/perfil.php");
            } else if ($_SESSION['id_rol'] == 2) {
                header("Location: ../Vista/especialista/perfil.php");
            } else {
                header("Location: ../Vista/login.php?status=error&message=Rol+no+reconocido");
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

        if (isset($_SESSION['id_rol'])) {
            if ($_SESSION['id_rol'] == 1) {
                header("Location: ../Vista/usuaria/perfil.php?status=success&message=" . urlencode("Datos actualizados correctamente"));
            } else if ($_SESSION['id_rol'] == 2) {
                header("Location: ../Vista/especialista/perfil.php?status=success&message=" . urlencode("Datos actualizados correctamente"));
            } else {
                header("Location: ../Vista/login.php?status=error&message=" . urlencode("Rol no reconocido"));
            }
        } else {
            header("Location: ../Vista/login.php?status=error&message=" . urlencode("Sesión no iniciada"));
        }
        exit;
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
                header("Location: " . $this->urlBase . "/Vista/admin/usuarias.php?eliminado=" . urlencode("Se eliminó la usuaria correctamente"));
                exit;
            } else {
                header("Location: " . $this->urlBase . "/Vista/admin/usuarias.php?eliminado=" . urlencode("No se pudo eliminar o ya fue eliminada"));
                exit;
            }
            mysqli_stmt_close($stmt);
        } else {

            die("Error en prepare: " . mysqli_error($con));
        }

        mysqli_close($con);
    }
}
