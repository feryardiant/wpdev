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
		<?php get_header(); ?>
	</header> <!-- #site-header -->

	<section id="site-content" class="section content">
		<div class="container">
			<div class="columns">
				<main role="main" id="primary" class="<?php $wpbp_theme->content->classes( [ 'column' ] ); ?>">
					<?php load_template( $wpbp_theme->get_template_filename() ); ?>
				</main> <!-- #primary -->

				<?php if ( ! $wpbp_theme->is_template( 'full-width' ) ) : ?>
					<aside id="secondary" class="column is-one-third widget-area">
						<?php get_sidebar(); ?>
					</aside> <!-- #secondary -->
				<?php endif; ?>
			</div>
		</div>
	</section> <!-- #site-content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<?php get_footer(); ?>
	</footer> <!-- #site-footer -->

	<?php wp_footer(); ?>
</body>

</html>
