<?php
/**
 * PHPUnit bootstrap file
 *
 * @package Sample
 */

$root = dirname(__DIR__);

require_once $root.'/vendor/autoload.php';

WP_Mock::bootstrap();

require_once $root.'/source/themes/blank/includes/autoload.php';
