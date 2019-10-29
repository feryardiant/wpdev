<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package  Blank
 * @since    0.2.0
 */

/** @var \Blank\Content $blank_content */
$blank_content = blank( 'content' );

?><article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">

		<?php $blank_content->header(); ?>

	</header> <!-- .entry-header -->

	<div class="entry-content">
		<?php
		the_content( sprintf(
			wp_kses(
				/* translators: %s: Name of current post. Only visible to screen readers */
				__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'blank' ),
				[
					'span' => [
						'class' => [],
					],
				]
			),
			get_the_title()
		) );

		$blank_content->link_pages();
		?>
	</div> <!-- .entry-content -->

	<footer class="entry-footer">

		<?php $blank_content->footer(); ?>

	</footer> <!-- .entry-footer -->

</article> <!-- #post-<?php the_ID(); ?> -->
