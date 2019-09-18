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

$wpbp_files = new \RecursiveIteratorIterator(
	new \RecursiveDirectoryIterator( __DIR__ )
);

/**
 * @var \SplFileInfo $wpbp_file
 */
foreach ( $wpbp_files as $wpbp_file ) {
	if ( substr( $wpbp_file->getFilename(), -14 ) === '-functions.php' ) {
		require_once $wpbp_file->getPathname();
	}
}

unset( $wpbp_files, $wpbp_file );
