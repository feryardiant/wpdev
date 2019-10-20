<?php
/**
 * The template for search form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package  Blank
 * @since    0.2.0
 */

?><form role="search" method="get" aria-label="Search form" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<input name="s" class="input" type="text" placeholder="Search..." value="<?php echo get_search_query(); ?>"/>
	<button class="button" type="submit" aria-label="Submit">
		<i class="fa fa-search"></i>
	</button>
</form>

