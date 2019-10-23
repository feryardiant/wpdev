<?php
/**
 * Blank Theme.
 *
 * @package    WordPress_Boilerplate
 * @subpackage WPBP_Theme
 * @since      0.2.1
 */

namespace Blank;

/**
 * Theme Typography Class.
 *
 * @category  Typography
 */
class Typography extends Feature {
	const GENERIC_NAME_ALIASES = [
		'serif'       => 'serif',
		'sans-serif'  => 'sans-serif',
		'monospace'   => 'monospace',
		'handwriting' => 'cursive',
		'display'     => 'fantasy',
	];

	/**
	 * Fonts Variants.
	 *
	 * @since 0.2.1
	 */
	const VARIANTS = [
		'100'       => 'Ultra-Light 100',
		'100italic' => 'Ultra-Light 100 Italic',
		'200'       => 'Light 200',
		'200italic' => 'Light 200 Italic',
		'300'       => 'Book 300',
		'300italic' => 'Book 300 Italic',
		'regular'   => 'Normal 400',
		'italic'    => 'Normal 400 Italic',
		'500'       => 'Medium 500',
		'500italic' => 'Medium 500 Italic',
		'600'       => 'Semi-Bold 600',
		'600italic' => 'Semi-Bold 600 Italic',
		'700'       => 'Bold 700',
		'700italic' => 'Bold 700 Italic',
		'800'       => 'Extra-Bold 800',
		'800italic' => 'Extra-Bold 800 Italic',
		'900'       => 'Ultra-Bold 900',
		'900italic' => 'Ultra-Bold 900 Italic',
	];

	/**
	 * Fonts Subsets.
	 *
	 * @since 0.2.1
	 */
	const SUBSETS = [
		'cyrillic'     => 'Cyrillic',
		'cyrillic-ext' => 'Cyrillic Extended',
		'devanagari'   => 'Devanagari',
		'greek'        => 'Greek',
		'greek-ext'    => 'Greek Extended',
		'khmer'        => 'Khmer',
		'latin'        => 'Latin',
		'latin-ext'    => 'Latin Extended',
		'vietnamese'   => 'Vietnamese',
		'hebrew'       => 'Hebrew',
		'arabic'       => 'Arabic',
		'bengali'      => 'Bengali',
		'gujarati'     => 'Gujarati',
		'tamil'        => 'Tamil',
		'telugu'       => 'Telugu',
		'thai'         => 'Thai',
	];

	/**
	 * Initialize class.
	 *
	 * -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue",
	 * Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
	 * SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
	 */
	protected function initialize() : void {
		add_action( 'wp_loaded', function () {
			$this->get_google_webfonts();
		} );
	}

	/**
	 * Get all available Google Webfonts.
	 *
	 * @since 0.2.1
	 * @return array
	 */
	public function get_google_webfonts() : array {
		$fonts_cache_file = $this->theme->get_dir( 'assets/google-webfonts.json' );
		$filesystem       = $this->theme->get_filesystem();

		if ( $filesystem->exists( $fonts_cache_file ) ) {
			return json_decode( $filesystem->get_contents( $fonts_cache_file ) );
		}

		$fonts = $this->fetch_google_webfonts();

		$filesystem->put_contents( $fonts_cache_file, wp_json_encode( $fonts ) );

		return $fonts;
	}

	/**
	 * Get all available System Fonts.
	 *
	 * @since 0.2.1
	 * @return array
	 */
	public function get_system_fonts() : array {
		return [];
	}

	/**
	 * Fetch all Google Webfonts.
	 *
	 * @since 0.2.1
	 * @return array
	 * @throws \RuntimeException If invalid.
	 */
	public function fetch_google_webfonts() : array {
		$google_key = getenv( 'GOOGLE_API_KEY' ) ?: null;

		if ( null === $google_key ) {
			throw new \RuntimeException( 'Could not fetch google webfont' );
		}

		$response = wp_remote_get(
			add_query_arg( [ 'key' => $google_key ], 'https://www.googleapis.com/webfonts/v1/webfonts' )
		);

		if ( is_wp_error( $response ) ) {
			throw $response;
		}

		if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
			$body = json_decode( $response['body'] );
			throw new \RuntimeException( $body->errors->reason );
		}

		$fonts = json_decode( $response['body'] );
		$items = [];

		foreach ( $fonts->items as $item ) {
			$category = array_key_exists( $item->category, self::GENERIC_NAME_ALIASES )
				? self::GENERIC_NAME_ALIASES[ $item->category ]
				: $item->category;

			$items[] = (object) [
				'family'   => $item->family,
				'category' => $category,
				'variants' => $item->variants,
				'subsets'  => $item->subsets,
				'files'    => $item->files,
			];
		}

		return $items;
	}
}
