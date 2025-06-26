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

        $query = "SELECT u.*, r.id_rol FROM usuarias u JOIN roles r ON u.id_rol = r.id_rol WHERE u.correo = '$correo'";

        $result = mysqli_query($con, $query);

        if ($result) {
            $reg = mysqli_fetch_array($result);
            if ($reg) {
                $hash = $reg["contraseña"];

                if (password_verify($this->contraseña, $hash)) {
                    session_start();
                    $_SESSION['id'] = $reg['id_usuaria'];
                    $_SESSION['id_rol'] = $reg['id_rol'];
                    $_SESSION['nombre_rol'] = $reg['nombre_rol'];
                    $_SESSION['nombre'] = $reg['nombre'];
                    $_SESSION['apellidos'] = $reg['apellidos'];
                    $_SESSION['nickname'] = $reg['nickname'];
                    $_SESSION['correo'] = $reg['correo'];
                    $_SESSION['fecha_nacimiento'] = $reg['fecha_nacimiento'];

                    // Redirección por rol
                    switch ($reg['id_rol']) {
                        case 1:
                            header("Location: ../index.php?message=exito");
                            break;
                        case 2:
                            header("Location: ../vista/tutor/panel.php");
                            break;
                        case 3:
                            header("Location: ../vista/admin/panel.php");
                            break;
                        default:
                            $message = "Rol no reconocido.";
                            header("Location: ../index.php?pagina=login&message=" . $message);
                            break;
                    }
                    exit;
                } else {
                    $message = "Correo y/o contraseña incorrecta";
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
    }


    public function validar()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Verificar si el usuario tiene el rol adecuado
        if (isset($_SESSION["rol"]) && $_SESSION["rol"] === "C") {
            header("Location: index.php?page=profile&" . $_SESSION['nombre']);
            exit;
        } elseif (isset($_SESSION["rol"]) && $_SESSION["rol"] == "A") {
            header("Location: vista/admin/panel.php");
            exit;
        }
    }

    public function cerrarSesion()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_destroy();

        header("Location: ../index.php?pagina=login");
        exit;
    }
}
