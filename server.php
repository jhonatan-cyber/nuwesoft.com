<?php

/**
 * Laravel - Router para el servidor embebido de PHP
 *
 * Permite emular la funcionalidad "mod_rewrite" de Apache
 * desde el servidor web embebido de PHP.
 */
$publicPath = getcwd();

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? ''
);

// Si el archivo o directorio existe, servirlo directamente
if ($uri !== '/' && file_exists($publicPath . $uri)) {
    return false;
}

$formattedDateTime = date('D M j H:i:s Y');

$requestMethod = $_SERVER['REQUEST_METHOD'];
$remoteAddress = $_SERVER['REMOTE_ADDR'] . ':' . $_SERVER['REMOTE_PORT'];

file_put_contents('php://stdout', "[$formattedDateTime] $remoteAddress [$requestMethod] URI: $uri\n");

require_once $publicPath . '/index.php';
