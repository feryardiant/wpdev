<?php
/**
 * WPBP autoloader
 *
 * @package  Blank
 * @since    0.2.0
 */

if ( file_exists( BLANK_EXTRA_DIR . 'vendor/autoload.php' ) ) {
	require_once BLANK_EXTRA_DIR . 'vendor/autoload.php';
}

spl_autoload_register( function ( $class ) {
	$namespace = 'Blank_Extra';

	if ( strpos( $class, $namespace ) !== 0 ) {
		return;
	}

	$path = BLANK_EXTRA_DIR . strtolower( str_replace(
		[ '\\', $namespace, '_' ],
		[ DIRECTORY_SEPARATOR, 'includes', '-' ],
		$class
	) ) . '.php';

	if ( file_exists( $path ) ) {
		require_once $path;
	}
} );

// phpcs:disable
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

if ( ! function_exists( 'dump' ) && ( defined( 'WP_DEBUG_DISPLAY' ) && WP_DEBUG_DISPLAY ) ) {
	/**
	 * Lil' helper to dump a value.
	 *
	 * @param  mixed ...$args
	 * @return void
	 * @codeCoverageIgnore
	 */
	function dump( ...$args ) {
		ob_start();

		array_map( function ( $arg ) {
			var_dump( $arg );
		}, $args );

		$dump = \str_replace( WP_CONTENT_DIR, '', ob_get_clean() );

		echo $dump;
		exit;
	}
}
// phpcs:enable
