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
		add_action( 'wpbp_skip_link', [ $this, 'skip_link' ], 10, 1 );

		add_image_size( 'post_size', 600, null );
		add_image_size( 'max_size', 1600, 900, true );

		add_filter( 'content_width', [ $this, 'content_width' ] );
		add_filter( 'image_size_names_choose', [ $this, 'image_size_names_choose' ] );
	}

	/**
	 * Get main content classes.
	 *
	 * @since 0.1.1
	 * @param  array $classes
	 * @param  bool  $is_return
	 * @return string|void
	 */
	public function classes( array $classes = [], $is_return = false ) {
		if ( ! $this->theme->is_template( 'full-width' ) ) {
			$classes[] = 'is-two-thirds';
		}

		$output = join( ' ', (array) apply_filters( 'wpbp_content_class', $classes ) );

		if ( $is_return ) {
			return $output;
		}

		echo esc_attr( $output );
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
	 * Print the skip-link.
	 *
	 * @since 0.1.1
	 * @param  string $target_id
	 * @return void
	 */
	public function skip_link( string $target_id = 'site-content' ) {
		$text = apply_filters( 'wpbp_skip_link_text', __( 'Skip to content', 'wpbp' ) );

		echo '<a class="skip-link" href="#' . esc_attr( $target_id ) . '">' . esc_html( $text ) . '</a>';
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
		$image_sizes['post_size'] = __( 'Post', 'wpbp' );

		if ( isset( $image_sizes['full'] ) ) {
			unset( $image_sizes['full'] );
		}

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
