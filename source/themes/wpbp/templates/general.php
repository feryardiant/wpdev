<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package    WordPress_Boilerplate
 * @subpackage WPBP_Theme
 * @since      0.1.0
 */

do_action( 'wpbp_main_content_before', [ 'site-main' ] );

if ( have_posts() ) {

	/* Start the Loop */
	while ( have_posts() ) {
		the_post();

		/*
			* Include the Post-Type-specific template for the content.
			* If you want to override this in a child theme, then include a file
			* called content-___.php (where ___ is the Post Type name) and that will be used instead.
			*/
		get_template_part( 'templates/content', get_post_type() );
	}

	if ( ! is_front_page() && ( comments_open() || get_comments_number() ) ) {
		comments_template();
	}
} else {

	get_template_part( 'templates/content', 'none' );

}

do_action( 'wpbp_main_content_after' );
