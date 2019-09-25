<?php
/**
 * WPBP Theme functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package    WordPress_Boilerplate
 * @subpackage WPBP_Theme
 * @since      0.1.0
 */

/**
 * Autoloader
 */
require_once __DIR__ . '/includes/autoload.php';

$wpbp_theme = new WPBP\Theme();

if ( ! function_exists( 'wpbp' ) ) {
	/**
	 * Main theme object.
	 *
	 * @param string|null $key Theme object key.
	 * @return WPBP\Theme
	 */
	function wpbp( $key = null ) {
		global $wpbp_theme;

		return $key ? $wpbp_theme->info( $key ) : $wpbp_theme;
	}
}
