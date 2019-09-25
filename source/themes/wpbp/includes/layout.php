<?php
/**
 * WPBP Theme.
 *
 * @package    WordPress_Boilerplate
 * @subpackage WPBP_Theme
 * @since      0.1.1
 */

namespace WPBP;

/**
 * Theme Layout Class.
 *
 * @category  Theme Layout
 */
class Layout extends Feature {
	/**
	 * Initialize class.
	 *
	 * @since 0.1.1
	 */
	protected function initialize() : void {
		add_action( 'wpbp_hero_body', [ $this, 'hero_body' ], 10 );
		add_action( 'wpbp_main_content_before', [ $this, 'main_content_before' ], 10 );
		add_action( 'wpbp_main_content_after', [ $this, 'main_content_after' ], 10, 0 );
	}

	/**
	 * Print element before main-content area.
	 *
	 * @since 0.1.1
	 * @return void
	 */
	public function hero_body() {
		$title = get_the_title();
		$subtitle = '';

		if ( is_search() ) {
			$title = sprintf(
				esc_html__( 'Search Results for: %s', 'wpbp' ),
				'<span>' . get_search_query() . '</span>'
			);
		} elseif ( is_404() ) {
			$title = esc_html__( 'Oops! That page can&rsquo;t be found.', 'wpbp' );
		} elseif ( is_archive() ) {
			$title = get_the_archive_title();
			$subtitle = get_the_archive_description();
		}

		if ( $title ) {
			echo '<h1 class="title screen-reader-text">' . esc_html( $title ) . '</h1>';
		}

		if ( $subtitle ) {
			echo esc_html( $subtitle );
		}
	}

	/**
	 * Print element before main-content area.
	 *
	 * @since 0.1.1
	 * @param  array $classes
	 * @return void
	 */
	public function main_content_before( array $classes = [] ) {
		$classes = (array) apply_filters(
			'wpbp_main_content_class',
			! is_array( $classes ) ? explode( ' ', $classes ) : $classes
		);

		echo '<section id="main" class="' . esc_attr( join( ' ', $classes ) ) . '">';
	}

	/**
	 * Print element after main-content area.
	 *
	 * @since 0.1.1
	 * @return void
	 */
	public function main_content_after() {
		echo '</section> <!-- #main.site-main -->';
	}
}
