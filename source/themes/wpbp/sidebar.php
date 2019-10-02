<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package    WordPress_Boilerplate
 * @subpackage WPBP_Theme
 * @since      0.1.0
 */

?>
<aside id="secondary" class="column is-one-third widget-area">

	<?php WPBP\Widgets::get_active( 'main-sidebar' ); ?>

</aside> <!-- #secondary -->
