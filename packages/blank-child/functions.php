<?php
/**
 * Blank Theme functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package  Blank
 * @since    0.1.0
 */

add_action( 'wp_enqueue_scripts', function () {
	$theme = blank();

	wp_enqueue_style( $theme->slug, $theme->get_uri( 'style.css' ), [], $theme->version );
});
