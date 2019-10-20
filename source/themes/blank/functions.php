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

$blank_theme = new Blank\Theme();

$blank_theme->load_options();

if ( ! function_exists( 'blank' ) ) {
	/**
	 * Main theme object.
	 *
	 * @param string|null $key Theme object key.
	 * @return Blank\Theme
	 */
	function blank( $key = null ) {
		global $blank_theme;

		return $key ? $blank_theme->$key : $blank_theme;
	}
}
