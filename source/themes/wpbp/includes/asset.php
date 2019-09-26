<?php
/**
 * WPBP Theme.
 *
 * @package    WordPress_Boilerplate
 * @subpackage WPBP_Theme
 * @since      0.1.0
 */

namespace WPBP;

/**
 * Theme Asset Class.
 *
 * @category  Theme Asset
 */
class Asset extends Feature {
	/**
	 * Initialize class.
	 *
	 * @since 0.1.1
	 */
	protected function initialize() : void {
		add_action( 'after_setup_theme', [ $this, 'setup' ] );
		add_action( 'login_head', [ $this, 'login_head' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue' ] );
	}

	/**
	 * Enqueue scripts and styles
	 *
	 * @internal
	 * @since 0.1.0
	 * @return void
	 */
	public function enqueue() {
		$version = $this->theme->info( 'version' );

		wp_register_style( 'wpbp-google-fonts', $this->google_fonts_url(), [], $version );

		wp_register_style( 'wpbp-css-variables', false, [], $version );
		wp_add_inline_style( 'wpbp-css-variables', $this->css_variables( $this->theme ) );

		wp_enqueue_style( 'wpbp-style', $this->theme->get_assets_uri( 'main.css' ), [ 'wpbp-google-fonts', 'wpbp-css-variables' ], $version );
		wp_style_add_data( 'wpbp-style', 'rtl', 'replace' );

		wp_enqueue_script( 'wpbp-script', $this->theme->get_assets_uri( 'navigation.js' ), [], $version, true );

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * @link https://developer.wordpress.org/reference/functions/add_theme_support/
	 *
	 * @internal
	 * @since 0.1.0
	 * @return void
	 */
	public function setup() {
		/**
		 * Add support for Block Styles.
		 */
		add_theme_support( 'wp-block-styles' );

		/**
		 * Add support for full and wide align images.
		 */
		add_theme_support( 'align-wide' );

		/**
		 * Add support for editor styles.
		 */
		add_theme_support( 'editor-styles' );

		/**
		 * Enqueue editor styles.
		 */
		add_editor_style( [
			$this->theme->get_assets_uri( 'gutenberg-editor.css' ),
			$this->google_fonts_url(),
		] );

		/**
		 * Add support for editor font sizes.
		 */
		add_theme_support(
			'editor-font-sizes',
			apply_filters( 'wpbp_editor_font_sizes_args', [
				[
					'name' => __( 'Small', 'wpbp' ),
					'size' => 12,
					'slug' => 'small',
				],
				[
					'name' => __( 'Normal', 'wpbp' ),
					'size' => 14,
					'slug' => 'normal',
				],
				[
					'name' => __( 'Medium', 'wpbp' ),
					'size' => 20,
					'slug' => 'medium',
				],
				[
					'name' => __( 'Large', 'wpbp' ),
					'size' => 26,
					'slug' => 'large',
				],
				[
					'name' => __( 'Huge', 'wpbp' ),
					'size' => 32,
					'slug' => 'huge',
				],
			] )
		);

		/**
		 * Add support for responsive embedded content.
		 */
		add_theme_support( 'responsive-embeds' );
	}

	/**
	 * Theme CSS Variables
	 *
	 * @internal
	 * @since 0.1.0
	 * @param  Theme $theme
	 * @return string
	 */
	protected function css_variables( Theme $theme ) {
		return self::make_css( [
			':root' => [
				'--white-color'            => $theme->get_mod( 'custom_white_color', '#fff' ),
				'--gray-color'             => $theme->get_mod( 'custom_gray_color', '#6c757d' ),
				'--gray-dark-color'        => $theme->get_mod( 'custom_gray_dark_color', '#343a40' ),
				'--primary-color'          => $theme->get_mod( 'custom_primary_color', '#007bff' ),
				'--secondary-color'        => $theme->get_mod( 'custom_secondary_color', '#6c757d' ),
				'--success-color'          => $theme->get_mod( 'custom_success_color', '#28a745' ),
				'--info-color'             => $theme->get_mod( 'custom_info_color', '#17a2b8' ),
				'--warning-color'          => $theme->get_mod( 'custom_warning_color', '#ffc107' ),
				'--danger-color'           => $theme->get_mod( 'custom_danger_color', '#dc3545' ),
				'--light-color'            => $theme->get_mod( 'custom_light_color', '#f8f9fa' ),
				'--dark-color'             => $theme->get_mod( 'custom_dark_color', '#343a40' ),

				'--breakpoint-xs'          => '0',
				'--breakpoint-sm'          => $theme->get_mod( 'custom_breakpoint_sm', '576px' ),
				'--breakpoint-md'          => $theme->get_mod( 'custom_breakpoint_md', '768px' ),
				'--breakpoint-lg'          => $theme->get_mod( 'custom_breakpoint_lg', '992px' ),
				'--breakpoint-xl'          => $theme->get_mod( 'custom_breakpoint_xl', '1200px' ),

				'--font-family-sans-serif' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol"',
				'--font-family-monospace'  => 'SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace',

				'--link-color'             => $theme->get_mod( 'custom_link_color', '#007bff' ),
				'--link-hover-color'       => $theme->get_mod( 'custom_link_hover_color', '#007bff' ),
				'--link-active-color'      => $theme->get_mod( 'custom_link_active_color', '#007bff' ),

				'--background-color'       => get_theme_mod( 'background_color', '#fff' ),
				'--border-color'           => $theme->get_mod( 'custom_border_color', '#007bff' ),
				'--text-color'             => $theme->get_mod( 'custom_text_color', '#343a40' ),
				'--paragraph-color'        => $theme->get_mod( 'custom_paragraph_color', '#343a40' ),
				'--heading-color'          => $theme->get_mod( 'custom_heading_color', '#343a40' ),
			],
		] );
	}

	/**
	 * Custom login hook.
	 *
	 * @return void
	 */
	public function login_head() {
		$theme_version = $this->theme->info( 'version' );
		$login_style   = $this->login_style();

		if ( $login_style ) {
			wp_register_style( 'wpbp-custom-login', false, [], $theme_version );
			wp_add_inline_style( 'wpbp-custom-login', $login_style );

			wp_enqueue_style( 'wpbp-custom-login' );
		}
	}

	/**
	 * Set up the WordPress core custom header feature.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
	 *
	 * @internal
	 * @since 0.1.0
	 * @return void
	 */
	public function custom_header() {
		$header_text_color = get_header_textcolor();

		/*
		 * If no custom options for text are set, let's bail.
		 * get_header_textcolor() options: Any hex value, 'blank' to hide text. Default: add_theme_support( 'custom-header' ).
		 */
		if ( get_theme_support( 'custom-header', 'default-text-color' ) === $header_text_color ) {
			return;
		}

		$custom_header_style = [ '<style id="custom-header-style" type="text/css">' ];

		if ( ! display_header_text() ) {
			$custom_header_style[] = '.site-title, .site-description { position: absolute; clip: rect(1px, 1px, 1px, 1px); }';
		} else {
			$custom_header_style[] = '.site-title a, .site-description { color: #' . esc_attr( $header_text_color ) . '; }';
		}

		$custom_header_style[] = '</style>';

		echo implode( '', $custom_header_style ); // phpcs:ignore WordPress.Security.EscapeOutput
	}

	/**
	 * Custom login style.
	 *
	 * @internal
	 * @since 0.1.1
	 * @return string
	 */
	public function login_style() {
		$logo_id  = get_theme_mod( 'custom_logo' );
		$logo_url = wp_get_attachment_image_url( $logo_id, 'full' );

		if ( ! $logo_url ) {
			return null;
		}

		return self::make_css( function () use ( $logo_url ) {
			return [
				'.login h1 a' => [
					'background-image' => 'url(' . esc_url( $logo_url ) . ') !important',
				],
			];
		} );
	}

	/**
	 * Register Google fonts.
	 *
	 * @since 0.1.0
	 * @return string Google fonts URL for the theme.
	 */
	public function google_fonts_url() {
		$google_fonts = apply_filters( 'wpbp_google_font_families', [
			'source-sans-pro' => 'Source+Sans+Pro:400,300,300italic,400italic,600,700,900',
		] );

		$query_args = [
			'family' => implode( '|', $google_fonts ),
			'subset' => rawurlencode( 'latin,latin-ext' ),
		];

		return add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	/**
	 * Style maker.
	 *
	 * @param  \Closure|array $styles
	 * @return string
	 * @throws \InvalidArgumentException If parameter not an array or instance of Closure.
	 */
	public static function make_css( $styles ) {
		if ( $styles instanceof \Closure ) {
			$styles = $styles( self::get_instance() );
		}

		if ( ! is_array( $styles ) ) {
			throw new \InvalidArgumentException( 'Param 1 should be instance of Closure or an array' );
		}

		$inline = [];

		foreach ( $styles as $selector => $style ) {
			$inline[] = $selector . ' {' . PHP_EOL;

			foreach ( $style as $name => $value ) {
				$inline[] = $name . ': ' . $value . ';' . PHP_EOL;
			}

			$inline[] = '}' . PHP_EOL;
		}

		return PHP_EOL . implode( ' ', $inline );
	}
}
