<?php

$request_uri = $_SERVER['REQUEST_URI'];

if (pathinfo($request_uri, PATHINFO_EXTENSION) !== 'php') {
    return false;    // serve the requested resource as-is
}

if (file_exists($wp_file = 'public/wp'.$request_uri)) {
    require_once $wp_file;
} else {
    require_once 'public/index.php';
}

