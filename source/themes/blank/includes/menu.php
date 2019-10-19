<?php
/**
 * Blank Theme.
 *
 * @package  Blank
 * @since    0.2.0
 */

namespace Blank;

/**
 * Theme Style Class.
 *
 * @category  Theme Style
 */
class Menu extends Feature {
	/**
	 * Initialize class.
	 *
	 * @since 0.1.1
	 */
	protected function initialize() : void {
		add_action( 'after_setup_theme', [ $this, 'setup' ] );

		add_filter( 'wp_nav_menu_args', [ $this, 'nav_menu_args' ], 10 );
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
		 * Register menu locations.
		 */
		register_nav_menus(
			apply_filters( 'blank_register_nav_menus_args', [
				'primary' => __( 'Primary Menu', 'blank' ),
				'footer'  => __( 'Footer Menu', 'blank' ),
			] )
		);
	}

	/**
	 * Customize nav menu arguments.
	 *
	 * @internal
	 * @since 0.1.1
	 * @param  array $args
	 * @return array
	 */
	public function nav_menu_args( $args ) {
		$walker = new Walker\Nav_Menu();

		$args['container']   = false;
		$args['menu_id']     = false;
		$args['menu_class']  = 'navbar-start';
		$args['items_wrap']  = '<div id="%1$s" class="%2$s">%3$s</div>';
		$args['after']       = '</div>';
		$args['walker']      = $walker;
		$args['fallback_cb'] = [ $walker, 'fallback' ];

		return $args;
	}
}
