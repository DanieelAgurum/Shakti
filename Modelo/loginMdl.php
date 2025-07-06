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

    /* ============================================================
   MODELO – Iniciar sesión
   ============================================================ */
public function iniciarSesion(): void
{
    header('Content-Type: application/json; charset=utf-8');
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $con  = $this->conectarBD();
    $stmt = $con->prepare(
        'SELECT u.id, u.contraseña, u.nombre, u.apellidos, u.fecha_nac,
                u.nickname, u.correo, u.id_rol, u.documentos, u.direccion,
                u.telefono, u.foto, u.estatus,
                r.nombre_rol
           FROM usuarias u
           JOIN roles r ON u.id_rol = r.id_rol
          WHERE u.correo = ?'
    );
    $stmt->bind_param('s', $this->correo);
    $stmt->execute();
    $reg = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    $con->close();

    if ($reg && password_verify($this->contraseña, $reg['contraseña'])) {
        /* Guardar datos de sesión */
        $_SESSION += [
            'id'            => $reg['id'],
            'id_rol'        => $reg['id_rol'],
            'nombre_rol'    => $reg['nombre_rol'],
            'nombre'        => $reg['nombre'],
            'apellidos'     => $reg['apellidos'],
            'nickname'      => $reg['nickname'],
            'correo'        => $reg['correo'],
            'fecha_nac'     => $reg['fecha_nac'],
            'telefono'      => $reg['telefono'],
            'direccion'     => $reg['direccion'],
            'documentos'    => $reg['documentos'],
            'estatus'       => $reg['estatus'],
            'foto'          => $reg['foto'],
        ];

        echo json_encode([
            'success' => true,
            'message' => 'Éxito',
            'id_rol'  => $reg['id_rol'],
        ]);
        exit;
    }

    /* Fallo de autenticación */
    echo json_encode([
        'success' => false,
        'message' => 'Correo y/o contraseña incorrectos.',
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
