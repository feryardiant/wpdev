<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package  Blank
 * @since    0.2.0
 */

?><article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header is-clearfix">

		<?php blank( 'content' )->header(); ?>

	</header> <!-- .entry-header -->

	<div class="entry-content is-clearfix">
		<?php
		the_content();

		blank( 'content' )->link_pages();
		?>
	</div> <!-- .entry-content -->

	<footer class="entry-footer is-clearfix">

		<?php blank( 'content' )->footer(); ?>

	</footer> <!-- .entry-footer -->

</article> <!-- #post-<?php the_ID(); ?> -->
