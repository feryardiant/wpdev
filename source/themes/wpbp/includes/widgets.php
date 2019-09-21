<?php
/**
 * WPBP Theme.
 *
 * @package     WordPress_Boilerplate
 * @subpackage  WPBP_Theme
 * @since       0.1.0
 */

namespace WPBP;

/**
 * Theme Widget Class.
 *
 * @category  Widget
 */
class Widgets {
	/**
	 * Initialize class.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {
		add_action( 'widgets_init', [ $this, 'init' ] );
	}

	/**
	 * Register widget area.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
	 *
	 * @internal
	 * @since 0.1.0
	 * @return void
	 */
	public function init() {
		register_sidebar( [
			'name'          => esc_html__( 'Main Sidebar', 'wpbp' ),
			'id'            => 'main-sidebar',
			'description'   => esc_html__( 'Main sidebar that placed on the side of your page.', 'wpbp' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section> <!-- #%1$s -->',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		] );

		register_sidebar( [
			'name'          => esc_html__( 'Footer Widgets', 'wpbp' ),
			'id'            => 'footer-widgets',
			'description'   => esc_html__( 'Footer widget that placed on the bottom of your page.', 'wpbp' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section> <!-- #%1$s -->',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		] );
	}

	/**
	 * Get dynamyc sidebar.
	 *
	 * @since 0.1.1
	 * @param  int|string $index
	 * @return void
	 */
	public static function get_active( $index ) {
		if ( ! is_active_sidebar( $index ) ) {
			return;
		}

		dynamic_sidebar( $index );
	}
}
