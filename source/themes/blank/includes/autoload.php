<?php
/**
 * WPBP autoloader
 *
 * @package  Blank
 * @since    0.2.0
 */

namespace Blank;

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
$theme_file_path     = dirname( __DIR__ ) . '/';
$composer_autoloader = $theme_file_path . 'vendor/autoload.php';

if ( file_exists( $composer_autoloader ) ) {
	require_once $composer_autoloader;
}

unset( $composer_autoloader );
// phpcs:enable

spl_autoload_register( function ( $class_name ) use ( $theme_file_path ) {
	if ( strpos( $class_name, __NAMESPACE__ ) !== 0 ) {
		return;
	}

	$path = $theme_file_path . strtolower( str_replace(
		[ '\\', __NAMESPACE__, '_' ],
		[ DIRECTORY_SEPARATOR, 'includes', '-' ],
		$class_name
	) ) . '.php';

	if ( file_exists( $path ) ) {
		require_once $path;
	}
} );

$blank_files = new \RecursiveIteratorIterator(
	new \RecursiveDirectoryIterator( __DIR__ )
);

/**
 * @var \SplFileInfo $blank_file
 */
foreach ( $blank_files as $blank_file ) {
	if ( substr( $blank_file->getFilename(), -14 ) === '-functions.php' ) {
		require_once $blank_file->getPathname();
	}
}

unset( $blank_files, $blank_file );

if ( ! function_exists( 'dump' ) && ( defined( 'WP_DEBUG_DISPLAY' ) && WP_DEBUG_DISPLAY ) ) {
	/**
	 * Lil' helper to dump a value.
	 *
	 * @param  mixed ...$args
	 * @return void
	 * @codeCoverageIgnore
	 */
	function dump( ...$args ) {
		global $theme_file_path;

		ob_start();

		array_map( function ( $arg ) {
			// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_var_dump
			var_dump( $arg );
		}, $args );

		$dump = \str_replace( $theme_file_path, '', ob_get_clean() );

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $dump;
		exit;
	}
}
