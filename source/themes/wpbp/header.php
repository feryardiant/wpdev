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
			<?php
			wp_nav_menu( [
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'navbar-start',
				'items_wrap'     => '<div id="%1$s" class="%2$s">%3$s</div>',
				'after'          => '</div>',
				'walker'         => $walker = new WPBP\Walker_Nav_Menu(),
				'fallback_cb'    => [ $walker, 'fallback' ]
			] );
			?>
		</div>
	</nav> <!-- .navbar -->

</div>
