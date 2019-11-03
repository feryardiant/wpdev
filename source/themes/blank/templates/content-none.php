<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package  Blank
 * @since    0.2.0
 */

?><section <?php post_class( 'content no-results' ); ?>>

	<div class="entry-content">

		<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'blank' ); ?></p>

		<?php get_search_form(); ?>

	</div> <!-- .entry-content -->

	<footer class="entry-footer">

		<?php blank( 'content' )->footer(); ?>

	</footer> <!-- .entry-footer -->

</section> <!-- .no-results -->
