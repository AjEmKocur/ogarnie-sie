<?php

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');
$file = __DIR__.$uri;

// Let PHP serve real static files directly (CSS/JS/images/build assets).
if ($uri !== '/' && is_file($file)) {
    return false;
}

require __DIR__.'/index.php';
