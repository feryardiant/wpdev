<?php
/**
 * WPBP autoloader
 *
 * @package  Blank
 * @since    0.2.0
 */

// phpcs:disable
$theme_dir = dirname( __DIR__ ) . '/';

if ( file_exists( $composer_autoloader = $theme_dir . 'vendor/autoload.php' ) ) {
	require_once $composer_autoloader;
}

unset( $composer_autoloader );

spl_autoload_register( function ( $class ) use ( $theme_dir ) {
	$namespace = 'Blank';

	if ( strpos( $class, $namespace ) !== 0 ) {
		return;
	}

	$path = $theme_dir . strtolower( str_replace(
		[ '\\', $namespace, '_' ],
		[ DIRECTORY_SEPARATOR, 'includes', '-' ],
		$class
	) ) . '.php';

	if ( file_exists( $path ) ) {
		require_once $path;
	}
} );

$files = new \RecursiveIteratorIterator(
	new \RecursiveDirectoryIterator( __DIR__ )
);

/**
 * @var \SplFileInfo $function
 */
foreach ( $files as $function ) {
	if ( substr( $function->getFilename(), -14 ) === '-functions.php' ) {
		require_once $function->getPathname();
	}
}

unset( $files, $function );
// phpcs:enable
