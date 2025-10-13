<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti//Modelo/alzalaVozModelo.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti//Modelo/conexion.php';

$id_usuaria = $_SESSION['id_usuaria'] ?? null;

if (!$id_usuaria) {
    $_SESSION['mensaje'] = "Debes iniciar sesión para realizar el test.";
    header("Location: ../Vista/login.php");
    exit;
}

$db = new ConectarDB();
$conexion = $db->open();

if (!$conexion) {
    $_SESSION['mensaje'] = "Error en la conexión a la base de datos.";
    header("Location: ../Vista/usuaria/alzalaVoz");
    exit;
}

$modelo = new AlzaLaVozModelo($conexion);

// Verificar si la usuaria ya hizo un test en las últimas 2 semanas
$fechaLimite = date('Y-m-d H:i:s', strtotime('-14 days'));
$ultimaPrueba = $modelo->obtenerUltimaPrueba($id_usuaria);

if ($ultimaPrueba && $ultimaPrueba >= $fechaLimite) {
    $_SESSION['mensaje'] = "Ya realizaste el test hace menos de 2 semanas. Por favor espera para hacerlo de nuevo.";
    header("Location: ../Vista/usuaria/alzalaVoz");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviar_test'])) {
    $respuestas = $_POST['respuestas'] ?? [];

    if (empty($respuestas)) {
        $_SESSION['mensaje'] = "Debes responder al menos una pregunta.";
        header("Location: ../Vista/usuaria/alzalaVoz");
        exit;
    }

    $resultado_test = json_encode($respuestas);
    $tipo_violencia = detectarTipoViolencia($respuestas);

    $guardado = $modelo->guardarResultado($id_usuaria, $resultado_test, $tipo_violencia);

    if ($guardado) {
        $_SESSION['mensaje'] = "Test guardado correctamente.";
        $_SESSION['tipo_violencia'] = $tipo_violencia; // Mantener el mensaje visible sin borrar
    } else {
        $_SESSION['mensaje'] = "Error al guardar el test. Intenta de nuevo.";
    }

    header("Location: ../Vista/usuaria/alzalaVoz");
    exit;
}

// Si se accede sin POST válido, solo redirige a la vista
header("Location: ../Vista/usuaria/alzalaVoz");
exit;


function detectarTipoViolencia($respuestas) {
    $tipos = [
        'fisica' => 0,
        'psicologica' => 0,
        'economica' => 0
    ];

    foreach ($respuestas as $respuesta) {
        if (isset($tipos[$respuesta])) {
            $tipos[$respuesta]++;
        }
    }

    arsort($tipos);
    if ($tipos[array_key_first($tipos)] > 0) {
        $mapaTipos = [
            'fisica' => 'Violencia física',
            'psicologica' => 'Violencia psicológica',
            'economica' => 'Violencia económica'
        ];
        return $mapaTipos[array_key_first($tipos)];
    }

    return "No detectado";
}
