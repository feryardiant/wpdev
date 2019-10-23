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
	global $blank_theme;

	wp_enqueue_style( $blank_theme->slug, $blank_theme->get_uri( 'style.css' ), [], $blank_theme->version );
});
