<?php
/**
 * Blank Theme.
 *
 * @package  Blank
 * @since    0.2.1
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
	 */
	protected function initialize() : void {
		add_action( 'wp_loaded', function () {
			$this->get_google_webfonts();
		} );

		add_filter( 'blank_option_default', [ $this, 'default_option' ], 10, 3 );
	}

	/**
	 * Get all fonts.
	 *
	 * @since 0.2.1
	 * @return array
	 */
	public function get_fonts() : array {
		static $fonts = [];

		$name  = $this->theme->transient_name( 'fonts_info' );
		$fonts = get_transient( $name ) ?: $fonts;

		if ( empty( $fonts ) ) {
			$fonts = array_merge( $this->get_system_fonts(), $this->get_google_webfonts() );

			set_transient( $name, $fonts );
		}

		return $fonts;
	}

	/**
	 * Get all fonts in used
	 *
	 * @return array
	 */
	public function get_options_values() : array {
		static $values = [];

		if ( ! empty( $values ) ) {
			return $values;
		}

		$all_fonts = $this->get_fonts();
		$options   = array_filter( $this->theme->options( 'settings' ), function ( $setting ) {
			return 'blank-typography' === $setting['type'];
		} );

		foreach ( array_keys( $options ) as $name ) {
			$value       = (object) $this->theme->get_option( $name );
			$value->name = $name;

			$fonts = array_values(
				array_filter( $all_fonts, function ( $font ) use ( $value ) {
					return $font->family === $value->family;
				} )
			);

			$value->weight = in_array( $value->variant, [ 'regular', 'italic' ], true ) ? 400 : substr( $value->variant, 3 );
			$value->style  = 'italic' === substr( $value->variant, -6 ) ? 'italic' : 'normal';

			if ( ! empty( $fonts ) && $fonts[0] ) {
				$value->category = $fonts[0]->category;
			}

			$values[] = $value;
		}

		return $values;
	}

	/**
	 * Register Google fonts.
	 *
	 * @since 0.2.2
	 * @param mixed  $default
	 * @param string $type
	 * @param string $name
	 * @return array Google fonts URL for the theme.
	 */
	public function default_option( $default, string $type, string $name ) {
		if ( 'blank-typography' !== $type ) {
			return $default;
		}

		$family = 'Source Sans Pro';

		if ( 'typography_pre_font' === $name ) {
			$family = 'Source Code Pro';
		}

		return wp_parse_args( $default ?: [], [
			'family'    => $family,
			'variant'   => 'regular',
			'subsets'   => [ 'latin' ],
			'size'      => [ 1, 'rem' ],
			'height'    => [ 1, 'em' ],
			'transform' => 'initial',
		] );
	}

	/**
	 * Register Google fonts.
	 *
	 * @since 0.2.2
	 * @return string|null Google fonts URL for the theme.
	 */
	public function get_google_fonts_url() : ?string {
		$google_fonts = (object) apply_filters( 'blank_google_fonts_enabled', [
			'families' => [],                      // Source+Sans+Pro.
			'variants' => [ 'regular', 'italic' ], // regular, italic, 300, 300italic.
			'subsets'  => [ 'latin' ],             // latin-ext.
		] );

		if ( empty( $google_fonts->families ) ) {
			return null;
		}

		$query_args = [
			'family' => implode( '|', $google_fonts->families ),
			'subset' => rawurlencode( 'latin,latin-ext' ),
		];

		return add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	/**
	 * Get all available System Fonts.
	 *
	 * Should we add these emoji fonts?
	 * - "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol"
	 * As well as these system fonts?
	 * - -apple-system, BlinkMacSystemFont
	 * - SFMono-Regular
	 *
	 * @link https://www.tutorialbrain.com/css_tutorial/css_font_family_list/
	 * @since 0.2.1
	 * @return array
	 */
	public function get_system_fonts() : array {
		$fonts   = [];
		$default = [
			'source'   => 'system',
			'subsets'  => [ 'latin' ],
			'variants' => [
				'300',
				'300italic',
				'regular',
				'italic',
				'700',
				'700italic',
			],
		];

		$fonts[] = (object) wp_parse_args( [
			'family'   => 'Segoe UI',
			'category' => 'sans-serif',
		], $default );

		$fonts[] = (object) wp_parse_args( [
			'family'   => 'Roboto',
			'category' => 'sans-serif',
		], $default );

		$fonts[] = (object) wp_parse_args( [
			'family'   => 'Helvetica Neue',
			'category' => 'sans-serif',
		], $default );

		$fonts[] = (object) wp_parse_args( [
			'family'   => 'Helvetica',
			'category' => 'sans-serif',
		], $default );

		$fonts[] = (object) wp_parse_args( [
			'family'   => 'Verdana',
			'category' => 'sans-serif',
		], $default );

		$fonts[] = (object) wp_parse_args( [
			'family'   => 'Arial',
			'category' => 'sans-serif',
		], $default );

		$fonts[] = (object) wp_parse_args( [
			'family'   => 'Times New Roman',
			'category' => 'serif',
		], $default );

		$fonts[] = (object) wp_parse_args( [
			'family'   => 'Times',
			'category' => 'serif',
		], $default );

		$fonts[] = (object) wp_parse_args( [
			'family'   => 'Georgia',
			'category' => 'serif',
		], $default );

		$fonts[] = (object) wp_parse_args( [
			'family'   => 'Menlo',
			'category' => 'monospace',
		], $default );

		$fonts[] = (object) wp_parse_args( [
			'family'   => 'Monaco',
			'category' => 'monospace',
		], $default );

		$fonts[] = (object) wp_parse_args( [
			'family'   => 'Consolas',
			'category' => 'monospace',
		], $default );

		$fonts[] = (object) wp_parse_args( [
			'family'   => 'Liberation Mono',
			'category' => 'monospace',
		], $default );

		$fonts[] = (object) wp_parse_args( [
			'family'   => 'Courier New',
			'category' => 'monospace',
		], $default );

		$fonts[] = (object) wp_parse_args( [
			'family'   => 'Courier',
			'category' => 'monospace',
		], $default );

		return $fonts;
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
				'source'   => 'google',
				'variants' => $item->variants,
				'subsets'  => $item->subsets,
				'files'    => $item->files,
			];
		}

		return $items;
	}
}
