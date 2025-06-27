<?php
// Extrae la URL base del proyecto sin importar en qué carpeta estés
function getBaseUrl($folder = 'Shakti')
{
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $uri = $_SERVER['REQUEST_URI'];

    // Busca la posición donde aparece la carpeta base
    $pos = strpos($uri, '/' . $folder);
    if ($pos !== false) {
        $basePath = substr($uri, 0, $pos + strlen($folder) + 1);
        return $protocol . '://' . $host . $basePath;
    } else {
        // Carpeta no encontrada, devuelve dominio base
        return $protocol . '://' . $host . '/';
    }
}

?>