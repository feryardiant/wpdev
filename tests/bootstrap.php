<?php
/**
 * PHPUnit bootstrap file
 *
 * @package Sample
 */

defined('BASE_PATH') || define('BASE_PATH', dirname(__DIR__));
defined('STUBS_PATH') || define('STUBS_PATH', BASE_PATH.'/tests/stubs');

require_once BASE_PATH.'/vendor/autoload.php';

WP_Mock::bootstrap();

require_once BASE_PATH.'/source/themes/blank/includes/autoload.php';
