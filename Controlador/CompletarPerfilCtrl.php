<?php
include("../Modelo/completarPerfil.php");

session_start();
$idUsuaria = $_SESSION['id_usuaria'] ?? null;
if (!$idUsuaria) {
    header("Location: ../Vista/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['opcion'] == 1) {

    $otrosDocs = ['documento1', 'documento2', 'documento3', 'documento4'];
    $alMenosUno = false;

    foreach ($otrosDocs as $doc) {
        if (isset($_FILES[$doc]) && $_FILES[$doc]['error'] === 0) {
            $alMenosUno = true;
            break;
        }
    }

    if (!$alMenosUno) {
        header("Location: ../Vista/especialista/perfil.php?status=error&message=Debes+subir+al+menos+un+documento+adicional");
        exit;
    }

    $cp = new Completar();

    $cp->inicializar(
        $_FILES['id_oficial'],
        $_FILES['documento1'] ?? null,
        $_FILES['documento2'] ?? null,
        $_FILES['documento3'] ?? null,
        $_FILES['documento4'] ?? null
    );

    $cp->completarPerfil($idUsuaria);

    header("Location: ../Vista/especialista/perfil.php?status=success&message=Perfil+completado+correctamente,+espera+la+validaci%C3%B3n+de+tus+documentos.");
    exit;
}
