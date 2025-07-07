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

        $response = [
            'success' => false,
            'message' => 'Correo y/o contraseña incorrectos.'
        ];

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $con = $this->conectarBD();
        $correo = mysqli_real_escape_string($con, $this->correo);

        // Consulta con campos explícitos para evitar confusiones
        $query = "
            SELECT 
                u.id, u.nombre, u.apellidos, u.fecha_nac, u.contraseña, u.nickname, u.correo, u.id_rol,
                u.documentos, u.descripcion, u.direccion, u.telefono, u.foto, u.estatus,
                r.id_rol AS rol_id, r.nombre_rol
            FROM usuarias u
            JOIN roles r ON u.id_rol = r.id_rol
            WHERE u.correo = '$correo'
        ";

        $result = mysqli_query($con, $query);

        if ($result) {
            $reg = mysqli_fetch_assoc($result);

            if ($reg && password_verify($this->contraseña, $reg["contraseña"])) {
                // Guardar datos en sesión
                $_SESSION['id'] = $reg['id'];

                $sql = "SELECT foto FROM usuarias WHERE id = {$_SESSION['id']}";
                $result = mysqli_query($con, $sql);
                if ($row = mysqli_fetch_assoc($result)) {
                    $_SESSION['foto'] = $row['foto'];
                }
                $_SESSION['id'] = $reg['id'];
                $_SESSION['id_usuaria'] = $reg['id'];
                $_SESSION['id_rol'] = $reg['id_rol'];
                $_SESSION['nombre_rol'] = $reg['nombre_rol'];
                $_SESSION['nombre'] = $reg['nombre'];
                $_SESSION['apellidos'] = $reg['apellidos'];
                $_SESSION['nickname'] = $reg['nickname'];
                $_SESSION['correo'] = $reg['correo'];
                $_SESSION['fecha_nacimiento'] = $reg['fecha_nac'];
                $_SESSION['telefono'] = $reg['telefono'];
                 $_SESSION['descripcion'] = $reg['descripcion'];
                $_SESSION['direccion'] = $reg['direccion'];
                $_SESSION['documentos'] = $reg['documentos'];
                $_SESSION['estatus'] = $reg['estatus'];

                echo json_encode([
                    'success' => true,
                    'message' => 'éxito',
                    'id_rol' => $reg['id_rol']
                ]);
                exit;
            }
        }

        session_write_close();
        echo json_encode($response);
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