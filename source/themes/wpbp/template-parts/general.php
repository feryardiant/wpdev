<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package     WordPress_Boilerplate
 * @subpackage  WPBP_Theme
 * @since       0.1.0
 */

if ( have_posts() ) {

	echo '<header class="page-header">';

	if ( is_home() && ! is_front_page() ) {
		single_post_title( '<h1 class="page-title screen-reader-text">', '</h1>' );
	} elseif ( is_search() ) {
		echo '<h1 class="page-title">';
		/* translators: %s: search query. */
		printf( esc_html__( 'Search Results for: %s', 'wpbp' ), '<span>' . get_search_query() . '</span>' );
		echo '</h1>';
	} else {
		the_archive_title( '<h1 class="page-title">', '</h1>' );
		the_archive_description( '<div class="archive-description">', '</div>' );
	}

	echo '</header> <!-- .page-header -->';

	/* Start the Loop */
	while ( have_posts() ) {
		the_post();

		/*
			* Include the Post-Type-specific template for the content.
			* If you want to override this in a child theme, then include a file
			* called content-___.php (where ___ is the Post Type name) and that will be used instead.
			*/
		get_template_part( 'template-parts/content', get_post_type() );
	}

	the_posts_navigation();

} else {

	get_template_part( 'template-parts/content', 'none' );

}
