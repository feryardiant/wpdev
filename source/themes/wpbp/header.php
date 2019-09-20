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

?><div class="container">

	<nav class="navbar is-transparent" role="navigation" aria-label="dropdown navigation">
		<div class="navbar-brand">
			<a class="navbar-item" href="<?php echo esc_url(home_url('/')); ?>" rel="home">
				<?php the_custom_logo(); ?>
				<h1 class="site-title"><?php bloginfo('name'); ?></h1> <!-- .site-title -->

				<?php $wpbp_description = get_bloginfo('description', 'display'); ?>
				<?php if ($wpbp_description || is_customize_preview()) : ?>
					<p class="site-description"><?php echo esc_html($wpbp_description); ?></p>
				<?php endif; ?>
			</a> <!-- .navbar-item -->
		</div> <!-- .navbar-brand -->

		<a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
			<span aria-hidden="true"></span><span aria-hidden="true"></span><span aria-hidden="true"></span>
		</a>

		<div id="navbarBasicExample" class="navbar-menu">
			<!-- <div class="navbar-start">
				<a class="navbar-item">Home</a>

				<a class="navbar-item">Documentation</a>

				<div class="navbar-item has-dropdown is-hoverable">
					<a class="navbar-link">More</a>

					<div class="navbar-dropdown">
						<a class="navbar-item">About</a>
						<a class="navbar-item">Jobs</a>
						<a class="navbar-item">Contact</a>
						<hr class="navbar-divider">
						<a class="navbar-item">Report an issue</a>
					</div>
				</div>
			</div> -->

			<?php
			wp_nav_menu( [
				'theme_location' => 'primary',
				'container'      => '',
				'menu_id'        => 'primary-menu',
				'menu_class'     => 'navbar-menu',
				'after'          => '</div>',
				'walker'         => new WPBP\Walker_Nav_Menu()
			] );
			?>
		</div>
	</nav> <!-- .navbar -->

</div>