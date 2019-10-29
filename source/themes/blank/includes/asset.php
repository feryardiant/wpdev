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

		wp_enqueue_style( 'blank-customizer', $this->get_uri( 'customizer.css' ), $this->get_styles_dependencies( 'customizer' ), $this->version );
		wp_register_script( 'blank-customizer', $this->get_uri( 'customizer.js' ), $this->get_scripts_dependencies( 'customizer' ), $this->version, true );

		wp_localize_script( 'blank-customizer', 'blank_customizer', $this->get_localize_script( [
			'customizer_url' => admin_url( '/customize.php?autofocus' ),
			'webfonts'       => $this->theme->typography->get_fonts(),
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
		wp_register_style( 'blank-variables', false, [], $this->version );
		wp_add_inline_style( 'blank-variables', $this->css_variables( $this->theme ) );

		$google_fonts = $this->theme->typography->get_google_fonts_url();
		$common_deps  = [
			'blank-variables',
		];

		if ( $google_fonts ) {
			wp_register_style( 'blank-google-fonts', $google_fonts, [], $this->version );

			$common_deps[] = 'blank-google-fonts';
		}

		$deps = apply_filters( 'blank_scripts_dependencies', [
			'theme'      => array_merge( $common_deps, [ 'wp-block-library' ] ),
			'admin'      => $common_deps,
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
				'jquery',
				'wp-i18n',
			],
			'customizer' => [
				'customize-preview',
				'customize-controls',
				'underscore',
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
		$variables = [
			'--background-color'            => $theme->get_option( 'background_color' ),

			'--max-site-width'              => $theme->get_option( 'max_site_width' ),

			'--primary-color'               => $theme->get_option( 'primary_color' ),
			'--secondary-color'             => $theme->get_option( 'secondary_color' ),
			'--info-color'                  => $theme->get_option( 'info_color' ),
			'--success-color'               => $theme->get_option( 'success_color' ),
			'--warning-color'               => $theme->get_option( 'warning_color' ),
			'--danger-color'                => $theme->get_option( 'danger_color' ),
			'--light-color'                 => $theme->get_option( 'light_color' ),
			'--dark-color'                  => $theme->get_option( 'dark_color' ),
		];

		foreach ( $theme->typography->get_options_values() as $option ) {
			$variables[ '--' . $option->name . '_family' ] = "'{$option->family}', {$option->category}";
			$variables[ '--' . $option->name . '_weight' ] = $option->weight;
			$variables[ '--' . $option->name . '_style' ]  = $option->style;
			$variables[ '--' . $option->name . '_size' ]   = $option->size[0] . ' ' . $option->size[1];
			$variables[ '--' . $option->name . '_height' ] = $option->height[0] . ' ' . $option->height[1];
		}

		return self::make_css( [ ':root' => apply_filters( 'blank_css_variables', $variables ) ] );
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
