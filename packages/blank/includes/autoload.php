<?php
/**
 * WPBP autoloader
 *
 * @package  Blank
 * @since    0.2.0
 */

$blank_dirs = [
	'Blank' => dirname( __DIR__ ) . '/',
];

if ( defined( 'BLANK_EXTRA_DIR' ) ) {
	$blank_dirs['Blank_Extra'] = BLANK_EXTRA_DIR;
}

// phpcs:disable
spl_autoload_register( function ( $class ) use ( $blank_dirs ) {
	foreach ( $blank_dirs as $prefix => $dir ) {
		$namespace = explode( '\\', $class );

		if ( $namespace[0] !== $prefix ) {
			continue;
		}

		$path = $dir . strtolower( str_replace(
			[ '\\', $prefix, '_' ],
			[ DIRECTORY_SEPARATOR, 'includes', '-' ],
			$class
		) ) . '.php';

		if ( file_exists( $path ) ) {
			require_once $path;
		}
	}

} );

foreach ( $blank_dirs as $namespace => $dir ) {
	$composer_autoloader = $dir . 'vendor/autoload.php';

	if ( file_exists( $composer_autoloader ) ) {
		require_once $composer_autoloader;
	}

	$files = new \RecursiveIteratorIterator(
		new \RecursiveDirectoryIterator( $dir )
	);

	/**
	 * @var \SplFileInfo $function
	 */
	foreach ( $files as $function ) {
		if ( substr( $function->getFilename(), -14 ) === '-functions.php' ) {
			require_once $function->getPathname();
		}
	}
}

unset( $composer_autoloader, $files, $function );

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
