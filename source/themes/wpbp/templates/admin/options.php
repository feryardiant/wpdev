<?php
/**
 * Theme option template.
 *
 * @package    WordPress_Boilerplate
 * @subpackage WPBP_Theme
 * @since      0.1.1
 */

global $wpbp_theme;

?><div class="wrap" id="wpbp-panel">
	<header>
		<h1>
			<?php
			// translators: %s Theme Name.
			printf( esc_html__( '%s Options', 'wpbp' ), esc_html( $wpbp_theme->name ) );
			?>
		</h1>
	</header>

	<nav>
		<ul>
			<li>
				<a href="#panel-section-general">General</a>
			</li>
			<li>
				<a href="#panel-section-welcome">Welcome</a>
			</li>
		</ul>
	</nav>

	<form action="#" method="post">
		<main id="panel-sections">
			<section id="panel-section-general">
				<p>Panel 1</p>
			</section>
			<section id="panel-section-welcome">
				<p>Panel 2</p>
			</section>
		</main> <!-- #poststuff -->
	</form> <!-- #roles -->
</div> <!-- .wrap -->
