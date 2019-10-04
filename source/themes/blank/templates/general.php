<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package    WP Theme Dev
 * @subpackage Blank Theme
 * @since      0.2.0
 */

do_action( 'blank_before_content', [ 'site-main' ] );

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

do_action( 'blank_after_content' );
