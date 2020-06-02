<?php
/**
 * Theme option template.
 *
 * @package    WordPress_Boilerplate
 * @subpackage WPBP_Theme
 * @since      0.1.1
 */

?><div class="wrap" id="blank-panel">
	<header>
		<h1>
			<?php
			// translators: %s Theme Name.
			printf( esc_html__( '%s Options', 'blank' ), esc_html( blank( 'name' ) ) );
			?>
		</h1>
	</header>

	<nav>
		<ul>
			<li>
				<a href="#panel-section-welcome">
					<?php esc_html_e( 'Welcome', 'blank' ); ?>
				</a>
			</li>
			<li>
				<a href="#panel-section-general">
					<?php esc_html_e( 'General', 'blank' ); ?>
				</a>
			</li>
		</ul>
	</nav>

	<form action="#" method="post">
		<main id="panel-sections">
			<section id="panel-section-welcome">
				<p>Panel 2</p>
			</section>
			<section id="panel-section-general">
				<p>Panel 1</p>
			</section>
		</main> <!-- #poststuff -->
	</form> <!-- #roles -->
</div> <!-- .wrap -->
