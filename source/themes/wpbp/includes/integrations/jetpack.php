<?php
/**
 * Jetpack Integration File.
 *
 * @link https://jetpack.com/
 *
 * @package    WordPress_Boilerplate
 * @subpackage WPBP_Theme
 * @since      0.1.0
 */

namespace WPBP\Integrations;

/**
 * JetPack Setup Class.
 *
 * @subpackage  JetPack Setup
 */
class JetPack {
	/**
	 * Initialize class.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {
		add_action( 'after_setup_theme', [ $this, 'setup' ] );
	}

	/**
	 * Jetpack setup function.
	 *
	 * @see https://jetpack.com/support/infinite-scroll/
	 * @see https://jetpack.com/support/responsive-videos/
	 * @see https://jetpack.com/support/content-options/
	 *
	 * @since 0.1.0
	 */
	public function setup() {
		// Add theme support for Infinite Scroll.
		add_theme_support( 'infinite-scroll', [
			'container' => 'main',
			'render'    => [ $this, 'infinite_scroll_render' ],
			'footer'    => 'page',
		] );

		// Add theme support for Responsive Videos.
		add_theme_support( 'jetpack-responsive-videos' );

		// Add theme support for Content Options.
		add_theme_support( 'jetpack-content-options', [
			'post-details'    => [
				'stylesheet' => 'wpbp-style',
				'date'       => '.posted-on',
				'categories' => '.cat-links',
				'tags'       => '.tags-links',
				'author'     => '.byline',
				'comment'    => '.comments-link',
			],
			'featured-images' => [
				'archive'    => true,
				'post'       => true,
				'page'       => true,
			],
		] );
	}

	/**
	 * Custom render function for Infinite Scroll.
	 *
	 * @since 0.1.0
	 */
	public function infinite_scroll_render() {
		while ( have_posts() ) {
			the_post();

			if ( is_search() ) {
				get_template_part( 'template-parts/content', 'search' );
			} else {
				get_template_part( 'template-parts/content', get_post_type() );
			}
		}
	}
}

