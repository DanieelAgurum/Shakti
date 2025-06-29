<?php

include("../modelo/Usuarias.php");

$u = new Usuarias();
$u->conectarBD();

switch ($_REQUEST['opcion']) {
    case 1:
        $u->inicializar(
            $_REQUEST['nombre'],
            $_REQUEST['apellidos'],
            $_REQUEST['nickname'],
            $_REQUEST['correo'],
            $_REQUEST['contraseña'],
            $_REQUEST['conContraseña'],
            $_REQUEST['fecha_nac'],
            $_REQUEST['rol']
        );
        $u->agregarUsuaria();
        break;
    case 2:
        session_start();
        if (!isset($_SESSION['id'])) {
            header("Location: ../Vista/login.php");
            exit;
        }
        $u->actualizarDatos(
            $_FILES['foto'] ?? null,
            $_REQUEST['nombreN'],
            $_REQUEST['apellidosN'],
            $_REQUEST['nicknameN'],
            $_SESSION['correo'], 
            $_REQUEST['contraseñaN'],
            $_REQUEST['fecha_nac'],
            $_REQUEST['telefono'],
            $_REQUEST['direccion'],
            $_SESSION['id']
        );
        $con = $u->conectarBD();
        $id = $_SESSION['id'];
        $query = mysqli_query($con, "SELECT * FROM usuarias WHERE id = $id");
        if ($usuaria = mysqli_fetch_assoc($query)) {
            $_SESSION['nombre'] = $usuaria['nombre'];
            $_SESSION['apellidos'] = $usuaria['apellidos'];
            $_SESSION['nickname'] = $usuaria['nickname'];
            $_SESSION['correo'] = $usuaria['correo'];
            $_SESSION['fecha_nacimiento'] = $usuaria['fecha_nac'];
            $_SESSION['telefono'] = $usuaria['telefono'];
            $_SESSION['direccion'] = $usuaria['direccion'];
            $_SESSION['id_rol'] = $usuaria['id_rol'];

            // Redirección según rol
            if ($usuaria['id_rol'] == 1) {
                header("Location: ../Vista/usuaria/perfil.php?status=success&message=Datos+actualizados+correctamente");
            } else if ($usuaria['id_rol'] == 2) {
                header("Location: ../Vista/especialista/perfil.php?status=success&message=Datos+actualizados+correctamente");
            } else {
                header("Location: ../Vista/login.php?status=error&message=Rol+no+reconocido");
            }
            exit;
        }
}
