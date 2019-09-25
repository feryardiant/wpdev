<?php
/**
 * The template wrapper for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @package    WordPress_Boilerplate
 * @subpackage WPBP_Theme
 * @since      0.1.0
 */

$wpbp_base_template = wpbp()->get_base_template();
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2.0">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php do_action( 'wpbp_skip_link', 'site-content' ); ?>

	<header id="site-header" class="hero">
		<?php get_header( $wpbp_base_template ); ?>
	</header> <!-- #masthead -->

	<section id="site-content" class="section">
		<div class="container">
			<div class="columns">
				<main role="main" id="primary" class="column">
					<?php load_template( wpbp()->get_main_template() ); ?>
				</main> <!-- #primary -->

				<aside id="secondary" class="column is-one-third widget-area">
					<?php get_sidebar( $wpbp_base_template ); ?>
				</aside> <!-- #secondary -->
			</div>
		</div>
	</section> <!-- #content -->

	<footer id="site-footer">
		<?php get_footer( $wpbp_base_template ); ?>
	</footer> <!-- #colopon -->

	<?php wp_footer(); ?>
</body>

</html>
