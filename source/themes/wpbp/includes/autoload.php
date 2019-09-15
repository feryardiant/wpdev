<?php
/**
 * WPBP autoloader
 *
 * @package     WordPress_Boilerplate
 * @subpackage  WPBP_Theme
 * @since       0.1.0
 */

namespace WPBP;

spl_autoload_register( function ( $class_name ) {
	if ( strpos( $class_name, __NAMESPACE__ ) !== 0 ) {
		return;
	}

	$path = get_theme_file_path( strtolower( str_replace(
		[ '\\', __NAMESPACE__, '_' ],
		[ DIRECTORY_SEPARATOR, 'includes', '-' ],
		$class_name
	) ) . '.php' );

	if ( file_exists( $path ) ) {
		require_once $path;
	}
} );
