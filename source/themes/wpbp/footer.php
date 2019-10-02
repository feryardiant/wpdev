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

do_action( 'wpbp_after_main' );
?>
	</section> <!-- #site-content -->

	<footer id="colophon" class="site-footer" role="contentinfo">

		<div class="container">
			<?php do_action( 'wpbp_footer' ); ?>
		</div>

	</footer> <!-- #site-footer -->

<?php wp_footer(); ?>
</body>

</html>
