<?php
/**
 * Blank Theme functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package  Blank
 * @since    0.1.0
 */

/**
 * Autoloader
 */
require_once __DIR__ . '/includes/autoload.php';

/**
 * Initiate theme instance.
 */
$blank_theme = new Blank\Theme();

/**
 * Load theme options from `options` directory.
 */
$blank_theme->load_options();

if ( ! function_exists( 'blank' ) ) {
	/**
	 * Helper function to interact with theme instance.
	 *
	 * @param string|null $key Theme object key.
	 * @return Blank\Theme
	 */
	function blank( $key = null ) {
		global $blank_theme;

		return $key ? $blank_theme->$key : $blank_theme;
	}
}
