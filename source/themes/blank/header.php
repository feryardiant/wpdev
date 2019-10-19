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

	<header id="site-header" role="banner" class="hero">

		<div class="hero-head">
			<div class="container">

				<nav class="navbar is-transparent" role="navigation" aria-label="dropdown navigation">
					<div class="navbar-brand"><?php the_custom_logo(); ?></div> <!-- .navbar-brand -->

					<a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navbar-menu-primary">
						<span aria-hidden="true"></span><span aria-hidden="true"></span><span aria-hidden="true"></span>
					</a>

					<div id="navbar-menu-primary" class="navbar-menu">
						<?php wp_nav_menu( [ 'theme_location' => 'primary' ] ); ?>
					</div>
				</nav> <!-- .navbar -->

			</div>
		</div>

		<div class="hero-body">
			<div class="container">
				<?php do_action( 'blank_hero_body' ); ?>
			</div>
		</div>
		<?php get_header(); ?>
	</header> <!-- #site-header -->

	<section id="site-content" class="section content">

		<?php do_action( 'blank_before_main' ); ?>
