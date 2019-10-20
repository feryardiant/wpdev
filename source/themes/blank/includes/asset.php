<?php
/**
 * Blank Theme.
 *
 * @package  Blank
 * @since    0.2.0
 */

namespace Blank;

/**
 * Theme Asset Class.
 *
 * @category  Theme Asset
 */
class Asset extends Feature {
	/**
	 * Theme version
	 *
	 * @var string|null
	 */
	protected $version = null;

	/**
	 * Initialize class.
	 *
	 * @since 0.1.1
	 */
	protected function initialize() : void {
		add_action( 'after_setup_theme', [ $this, 'setup' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue' ] );
		add_action( 'login_head', [ $this, 'login_head' ] );

		// Prevent adding verion string while development.
		if ( ! self::is_debug() ) {
			$this->version = $this->theme->version;
		}
	}

	/**
	 * Enqueue scripts and styles
	 *
	 * @internal
	 * @since 0.1.0
	 * @return void
	 */
	public function enqueue() {
		wp_register_style( 'blank-google-fonts', $this->google_fonts_url(), [], $this->version );

		wp_register_style( 'blank-css-variables', false, [], $this->version );
		wp_add_inline_style( 'blank-css-variables', $this->css_variables( $this->theme ) );

		wp_enqueue_style( 'blank-style', $this->get_uri( 'main.css' ), [ 'blank-google-fonts', 'blank-css-variables' ], $this->version );
		wp_style_add_data( 'blank-style', 'rtl', 'replace' );

		wp_enqueue_script( 'blank-script', $this->get_uri( 'main.js' ), [ 'jquery' ], $this->version, true );

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}

	/**
	 * Enqueue Admin Scripts.
	 *
	 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
	 * @since 0.1.1
	 * @param string $hook
	 * @return void
	 */
	public function admin_enqueue( $hook ) {
		if ( ( 'appearance_page_' . $this->theme->slug . '-options' ) !== $hook ) {
			return;
		}

		wp_enqueue_style( 'blank-panel-style', $this->get_uri( 'admin.css' ), [], $this->version );
		wp_enqueue_script( 'blank-panel-script', $this->get_uri( 'admin.js' ), [ 'jquery' ], $this->version, true );
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
			$this->get_uri( 'gutenberg-editor.css' ),
			$this->google_fonts_url(),
		] );

		/**
		 * Add support for editor font sizes.
		 */
		add_theme_support(
			'editor-font-sizes',
			apply_filters( 'blank_editor_font_sizes_args', [
				[
					'name' => __( 'Small', 'blank' ),
					'size' => 12,
					'slug' => 'small',
				],
				[
					'name' => __( 'Normal', 'blank' ),
					'size' => 14,
					'slug' => 'normal',
				],
				[
					'name' => __( 'Medium', 'blank' ),
					'size' => 20,
					'slug' => 'medium',
				],
				[
					'name' => __( 'Large', 'blank' ),
					'size' => 26,
					'slug' => 'large',
				],
				[
					'name' => __( 'Huge', 'blank' ),
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
				'--background-color'            => $theme->get_option( 'background_color' ),

				'--max-site-width'              => $theme->get_option( 'max_site_width' ),

				'--typography-base-font'        => $theme->get_option( 'typography_base_font' ),
				'--typography-base-color'       => $theme->get_option( 'typography_base_color' ),
				'--typography-link-color'       => $theme->get_option( 'typography_link_color' ),
				'--typography-link-hover-color' => $theme->get_option( 'typography_link_hover_color' ),

				'--typography-heading-font'     => $theme->get_option( 'typography_heading_font' ),
				'--typography-heading-color'    => $theme->get_option( 'typography_heading_color' ),
				'--typography-h1-font'          => $theme->get_option( 'typography_h1_font' ),
				'--typography-h2-font'          => $theme->get_option( 'typography_h2_font' ),
				'--typography-h3-font'          => $theme->get_option( 'typography_h3_font' ),
				'--typography-h4-font'          => $theme->get_option( 'typography_h4_font' ),
				'--typography-h5-font'          => $theme->get_option( 'typography_h5_font' ),
				'--typography-h6-font'          => $theme->get_option( 'typography_h6_font' ),
				'--typography-blockquote-font'  => $theme->get_option( 'typography_blockquote_font' ),
				'--typography-pre-font'         => $theme->get_option( 'typography_pre_font' ),

				'--primary-color'               => $theme->get_option( 'primary_color' ),
				'--secondary-color'             => $theme->get_option( 'secondary_color' ),
				'--info-color'                  => $theme->get_option( 'info_color' ),
				'--success-color'               => $theme->get_option( 'success_color' ),
				'--warning-color'               => $theme->get_option( 'warning_color' ),
				'--danger-color'                => $theme->get_option( 'danger_color' ),
				'--light-color'                 => $theme->get_option( 'light_color' ),
				'--dark-color'                  => $theme->get_option( 'dark_color' ),
			],
		] );
	}

	/**
	 * Custom login hook.
	 *
	 * @return void
	 */
	public function login_head() {
		if ( ! $this->theme->get_option( 'custom_login_logo' ) ) {
			return;
		}

		wp_register_style( 'blank-custom-login', false, [], $this->version );
		wp_add_inline_style( 'blank-custom-login', $this->login_style() );

		wp_enqueue_style( 'blank-custom-login' );
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
		$google_fonts = apply_filters( 'blank_google_font_families', [
			'source-sans-pro' => 'Source+Sans+Pro:400,300,300italic,400italic,600,700,900',
		] );

		$query_args = [
			'family' => implode( '|', $google_fonts ),
			'subset' => rawurlencode( 'latin,latin-ext' ),
		];

		return add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	/**
	 * Get asset file uri.
	 *
	 * @since 0.1.0
	 * @param  string $filename
	 * @return string
	 */
	public function get_uri( string $filename ) : string {
		extract( pathinfo( $filename ) ); // phpcs:ignore WordPress.PHP.DontExtract

		if ( ! in_array( $extension, [ 'js', 'css' ], true ) ) {
			$extension = 'img';
		} else {
			$basename = $filename . ( self::is_debug() ? ".$extension" : ".min.$extension" );
		}

		return $this->theme->get_uri( "assets/$extension/$basename" );
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
		$eol    = self::is_debug() ? PHP_EOL : ' ';

		foreach ( $styles as $selector => $style ) {
			$inline[] = $selector . ' {' . $eol;

			foreach ( $style as $name => $value ) {
				$inline[] = $name . ': ' . $value . ';' . $eol;
			}

			$inline[] = '}' . $eol;
		}

		return $eol . implode( ' ', $inline );
	}

	/**
	 * Determine is it on script-debug mode.
	 *
	 * @since 0.1.1
	 * @return bool
	 * @codeCoverageIgnore
	 */
	public static function is_debug() : bool {
		return defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;
	}
}
