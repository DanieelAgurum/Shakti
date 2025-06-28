<?php
function getBaseUrl($folder = 'Shakti')
{
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $uri = $_SERVER['REQUEST_URI'];

    // Busca la posición donde aparece la carpeta base
    $pos = strpos($uri, '/' . $folder);
    if ($pos !== false) {
        // Obtiene el path completo hasta la carpeta base
        $basePath = substr($uri, 0, $pos + strlen($folder) + 1);
        // Asegura que termine en '/'
        if (substr($basePath, -1) !== '/') {
            $basePath .= '/';
        }
        return $protocol . '://' . $host . $basePath;
    } else {
        // Carpeta no encontrada, devuelve dominio base con '/'
        return $protocol . '://' . $host . '/';
    }
}
?>
