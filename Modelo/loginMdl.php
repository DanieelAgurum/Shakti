<?php

class loginMdln
{
    private $correo;
    private $contraseña;

    public function conectarBD()
    {
        $con = mysqli_connect("localhost", "root", "", "SHAKTI");
        if (!$con) {
            // Respondemos JSON con error de conexión
            echo json_encode([
                'success' => false,
                'message' => 'Error en la conexión a la base de datos.'
            ]);
            exit;
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
        $query = "SELECT u.*, r.id_rol, r.nombre_rol 
                  FROM usuarias u 
                  JOIN roles r ON u.id_rol = r.id_rol 
                  WHERE u.correo = '$correo'";

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
                    $_SESSION['telefono'] = $reg['telefono'];
                    $_SESSION['direccion'] = $reg['direccion'];
                    // Respondemos con éxito y el rol para que JS decida a dónde ir
                    echo json_encode([
                        'success' => true,
                        'message' => 'Inicio de sesión exitoso.',
                        'id_rol' => $reg['id_rol']
                    ]);
                    exit;
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => "Correo y/o contraseña incorrectos."
                    ]);
                    exit;
                }
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => "Correo y/o contraseña incorrectos."
                ]);
                exit;
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => "Error en la consulta a la base de datos."
            ]);
            exit;
        }
    }

    public function cerrarSesion()
    {
        session_start();
        session_unset();
        session_destroy();
        header("Location: ../index.php");
        exit;
        // echo json_encode([
        //     'success' => true,
        //     'message' => 'Sesión cerrada correctamente.'
        // ]);
        exit;
    }
}