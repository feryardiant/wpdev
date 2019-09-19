<?php
/**
 * WPBP Theme functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package     WordPress_Boilerplate
 * @subpackage  WPBP_Theme
 * @since       0.1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 980; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals
}

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

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function wpbp_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'wpbp_content_width', 640 );
}
add_action( 'after_setup_theme', 'wpbp_content_width', 0 );
