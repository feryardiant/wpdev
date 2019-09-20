<?php

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (pathinfo($path, PATHINFO_EXTENSION) !== 'php') {
    return false;
}

if (file_exists($wp_file = 'public/wp'.$path)) {
    require_once $wp_file;
} else {
    require_once 'public/index.php';
}

