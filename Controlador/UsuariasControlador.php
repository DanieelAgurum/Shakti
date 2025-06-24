<?php

include("../modelo/Usuarias.php");

$u = new Usuarias();
$u->conectarBD();
switch ($_REQUEST['opcion']) {
    case 1:
        $documento = isset($_FILES['documento']) && $_FILES['documento']['error'] == 0 ? $_FILES['documento'] : null;

        $u->inicializar(
            $_REQUEST['nombre'],
            $_REQUEST['apellidos'],
            $_REQUEST['nickname'],
            $_REQUEST['correo'],
            $_REQUEST['contraseña'],
            $_REQUEST['conContraseña'],
            $_REQUEST['fecha_nac'],
            $_REQUEST['rol'],
            $documento
        );
        $u->agregarUsuaria();
        break;
}
