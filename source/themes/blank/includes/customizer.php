<?php
/**
 * WordPress Customizer Integration File.
 *
 * @package  Blank
 * @since    0.2.0
 */

namespace Blank;

use WP_Customize_Manager as Manager;

/**
 * WordPress Customizer Setup Class.
 *
 * @category  WordPress Customizer Setup
 */
class Customizer extends Feature {
	/**
	 * Control class aliases.
	 *
	 * @var array
	 */
	private $control_aliases = [
		'color'  => \WP_Customize_Color_Control::class,
		'image'  => \WP_Customize_Image_Control::class,
		'media'  => \WP_Customize_Media_Control::class,
		'upload' => \WP_Customize_Upload_Control::class,
	];

	/**
	 * Initialize class.
	 *
	 * @since 0.1.1
	 */
	protected function initialize() : void {
		add_action( 'customize_register', [ $this, 'register' ] );
		add_action( 'customize_preview_init', [ $this, 'preview_init' ] );
	}

	/**
	 * Register customizer settings.
	 *
	 * @since 0.1.0
	 * @param WP_Customize_Manager $customizer
	 */
	public function register( Manager $customizer ) {
		$customizer->get_setting( 'blogname' )->transport         = 'postMessage';
		$customizer->get_setting( 'blogdescription' )->transport  = 'postMessage';
		$customizer->get_setting( 'header_textcolor' )->transport = 'postMessage';

		if ( isset( $customizer->selective_refresh ) ) {
			$customizer->selective_refresh->add_partial( 'blogname', [
				'selector'        => '.site-title',
				'render_callback' => [ $this->theme->template, 'site_name' ],
			] );

			$customizer->selective_refresh->add_partial( 'blogdescription', [
				'selector'        => '.site-description',
				'render_callback' => [ $this->theme->template, 'site_slogan' ],
			] );
		}

		$callback = function () {
			// .
		};

		foreach ( $this->theme->option->items() as $position => $values ) {
			if ( in_array( $position, [ 'panels', 'sections' ], true ) ) {
				$method = 'panels' === $position ? 'add_panel' : 'add_section';

				foreach ( $values as $key => $value ) {
					if ( 'sections' === $position ) {
						$value['panel'] = $this->add_prefix( $value['panel'] );
					}

					call_user_func( [ $customizer, $method ], $this->add_prefix( $key ), $value );
				}
			} else {
				foreach ( $values as $key => $value ) {
					$key = $this->add_prefix( $key );
					$customizer->add_setting( $key, [
						'default'   => $value['default'],
						'transport' => 'postMessage',
					] );

					unset( $value['default'] );
					$type     = $value['type'];
					$selector = null;

					if ( isset( $value['selector'] ) ) {
						$selector = $value['selector'];
						$callback = $value['render_callback'] ?? $callback;
						unset( $value['selector'], $value['render_callback'] );
					}

					if ( $this->theme->option->has_section( $value['section'] ) ) {
						$value['section'] = $this->add_prefix( $value['section'] );
					}

					if ( array_key_exists( $type, $this->control_aliases ) ) {
						$control_class = $this->control_aliases[ $type ];

						$customizer->add_control(
							new $control_class( $customizer, $key, $value )
						);
					} else {
						$customizer->add_control( $key, $value );
					}

					if ( null !== $selector ) {
						$customizer->selective_refresh->add_partial( $key, [
							'selector'        => $selector,
							'render_callback' => $callback,
						] );
					}
				}
			}
		}
	}

	/**
	 * Add theme prefix.
	 *
	 * @param string $name
	 * @return string
	 */
	protected function add_prefix( string $name ) : string {
		return $this->theme->slug . '[' . $name . ']';
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
		wp_enqueue_script(
			'blank-customizer-script',
			$this->theme->asset->get_uri( 'customizer.js' ),
			[ 'customize-preview' ],
			$this->theme->version,
			true
		);
	}
}
