<?php declare(strict_types=1);

$cwd = getcwd();

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? ''
);

if ($uri !== '/' && file_exists($cwd . $uri)) {
    return false;
}

require_once $cwd . '/index.php';