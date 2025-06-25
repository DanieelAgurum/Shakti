<?php

class loginMdln
{
    private $correo;
    private $contraseña;

    public function conectarBD()
    {
        $con = mysqli_connect("localhost", "root", "", "SHAKTI");
        if (!$con) {
            die("Problemas con la conexión a la base de datos: " . mysqli_connect_error());
        }
        return $con;
    }

    public function inicializar($correo, $contraseña)
    {
        $this->correo = $correo;
        $this->contraseña = $contraseña;
    }

    public function iniciarSesion()
    {
        $con = $this->conectarBD();

        $correo = mysqli_real_escape_string($con, $this->correo);
        $contraseña = mysqli_real_escape_string($con, $this->contraseña);
        $contraHash = password_hash($contraseña, PASSWORD_DEFAULT);

        $query = "SELECT * FROM usuarias 
              JOIN roles ON usuarias.id_rol = roles.id_rol 
              WHERE correo = '$correo' and contraseña = '$contraHash'";

        // echo $query;
        $result = mysqli_query($con, $query);

        if ($result) {
            $reg = mysqli_fetch_array($result);
            if ($reg) {

                session_start();
                $_SESSION['id_usuaria'] = $reg['id_usuaria'];
                $_SESSION['rol'] = $reg['id_rol'];
                $_SESSION['nombre'] = $reg['nombre'];
                $_SESSION['apellido'] = $reg['apellidos'];
                $_SESSION['nickname'] = $reg['nickname'];
                $_SESSION['correo'] = $reg['correo'];
                $_SESSION['fecha_nac'] = $reg['fecha_nac'];

                // Redirección según rol (ajusta si usas letras o números)
                if ($reg['id_rol'] == "1") {
                    header("Location: ../vista/admin/panel.php");
                    exit;
                } elseif ($reg['id_rol'] == "2") {
                    header("Location: ../index.php?page=profile");
                    exit;
                } else {
                    $message = "Rol no válido";
                    header("Location: ../index.php?pagina=login&message=" . $message);
                    exit;
                }
            } else {
                $message = "No se encontró ningún usuario con este correo";
                header("Location: ../index.php?pagina=login&message=" . $message);
                exit;
            }
        } else {
            die("Problemas en la consulta: " . mysqli_error($con));
        }

        mysqli_close($con);
    }


    public function validar()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Verificar si el usuario tiene el rol de cliente
        if (isset($_SESSION["rol"]) && $_SESSION["rol"] === "C") {
            // Si no tiene el rol adecuado, redirigir al formulario de inicio de sesión
            header("Location: index.php?page=profile&" . $_SESSION['nombre']);
            exit;
        } elseif (isset($_SESSION["rol"]) && $_SESSION["rol"] == "A") {
            header("Location: vista/admin/panel.php");
            exit;
        }
    }

    public function cerrarSesion()
    {
        // Inicia la sesión si no está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Destruye la sesión
        session_destroy();

        // Redirige al formulario de inicio de sesión
        header("Location: ../index.php?pagina=login");
        exit;
    }
}
