<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package    WordPress_Boilerplate
 * @subpackage WPBP_Theme
 * @since      0.1.0
 */

?><div class="container">
	<div class="footer-widgets">
		<?php WPBP\Widgets::get_active( 'footer-widgets' ); ?>
	</div>

	<div class="site-info">
		<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'wpbp' ) ); ?>">
			<?php
			/* translators: %s: CMS name, i.e. WordPress. */
			printf( esc_html__( 'Proudly powered by %s', 'wpbp' ), 'WordPress' );
			?>
		</a>
		<span class="sep"> | </span>
			<?php
			/* translators: 1: Theme name, 2: Theme author. */
			printf( esc_html__( 'Theme: %1$s by %2$s.', 'wpbp' ), 'wpbp', '<a href="http://underscores.me/">Fery Wardiyanto</a>' );
			?>
	</div> <!-- .site-info -->
</div>
