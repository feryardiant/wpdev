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
<div class="hero-head">
	<div class="container">

		<nav class="navbar is-transparent" role="navigation" aria-label="dropdown navigation">
			<div class="navbar-brand">
				<a class="navbar-item" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<?php the_custom_logo(); ?>
					<h1 class="site-title"><?php bloginfo( 'name' ); ?></h1> <!-- .site-title -->

					<?php $wpbp_description = get_bloginfo( 'description', 'display' ); ?>
					<?php if ( $wpbp_description || is_customize_preview() ) : ?>
						<p class="site-description"><?php echo esc_html( $wpbp_description ); ?></p>
					<?php endif; ?>
				</a> <!-- .navbar-item -->
			</div> <!-- .navbar-brand -->

			<a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navbar-menu-primary">
				<span aria-hidden="true"></span><span aria-hidden="true"></span><span aria-hidden="true"></span>
			</a>

			<div id="navbar-menu-primary" class="navbar-menu">
				<?php
				wp_nav_menu( [
					'theme_location' => 'primary',
				] );
				?>
			</div>
		</nav> <!-- .navbar -->

	</div>
</div>

<div class="hero-body">
	<div class="container">
		<?php
		if ( is_home() && ! is_front_page() ) {
			single_post_title( '<h1 class="title screen-reader-text">', '</h1>' );
		} elseif ( is_search() ) {
			echo '<h1 class="title">';
			/* translators: %s: search query. */
			printf( esc_html__( 'Search Results for: %s', 'wpbp' ), '<span>' . get_search_query() . '</span>' );
			echo '</h1>';
		} elseif ( is_singular() ) {
			the_title( '<h1 class="title">', '</h1>' );
		} elseif ( is_404() ) {
			echo '<h1 class="page-title">';
			esc_html_e( 'Oops! That page can&rsquo;t be found.', 'wpbp' );
			echo '</h1>';
		} else {
			the_archive_title( '<h1 class="title">', '</h1>' );
			the_archive_description( '<h2 class="subtitle">', '</h2>' );
		}
		?>
	</div>
</div>
