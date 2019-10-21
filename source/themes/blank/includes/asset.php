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
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue' ] );
		add_action( 'customize_controls_init', [ $this, 'customizer_enqueue' ] );

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
		wp_enqueue_style( 'blank-theme', $this->get_uri( 'main.css' ), $this->get_styles_dependencies( 'theme' ), $this->version );
		wp_style_add_data( 'blank-theme', 'rtl', 'replace' );

		wp_enqueue_script( 'blank-theme', $this->get_uri( 'main.js' ), $this->get_scripts_dependencies( 'theme' ), $this->version, true );

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

		wp_enqueue_style( 'blank-admin', $this->get_uri( 'admin.css' ), $this->get_styles_dependencies( 'admin' ), $this->version );
		wp_register_script( 'blank-admin', $this->get_uri( 'admin.js' ), $this->get_scripts_dependencies( 'admin' ), $this->version, true );

		wp_localize_script( 'blank-admin', 'blank_admin', $this->get_localize_script() );

		wp_enqueue_script( 'blank-admin' );
	}

	/**
	 * Load customizer scripts.
	 *
	 * @since 0.2.2
	 */
	public function customizer_enqueue() {
		$locale_data = wp_json_encode( $this->theme->get_locale_data( $this->theme->slug ) );
		wp_add_inline_script(
			'wp-i18n',
			'wp.i18n.setLocaleData( ' . $locale_data . ', "' . $this->theme->slug . '" );'
		);

		wp_register_script( 'blank-customizer', $this->get_uri( 'customizer.js' ), $this->get_scripts_dependencies( 'admin' ), $this->version, true );

		wp_localize_script( 'blank-customizer', 'blank_customizer', $this->get_localize_script( [
			'customizer_url' => admin_url( '/customize.php?autofocus' ),
		] ) );

		wp_enqueue_script( 'blank-customizer' );
	}

	/**
	 * Shared object across multiple front-end
	 *
	 * @param  array $merge
	 * @return array
	 */
	protected function get_localize_script( array $merge = [] ) : array {
		return array_merge( [
			'is_debug'   => self::is_debug(),
			'nonce'      => wp_create_nonce( 'blank-ajax-nonce' ),
			'ajax_url'   => admin_url( 'admin-ajax.php' ),
			'assets_url' => $this->theme->get_uri( 'assets' ),
			'rest_url'   => get_rest_url(),
		], $merge );
	}

	/**
	 * Get styles dependencies for each $context.
	 *
	 * @param  string $context
	 * @return array
	 */
	protected function get_styles_dependencies( string $context ) : array {
		wp_register_style( 'blank-google-fonts', $this->google_fonts_url(), [], $this->version );

		wp_register_style( 'blank-variables', false, [], $this->version );
		wp_add_inline_style( 'blank-variables', $this->css_variables( $this->theme ) );

		$deps = apply_filters( 'blank_scripts_dependencies', [
			'theme'      => [
				'blank-google-fonts',
				'blank-variables',
			],
			'admin'      => [
				'blank-google-fonts',
				'blank-variables',
			],
			'customizer' => [],
		], $context );

		return $deps[ $context ] ?? [];
	}

	/**
	 * Get scripts dependencies for each $context.
	 *
	 * @param  string $context
	 * @return array
	 */
	protected function get_scripts_dependencies( string $context ) : array {
		$deps = apply_filters( 'blank_scripts_dependencies', [
			'theme'      => [
				'jquery',
			],
			'admin'      => [
				'wp-i18n',
				'jquery',
			],
			'customizer' => [
				'underscore',
				'wp-color-picker',
				'wp-element',
				'wp-components',
				'wp-date',
				'wp-i18n',
			],
		], $context );

		return $deps[ $context ] ?? [];
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

		$prefix = 'assets/';

		if ( in_array( $extension, [ 'js', 'css' ], true ) ) {
			$basename = $filename . '.' . ( self::is_debug() ? $extension : 'min.' . $extension );
			$prefix  .= $extension . '/';
		} elseif ( in_array( $extension, [ 'jpg', 'jpeg', 'png', 'gif', 'svg', 'ico', 'webp' ], true ) ) {
			$prefix .= 'img/';
		}

		return $this->theme->get_uri( $prefix . $basename );
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
