<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package  Blank
 * @since    0.2.0
 */

do_action( 'blank_after_main' );
?>
	</section> <!-- .site-content -->

	<footer <?php blank( 'template' )->footer_attr( [ 'id' => 'colopon' ] ); ?>>

		<?php blank( 'template' )->footer(); ?>

	</footer> <!-- .site-footer -->

	<?php wp_footer(); ?>
</body>

</html>
