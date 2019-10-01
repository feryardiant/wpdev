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
 * Theme Option.
 *
 * @category  Theme Option
 */
class Option extends Feature {
	/**
	 * Options items
	 *
	 * @var array
	 */
	private $items = [];

	/**
	 * Initialize class.
	 *
	 * @since 0.1.1
	 */
	protected function initialize() : void {
		add_action( 'after_theme_setup', [ $this, 'initialize_options' ] );
	}

	/**
	 * Get Theme Modification value.
	 *
	 * @since 0.1.1
	 * @return void
	 */
	public function initialize_options() {
		// .
	}

	/**
	 * Add theme option.
	 *
	 * @since 0.1.1
	 * @internal
	 * @param string $name
	 * @param array  $attributes
	 * @param string $parent
	 * @return void
	 */
	public function add( string $name, $attributes = [], string $parent = null ) : void {
		$type     = 'settings';
		$children = [];
		$defaults = [
			'title'       => '',
			'description' => '',
			'priority'    => 25,
		];

		if ( array_key_exists( 'sections', $attributes ) ) {
			$type     = 'panels';
			$children = $attributes['sections'];
			unset( $attributes['sections'] );
			$attributes = wp_parse_args( $attributes, $defaults );
		} elseif ( array_key_exists( 'settings', $attributes ) ) {
			$type     = 'sections';
			$children = $attributes['settings'];
			unset( $attributes['settings'] );
			$defaults['panel'] = $parent;
			$attributes        = wp_parse_args( $attributes, $defaults );
		} else {
			$attributes = wp_parse_args( $attributes, [
				'label'       => '',
				'description' => '',
				'default'     => null,
				'section'     => $parent,
				'type'        => 'text',
			] );

			$this->default_options[ $name ] = $attributes['default'];
		}

		$this->items[ $type ][ $name ] = $attributes;

		foreach ( $children as $key => $value ) {
			$this->add( "{$name}_{$key}", $value, $name );
		}
	}

	/**
	 * Get Theme Modification value.
	 *
	 * @since 0.1.1
	 * @param  string $name
	 * @param  mixed  $default
	 * @return mixed
	 */
	public function get( $name, $default = null ) {
		$theme_mods = get_theme_mods()[ $this->theme->slug ] ?? [];
		$theme_mods = wp_parse_args( $theme_mods, $this->default_options );

		return $theme_mods[ $name ] ?? $default;
	}

	/**
	 * Determine is section exists.
	 *
	 * @param string $name
	 * @return bool
	 */
	public function has_section( string $name ) : bool {
		$sections = array_keys( $this->items['sections'] );

		return in_array( $name, $sections, true );
	}

	/**
	 * Retrieve all options.
	 *
	 * @return array
	 */
	public function items() : array {
		return $this->items;
	}
}
