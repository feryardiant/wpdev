<?php
/**
 * Sample implementation of the Custom Header feature
 *
 * You can add an optional custom header image to header.php like so ...
 *
	<?php the_header_image_tag(); ?>
 *
 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
 *
 * @package Starter_Theme
 */

/**
 * Set up the WordPress core custom header feature.
 *
 * @uses starter_header_style()
 */
function starter_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'starter_custom_header_args', [
		'default-image'       => '',
		'default-text-color'  => '000000',
		'width'               => 1000,
		'height'              => 250,
		'flex-height'         => true,
		'wp-head-callback'    => 'starter_header_style',
	] ) );
}
add_action( 'after_setup_theme', 'starter_custom_header_setup' );

if ( ! function_exists( 'starter_header_style' ) ) {
	/**
	 * Styles the header image and text displayed on the blog.
	 *
	 * @see starter_custom_header_setup().
	 */
	function starter_header_style() {
		$header_text_color = get_header_textcolor();

		/*
		 * If no custom options for text are set, let's bail.
		 * get_header_textcolor() options: Any hex value, 'blank' to hide text. Default: add_theme_support( 'custom-header' ).
		 */
		if ( get_theme_support( 'custom-header', 'default-text-color' ) === $header_text_color ) {
			return;
		}

		$custom_header_style = [ '<style id="custom-header-style" type="text/css">' ];

		if ( ! display_header_text() ) {
			$custom_header_style[] = '.site-title, .site-description { position: absolute; clip: rect(1px, 1px, 1px, 1px); }';
		} else {
			$custom_header_style[] = '.site-title a, .site-description { color: #' . esc_attr( $header_text_color ) . '; }';
		}

		$custom_header_style[] = '</style>';

		echo implode( '', $custom_header_style ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
