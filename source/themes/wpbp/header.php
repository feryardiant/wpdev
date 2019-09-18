<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package     WordPress_Boilerplate
 * @subpackage  WPBP_Theme
 * @since       0.1.0
 */

?>
<div class="site-branding">
	<?php the_custom_logo(); ?>

	<h1 class="site-title">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
	</h1><!-- .site-title -->

	<?php $wpbp_description = get_bloginfo( 'description', 'display' ); ?>
	<?php if ( $wpbp_description || is_customize_preview() ) : ?>
		<p class="site-description"><?php echo esc_html( $wpbp_description ); ?></p>
	<?php endif; ?>
</div><!-- .site-branding -->

<nav id="site-navigation" class="main-navigation">
	<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Primary Menu', 'wpbp' ); ?></button>

	<?php
	wp_nav_menu( [
		'theme_location' => 'primary',
		'menu_id'        => 'primary-menu',
	] );
	?>
</nav><!-- #site-navigation -->
