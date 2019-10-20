<?php
/**
 * PHPUnit bootstrap file
 *
 * @package Sample
 */

defined('BASE_PATH') || define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH.'/vendor/autoload.php';

WP_Mock::bootstrap();

require_once BASE_PATH.'/source/themes/blank/includes/autoload.php';
