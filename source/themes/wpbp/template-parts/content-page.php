<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package    WordPress_Boilerplate
 * @subpackage WPBP_Theme
 * @since      0.1.0
 */

?><article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<!-- wpbp_post_thumbnail(); -->

	<div class="entry-content">
		<?php
		the_content();

		wp_link_pages( [
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'wpbp' ),
			'after'  => '</div>',
		] );
		?>
	</div> <!-- .entry-content -->

	<footer class="entry-footer">
		<?php
		if ( get_edit_post_link() ) :
			edit_post_link(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__( 'Edit <span class="screen-reader-text">%s</span>', 'wpbp' ),
						[
							'span' => [
								'class' => [],
							],
						]
					),
					get_the_title()
				),
				'<span class="edit-link">',
				'</span>'
			);
		endif;
		?>

		<?php the_posts_navigation(); ?>
	</footer> <!-- .entry-footer -->

</article> <!-- #post-<?php the_ID(); ?> -->

<?php
// If comments are open or we have at least one comment, load up the comment template.
if ( ! is_front_page() && ( comments_open() || get_comments_number() ) ) {
	comments_template();
}
?>
