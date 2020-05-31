<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package  Blank
 * @since    0.2.0
 */

get_header();

do_action( 'blank_before_content', [ 'site-main' ] );

get_template_part( 'templates/content', 'none' );

do_action( 'blank_after_content' );

get_sidebar();

get_footer();
