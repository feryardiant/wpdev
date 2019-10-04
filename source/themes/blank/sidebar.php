<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package    WP Theme Dev
 * @subpackage Blank Theme
 * @since      0.2.0
 */

?>
<aside id="secondary" class="column is-one-third widget-area">

	<?php Blank\Widgets::get_active( 'main-sidebar' ); ?>

</aside> <!-- #secondary -->
