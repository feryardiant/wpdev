<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package  Blank
 * @since    0.2.0
 */

if ( ! is_active_sidebar( 'main-sidebar' ) ) {
	return;
}
?>
<aside id="secondary" class="column is-one-third widget-area">

	<?php dynamic_sidebar( 'main-sidebar' ); ?>

</aside> <!-- #secondary -->
