<?php
/**
 * Template part for displaying results in search pages
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

	<div class="entry-summary">

		<?php the_excerpt(); ?>

	</div> <!-- .entry-summary -->

	<footer class="entry-footer">

		<?php $blank_content->footer(); ?>

	</footer> <!-- .entry-footer -->

</article> <!-- #post-<?php the_ID(); ?> -->
