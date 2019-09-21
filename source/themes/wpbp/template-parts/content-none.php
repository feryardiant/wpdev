<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package    WordPress_Boilerplate
 * @subpackage WPBP_Theme
 * @since      0.1.0
 */

?><section class="no-results not-found">
	<header class="page-header">
		<h1 class="page-title"><?php esc_html_e( 'Nothing Found', 'wpbp' ); ?></h1>
	</header> <!-- .page-header -->

	<div class="page-content">
		<p>
			<?php
			if ( is_home() && current_user_can( 'publish_posts' ) ) {

				printf(
					wp_kses(
						/* translators: 1: link to WP admin new post page. */
						__( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'wpbp' ),
						[
							'a' => [
								'href' => [],
							],
						]
					),
					esc_url( admin_url( 'post-new.php' ) )
				);

			} elseif ( is_search() ) {

				esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'wpbp' );

			} else {

				esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'wpbp' );

			}
			?>
		</p>

		<?php get_search_form(); ?>
	</div> <!-- .page-content -->
</section> <!-- .no-results -->
