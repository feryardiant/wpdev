<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package  Blank
 * @since    0.2.0
 */

?><section <?php post_class(); ?>>

	<div class="page-content">

		<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'blank' ); ?></p>

		<?php
		get_search_form();

		the_widget( 'WP_Widget_Recent_Posts' );
		?>

		<div class="widget widget_categories">
			<h2 class="widget-title"><?php esc_html_e( 'Most Used Categories', 'blank' ); ?></h2>
			<ul>
				<?php
				wp_list_categories( [
					'orderby'    => 'count',
					'order'      => 'DESC',
					'show_count' => 1,
					'title_li'   => '',
					'number'     => 10,
				] );
				?>
			</ul>
		</div> <!-- .widget -->

		<?php
		/* translators: %1$s: smiley */
		$blank_archive_content = '<p>' . sprintf( esc_html__( 'Try looking in the monthly archives. %1$s', 'blank' ), convert_smilies( ':)' ) ) . '</p>';
		the_widget( 'WP_Widget_Archives', 'dropdown=1', "after_title=</h2>$blank_archive_content" );

		the_widget( 'WP_Widget_Tag_Cloud' );
		?>

	</div> <!-- .page-content -->

</section> <!-- .no-results -->
