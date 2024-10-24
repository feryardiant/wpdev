<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package  Blank
 * @since    0.2.0
 */

?><article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">

		<?php blank( 'content' )->header(); ?>

	</header> <!-- .entry-header -->

	<div class="entry-content">
		<?php
		the_content(
			sprintf(
				wp_kses(
				/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'blank' ),
					[
						'span' => [ 'class' => true ],
					]
				),
				get_the_title()
			)
		);
		?>

		<?php blank( 'content' )->link_pages(); ?>
	</div> <!-- .entry-content -->

	<footer class="entry-footer">

		<?php blank( 'content' )->footer(); ?>

	</footer> <!-- .entry-footer -->

</article> <!-- #post-<?php the_ID(); ?> -->
