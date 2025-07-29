<?php
require_once "comentariosModelo.php";

$modelo = new Comentario();

// Cambia este ID por un comentario raíz que sepas que tiene respuestas
$idComentario = 250;

$respuestasCount = $modelo->contarRespuestasPorPadre($idComentario);

echo "Número de respuestas para el comentario con ID $idComentario: $respuestasCount\n";

// También puedes probar obtener las respuestas
$respuestas = $modelo->obtenerRespuestasPorPadre($idComentario);

echo "Respuestas:\n";
foreach ($respuestas as $r) {
    echo "- (" . $r['id_comentario'] . ") " . $r['comentario'] . " por " . $r['nombre'] . "\n";
}
