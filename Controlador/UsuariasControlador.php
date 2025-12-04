<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Modelo/Usuarias.php';
include_once '../Modelo/confirmarCorreo.php';
include_once '../obtenerLink/obtenerLink.php';


$u = new Usuarias();
$urlBase = getBaseUrl();

switch ($_REQUEST['opcion']) {
    case 1:
        // Inicializar la usuaria con los datos del POST
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

        // Agregar usuaria a la BD y obtener el ID recién insertado
        $id_usuaria = $u->agregarUsuaria(); 
         header("Location: ../Vista/login?status=success&message=" . urlencode("Cuenta creada exitosamente. Revisa tu correo para verificar tu cuenta."));
        exit;

        if ($id_usuaria) {
            // Enviar correo de confirmación
            $correoConfirmacion = new ConfirmarCorreo();
            $correoConfirmacion->inicializar($_REQUEST['correo'], $_REQUEST['nombre'], $urlBase, $id_usuaria);
            $enviado = $correoConfirmacion->enviarCorreoVerificacion();

            if ($enviado) {
                echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Cuenta creada correctamente',
                        text: 'Por favor revisa tu correo para verificar tu cuenta.',
                        confirmButtonColor: '#5a2a83'
                    }).then(() => {
                        window.location.href = '{$urlBase}/Vista/login';
                    });
                </script>";
            } else {
                echo "<script>
                    Swal.fire({
                        icon: 'warning',
                        title: 'Cuenta creada pero sin verificación',
                        text: 'No se pudo enviar el correo, intenta más tarde.',
                        confirmButtonColor: '#5a2a83'
                    }).then(() => {
                        window.location.href = '{$urlBase}/Vista/login';
                    });
                </script>";
            }
        }
        break;

    case 2:
        session_start();
        if (!isset($_SESSION['id'])) {
            header("Location: ../Vista/login");
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
        if (!isset($_SESSION['id_usuaria'])) {
            header("Location: ../Vista/login");
            exit;
        }

        $resultado = $u->cambiarFotoPerfil($_SESSION['id_usuaria'], $_FILES['nuevaFoto']);
        $status = $resultado['status'];
        $message = urlencode($resultado['message']);

        if (isset($_SESSION['id_rol']) && $_SESSION['id_rol'] == 2) {
            header("Location: ../Vista/especialista/perfil?status=$status&message=$message");
        } else {
            header("Location: ../Vista/usuaria/perfil?status=$status&message=$message");
        }
        exit;

    case 5:
        session_start();
        if (!isset($_SESSION['id_usuaria'])) {
            header("Location: ../Vista/login");
            exit;
        }

        $resultado = $u->eliminarFotoPerfil($_SESSION['id']);
        $status = $resultado['status'];
        $message = urlencode($resultado['message']);

        if (isset($_SESSION['id_rol']) && $_SESSION['id_rol'] == 2) {
            header("Location: ../Vista/especialista/perfil?status=$status&message=$message");
        } else {
            header("Location: ../Vista/usuaria/perfil?status=$status&message=$message");
        }
        exit;
}
