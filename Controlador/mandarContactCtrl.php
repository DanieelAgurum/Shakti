<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Modelo/contactMdl.php';

$contacto = new contactMdl();
if (isset($_REQUEST['opcion'])) {
    switch ($_REQUEST['opcion']) {
        case 1:
            $contacto->inicializar($_POST['correo'], $_POST['comentario']);
            $contacto->mandarContact();
            break;
        default:
            echo "Opción no válida";
            exit;
    }
}
