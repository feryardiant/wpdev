<?php
/**
 * Router file for PHP Development server.
 */

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (substr($path, 1, 8) === 'wp-admin' && substr($path, 10) === '') {
    $path .= 'index.php';
}

if ('php' !== pathinfo($path, PATHINFO_EXTENSION)) {
    return false;
}

if (file_exists($wp_file = 'public/wp'.$path)) {
    include_once $wp_file;
} else {
    include_once 'public/index.php';
}

