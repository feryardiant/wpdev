<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package  Blank
 * @since    0.2.0
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php do_action( 'blank_skip_link', 'site-content' ); ?>

	<header id="masthead" role="banner" class="site-header" itemtype="https://schema.org/WPHeader" itemscope>

		<div class="container">

			<div class="site-identity" itemprop="headline" itemtype="http://schema.org/Organization">
				<?php the_custom_logo(); ?>
			</div> <!-- .navbar-brand -->

			<nav class="site-navigation" role="navigation" aria-label="dropdown navigation" itemtype="https://schema.org/SiteNavigationElement" itemscope aria-label="Site Navigation">
				<button role="button" class="menu-toggle" aria-controls="menu-primary-menu" aria-label="menu" aria-expanded="false">
					<span class="mobile-menu"></span>
				</button> <!-- .menu-toggle -->

				<?php wp_nav_menu( [ 'theme_location' => 'primary' ] ); ?> <!-- #primary-menu -->
			</nav> <!-- .site-navigation -->

		</div> <!-- .container -->

	</header> <!-- #masthead -->

	<section id="site-content" class="section content">

		<?php do_action( 'blank_before_main' ); ?>
