<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Modelo/Usuarias.php';

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
            $_REQUEST['nombreN'],
            $_REQUEST['apellidosN'],
            $_REQUEST['nicknameN'],
            $_SESSION['correo'],
            $_REQUEST['contraseñaN'],
            $_REQUEST['fecha_nac'],
            $_REQUEST['telefono'],
            $_REQUEST['direccion'],
            $_REQUEST['descripcion'],
            $_SESSION['id']
        );

        break;
    case 3:
        $u->eliminarUsuaria($_REQUEST['id']);
        break;
    case 4:
        session_start();
        if (!isset($_SESSION['id'])) {
            header("Location: ../Vista/login.php");
            exit;
        }

        $resultado = $u->cambiarFotoPerfil($_SESSION['id'], $_FILES['nuevaFoto']);
        $status = $resultado['status'];
        $message = urlencode($resultado['message']);
        header("Location: ../Vista/usuaria/perfil.php?status=$status&message=$message");
        exit;
    case 5:
        session_start();
        if (!isset($_SESSION['id'])) {
            header("Location: ../Vista/login.php");
            exit;
        }

        $resultado = $u->eliminarFotoPerfil($_SESSION['id']);
        $status = $resultado['status'];
        $message = urlencode($resultado['message']);

        header("Location: ../Vista/usuaria/perfil.php?status=$status&message=$message");
        exit;
}
