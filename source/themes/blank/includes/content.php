<?php
/**
 * Blank Theme.
 *
 * @package  Blank
 * @since    0.2.0
 */

namespace Blank;

/**
 * Theme Content Class.
 *
 * @category  Theme Content
 */
class Content extends Feature {
	/**
	 * Initialize class.
	 *
	 * @since 0.1.1
	 */
	protected function initialize() : void {
		add_action( 'after_setup_theme', [ $this, 'setup' ] );
		add_filter( 'content_width', [ $this, 'content_width' ] );
		add_filter( 'image_size_names_choose', [ $this, 'image_size_names_choose' ] );
	}

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * @link https://developer.wordpress.org/reference/functions/add_theme_support/
	 *
	 * @internal
	 * @since 0.2.0
	 * @return void
	 */
	public function setup() : void {
		add_image_size( 'post_size', 600, null );
		add_image_size( 'max_size', 1600, 900, true );
	}

	/**
	 * Custom content-width.
	 *
	 * @link https://codex.wordpress.org/Content_Width
	 * @since 0.1.1
	 * @return int
	 */
	public function content_width() {
		return 700;
	}

	/**
	 * Customize image sizes name.
	 *
	 * @link https://gist.github.com/wycks/4949242
	 * @since 0.1.1
	 * @param  array $image_sizes
	 * @return array
	 */
	public function image_size_names_choose( array $image_sizes ) {
		$image_sizes['post_size'] = __( 'Post', 'blank' );

		return $image_sizes;
	}

	/**
	 * Custom content-width.
	 *
	 * @link https://codex.wordpress.org/Content_Width
	 * @since 0.1.1
	 * @return \WP_Post|null
	 */
	public function page_query() : ?\WP_Post {
		global $wp_query;

		$query = $wp_query->get_queried_object();

		if ( $query instanceof \WP_Post_Type ) {
			$query = get_page_by_path( $query->rewrite['slug'] );
		}

		return $query;
	}
}
