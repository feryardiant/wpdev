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
	 * Built-in Controls
	 *
	 * @var array
	 */
	private $controls = [
		Customizer\Basic_Control::class      => [
			'blank-text',
			'blank-number',
			'blank-email',
			'blank-telp',
		],
		Customizer\Dropdown_Control::class   => [
			'blank-dropdown',
		],
		Customizer\Typography_Control::class => [
			'blank-typography',
		],
	];

	/**
	 * Initialize class.
	 *
	 * @since 0.1.1
	 */
	protected function initialize(): void {
		add_action( 'customize_register', [ $this, 'register' ] );
	}

	/**
	 * Register customizer settings.
	 *
	 * @since 0.1.0
	 * @param WP_Customize_Manager $customizer
	 */
	public function register( Manager $customizer ) {
		$this->register_controls( $customizer );

		$this->override_builtins( $customizer );

		$options = $this->theme->options();

		foreach ( $options as $position => $values ) {
			if ( 'values' === $position ) {
				continue;
			}

			if ( ! in_array( $position, [ 'panels', 'sections' ], true ) ) {
				$this->register_control_setting( $customizer, $options, $values );
				continue;
			}

			$method = 'panels' === $position ? 'add_panel' : 'add_section';

			foreach ( $values as $key => $value ) {
				if ( 'sections' === $position && isset( $value['panel'] ) ) {
					$value['panel'] = $this->add_prefix( $value['panel'] );
				}

				call_user_func( [ $customizer, $method ], $this->add_prefix( $key ), $value );
			}
		}
	}

	/**
	 * Register customizer settings.
	 *
	 * @since 0.2.1
	 * @param WP_Customize_Manager $customizer
	 */
	protected function register_controls( Manager $customizer ) {
		foreach ( array_keys( $this->controls ) as $blank_control ) {
			$customizer->register_control_type( $blank_control );
		}
	}

	/**
	 * Register customizer settings.
	 *
	 * @since 0.2.1
	 * @param WP_Customize_Manager $customizer
	 * @param array                $options
	 * @param array                $values
	 */
	protected function register_control_setting( Manager $customizer, array $options, array $values ) {
		$setting_keys = [
			'default',
			'sanitize_callback',
			'transport',
			'capability',
		];

		foreach ( $values as $key => $value ) {
			$key      = $this->add_prefix( $key );
			$selector = null;
			$callback = null;
			$settings = [
				'type'       => 'theme_mod',
				'transport'  => 'auto',
				'capability' => 'edit_theme_options',
			];

			foreach ( $setting_keys as $setting_key ) {
				if ( isset( $value[ $setting_key ] ) ) {
					$settings[ $setting_key ] = $value[ $setting_key ];
					unset( $value[ $setting_key ] );
				}
			}

			$customizer->add_setting( $key, $settings );

			if ( isset( $value['selector'] ) ) {
				$selector = $value['selector'];
				$callback = $value['render_callback'] ?? $callback;
				unset( $value['selector'], $value['render_callback'] );
			}

			if ( array_key_exists( $value['section'], $options['sections'] ) ) {
				$value['section'] = $this->add_prefix( $value['section'] );
			}

			$control_class = $this->get_control_alias( $value['type'] );

			if ( $control_class ) {
				$customizer->add_control(
					new $control_class( $customizer, $key, $value )
				);
			} else {
				$customizer->add_control( $key, $value );
			}

			if ( null !== $selector ) {
				$customizer->selective_refresh->add_partial(
					$key,
					[
						'selector'        => $selector,
						'render_callback' => $callback,
					]
				);
			}
		}
	}

	/**
	 * Register customizer settings.
	 *
	 * @since 0.2.1
	 * @param WP_Customize_Manager $customizer
	 */
	protected function override_builtins( Manager $customizer ) {
		$customizer->get_setting( 'blogname' )->transport         = 'postMessage';
		$customizer->get_setting( 'blogdescription' )->transport  = 'postMessage';
		$customizer->get_setting( 'header_textcolor' )->transport = 'postMessage';

		if ( isset( $customizer->selective_refresh ) ) {
			$customizer->selective_refresh->add_partial(
				'blogname',
				[
					'selector'        => '.site-title',
					'render_callback' => [ $this->theme->template, 'site_name' ],
				]
			);

			$customizer->selective_refresh->add_partial(
				'blogdescription',
				[
					'selector'        => '.site-description',
					'render_callback' => [ $this->theme->template, 'site_slogan' ],
				]
			);
		}
	}

	/**
	 * Get control class from alias.
	 *
	 * @param string $type
	 * @return string|null
	 */
	protected function get_control_alias( string $type ): ?string {
		if ( array_key_exists( $type, $this->control_aliases ) ) {
			return $this->control_aliases[ $type ];
		}

		$controls = array_keys(
			array_filter(
				$this->controls,
				function ( $control ) use ( $type ) {
					return in_array( $type, $control, true );
				}
			)
		);

		if ( 1 === count( $controls ) ) {
			return $controls[0];
		}

		return null;
	}

	/**
	 * Add theme prefix.
	 *
	 * @param string $name
	 * @return string
	 */
	protected function add_prefix( string $name ): string {
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
}
