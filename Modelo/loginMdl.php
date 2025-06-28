<?php
include '../obtenerLink/obtenerLink.php';

class loginMdln
{
    private $correo;
    private $contraseña;

    public function conectarBD()
    {
        $con = mysqli_connect("localhost", "root", "", "shakti");
        if (!$con) {
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
        header('Content-Type: application/json');

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $con = $this->conectarBD();
        $correo = mysqli_real_escape_string($con, $this->correo);

        $query = "SELECT u.id, u.nombre, u.apellidos, u.nickname, u.correo, u.fecha_nac, u.telefono, u.direccion, u.contraseña, r.id_rol, r.nombre_rol 
                  FROM usuarias u 
                  JOIN roles r ON u.id_rol = r.id_rol 
                  WHERE u.correo = '$correo' LIMIT 1";

        $result = mysqli_query($con, $query);

        if ($result) {
            $reg = mysqli_fetch_assoc($result);

            if ($reg && password_verify($this->contraseña, $reg["contraseña"])) {
                $_SESSION['id'] = $reg['id'];
                $_SESSION['id_usuaria'] = $reg['id']; // por compatibilidad con tu código
                $_SESSION['id_rol'] = $reg['id_rol'];
                $_SESSION['nombre_rol'] = $reg['nombre_rol'];
                $_SESSION['nombre'] = $reg['nombre'];
                $_SESSION['apellidos'] = $reg['apellidos'];
                $_SESSION['nickname'] = $reg['nickname'];
                $_SESSION['correo'] = $reg['correo'];
                $_SESSION['fecha_nacimiento'] = $reg['fecha_nac'];
                $_SESSION['telefono'] = $reg['telefono'];
                $_SESSION['direccion'] = $reg['direccion'];

                echo json_encode([
                    'success' => true,
                    'message' => 'Inicio de sesión exitoso',
                    'id_rol' => $reg['id_rol']
                ]);
                exit;
            }
        }

        echo json_encode([
            'success' => false,
            'message' => 'Correo y/o contraseña incorrectos.'
        ]);
        exit;
    }

    public function cerrarSesion()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        header("Location: " . getBaseUrl());
        exit;
    }
}
