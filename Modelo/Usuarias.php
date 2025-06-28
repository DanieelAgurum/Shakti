<?php
class Usuarias
{
    private $nombre;
    private $apellidos;
    private $nickname;
    private $correo;
    private $contraseña;
    private $conContraseña;
    private $fecha_nac;
    private $rol;

    public function conectarBD()
    {
        $con = mysqli_connect("localhost", "root", "", "shakti") or die("Problemas con la conexión a la base de datos");
        return $con;
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

        if (!filter_var($this->correo, FILTER_VALIDATE_EMAIL)) {
            header("Location: ../Vista/registro.php?status=error&message=" . urlencode("Correo electrónico inválido"));
            exit;
        }

        if ($this->contraseña !== $this->conContraseña) {
            header("Location: ../Vista/registro.php?status=error&message=" . urlencode("Las contraseñas no coinciden"));
            exit;
        }

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
        $rol = (int) $this->rol;

        // Insertar usuaria en la base de datos
        mysqli_query($con, "INSERT INTO usuarias (nombre, apellidos, nickname, correo, contraseña, fecha_nac, id_rol) 
    VALUES ('$nombre', '$apellidos', '$nickname', '$correo', '$hash', '$fecha', '$rol')")
            or die("Error al insertar usuaria: " . mysqli_error($con));

        // Iniciar sesión automáticamente
        session_start();
        $_SESSION['id'] = mysqli_insert_id($con);
        $_SESSION['id_rol'] = $this->nombre;
        $_SESSION['nombre_rol'] = $this->nombre;
        $_SESSION['nombre'] = $this->nombre;
        $_SESSION['apellidos'] = $this->nombre;
        $_SESSION['nickname'] = $this->nombre;
        $_SESSION['correo'] = $this->nombre;
        $_SESSION['fecha_nacimiento'] = $this->nombre;
        $_SESSION['telefono'] = $this->nombre;
        $_SESSION['direccion'] = $this->nombre;

        // Redirigir según el rol
        switch ($rol) {
            case 1:
                header("Location: ../Vista/usuarias/perfil.php?status=success&message=" . urlencode("Cuenta creada exitosamente"));
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
}
