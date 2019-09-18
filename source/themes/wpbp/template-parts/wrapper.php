<?php
/**
 * The template wrapper for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @package     WordPress_Boilerplate
 * @subpackage  WPBP_Theme
 * @since       0.1.0
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
	<div id="page" class="site">
		<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'wpbp' ); ?></a>

		<header id="masthead" class="site-header">
			<?php get_header( $wpbp_base_template ); ?>
		</header><!-- #masthead -->

		<section id="content" class="site-content">
			<div id="primary" class="content-area">
				<main id="main" class="site-main">
					<?php load_template( wpbp()->get_main_template() ); ?>
				</main><!-- #main -->
			</div><!-- #primary -->

			<aside id="secondary" class="widget-area">
				<?php get_sidebar( $wpbp_base_template ); ?>
			</aside><!-- #secondary -->
		</section><!-- #content -->

		<footer id="colophon" class="site-footer">
			<?php get_footer( $wpbp_base_template ); ?>
		</footer><!-- #colopon -->
	</div><!-- #page -->

	<?php wp_footer(); ?>
</body>

</html>
