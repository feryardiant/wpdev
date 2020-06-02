<?php
/**
 * PHPUnit bootstrap file
 *
 * @package Sample
 */

defined('BASE_PATH')  || define('BASE_PATH', dirname(__DIR__));
defined('STUBS_PATH') || define('STUBS_PATH', BASE_PATH.'/tests/stubs');
// defined('ABSPATH')    || define('ABSPATH', BASE_PATH.'/public/wp');

require_once BASE_PATH.'/vendor/autoload.php';
require_once BASE_PATH.'/packages/blank/includes/autoload.php';

WP_Mock::bootstrap();
