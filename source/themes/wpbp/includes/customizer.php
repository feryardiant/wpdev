<?php
/**
 * WordPress Customizer Integration File.
 *
 * @package    WordPress_Boilerplate
 * @subpackage WPBP_Theme
 * @since      0.1.0
 */

namespace WPBP;

use WP_Customize_Color_Control;
use WP_Customize_Manager;

/**
 * WordPress Customizer Setup Class.
 *
 * @category  WordPress Customizer Setup
 */
class Customizer extends Feature {
	/**
	 * Theme instance.
	 *
	 * @var Theme
	 */
	protected $theme;

	/**
	 * Theme slug name.
	 *
	 * @var string
	 */
	protected $theme_slug;

	/**
	 * Initialize class.
	 *
	 * @since 0.1.0
	 * @param Theme $theme
	 */
	public function __construct( Theme $theme ) {
		parent::__construct( $theme );
		$this->theme_slug = $theme->info( 'child_slug' );

		add_action( 'customize_register', [ $this, 'register' ] );
		add_action( 'customize_preview_init', [ $this, 'preview_init' ] );
	}

	/**
	 * Register customizer settings.
	 *
	 * @since 0.1.0
	 * @param WP_Customize_Manager $customizer
	 */
	public function register( WP_Customize_Manager $customizer ) {
		$this->site_identity( $customizer );

		$this->colors( $customizer );
	}

	/**
	 * Customize Site Identity Section.
	 *
	 * @param  WP_Customize_Manager $customizer
	 * @return void
	 */
	protected function site_identity( WP_Customize_Manager $customizer ) {
		$customizer->get_setting( 'blogname' )->transport         = 'postMessage';
		$customizer->get_setting( 'blogdescription' )->transport  = 'postMessage';
		$customizer->get_setting( 'header_textcolor' )->transport = 'postMessage';

		if ( isset( $customizer->selective_refresh ) ) {
			$customizer->selective_refresh->add_partial( 'blogname', [
				'selector'        => '.site-title a',
				'render_callback' => apply_filters( 'wpbp_customize_site_title_render_callback', function () {
					bloginfo( 'name' );
				} ),
			] );

			$customizer->selective_refresh->add_partial( 'blogdescription', [
				'selector'        => '.site-description',
				'render_callback' => apply_filters( 'wpbp_customize_site_description_render_callback', function () {
					bloginfo( 'description' );
				} ),
			] );

			$customizer->add_setting( $this->theme_slug . '[wpbp_site_logo_display]', [
				'default'           => 'text_only',
				'type'              => 'option',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'accelerate_radio_select_sanitize',
			] );

			$customizer->add_control( $this->theme_slug . '[wpbp_site_logo_display]', [
				'type'    => 'radio',
				'label'   => __( 'Choose the option that you want.', 'wpbp' ),
				'section' => 'title_tagline',
				'choices' => [
					'logo_only' => __( 'Logo Image Only', 'wpbp' ),
					'text_only' => __( 'Logo Text Only', 'wpbp' ),
					'both'      => __( 'Show Both', 'wpbp' ),
					'none'      => __( 'Disable', 'wpbp' ),
				],
			] );
		}
	}

	/**
	 * Customize Colors Section.
	 *
	 * @param  WP_Customize_Manager $customizer
	 * @return void
	 */
	protected function colors( WP_Customize_Manager $customizer ) {
		$colors = [
			'link_color' => [
				'label'   => __( 'Link Color', 'wpbp' ),
				'default' => '#007bff',
			],
			'link_active_color' => [
				'label'   => __( 'Link Active Color', 'wpbp' ),
				'default' => '#007bff',
			],
			'link_hover_color' => [
				'label'   => __( 'Link Hover Color', 'wpbp' ),
				'default' => '#007bff',
			],
			'text_color' => [
				'label'   => __( 'Text Color', 'wpbp' ),
				'default' => '#343a40',
			],
			'paragraph_color' => [
				'label'   => __( 'Paragraph Color', 'wpbp' ),
				'default' => '#343a40',
			],
			'heading_color' => [
				'label'   => __( 'Heading Color', 'wpbp' ),
				'default' => '#343a40',
			],
		];

		foreach ( $colors as $name => $value ) {
			$customizer->add_setting( 'wpbp[custom_' . $name . ']', [
				'default'    => $value['default'] ?? '',
				'type'       => 'theme_mod',
				'capability' => 'edit_theme_options',
				'transport'  => 'postMessage',
			] );

			$customizer->add_control( new WP_Customize_Color_Control( $customizer, 'wpbp[custom_' . $name . ']', [
				'label'    => $value['label'],
				'settings' => 'wpbp[custom_' . $name . ']',
				'priority' => 10,
				'section'  => 'colors',
			] ) );
		}

	}

	/**
	 * Customize Colors Section.
	 *
	 * @param  string $input
	 * @param  mixed  $setting
	 * @return string
	 */
	public function sanitize_radio_select( $input, $setting ) {
		$input   = sanitize_key( $input );
		$choices = $setting->manager->get_control( $setting->ID )->choices;

		return array_key_exists( $input, $choices ) ? $input : $setting->default;
	}

	/**
	 * Load customizer scripts.
	 *
	 * @since 0.1.0
	 */
	public function preview_init() {
		$theme_version = $this->theme->info( 'version' );

		wp_enqueue_script( 'wpbp-customizer-script', $this->theme->assets_url( 'customizer.js' ), [ 'customize-preview' ], $theme_version, true );
	}
}
