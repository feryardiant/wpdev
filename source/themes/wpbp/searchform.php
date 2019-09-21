<?php
/**
 * The template for search form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package    WordPress_Boilerplate
 * @subpackage WPBP_Theme
 * @since      0.1.0
 */

?><form role="search" method="get" id="searchform" class="searchform" action="' . esc_url( home_url( '/' ) ) . '">
	<div class="field has-addons">
		<div class="control">
			<label for="search" class="is-sr-only">Search for:</label>
			<input class="input" type="text" value="<?php echo get_search_query() ?>" placeholder="Search..." name="s" id="search" />
		</div>
		<div class="control">
			<button class="button" type="submit" id="searchsubmit">
				<?php echo esc_attr_x( 'Search', 'submit button' ) ?>
			</button>
		</div>
	</div>
</form>