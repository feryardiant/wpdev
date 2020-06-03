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
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	</header>

	<nav>
		<?php blank( 'options' )->get_navigations(); ?>
	</nav>

	<form action="#" method="post">
		<?php blank( 'options' )->get_sections(); ?>
	</form> <!-- /form -->
</div> <!-- .wrap -->
