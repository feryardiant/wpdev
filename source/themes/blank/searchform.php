<?php
/**
 * The template for search form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package  Blank
 * @since    0.2.0
 */

?><form role="search" method="get" id="searchform" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div class="field has-addons">
		<div class="control is-expanded">
			<label for="search" class="is-sr-only">Search for:</label>
			<input class="input" type="text" value="<?php echo get_search_query(); ?>" placeholder="Search..." name="s" id="search" />
		</div>
		<div class="control">
			<button class="button" type="submit" id="searchsubmit">
				<?php echo esc_attr_x( 'Search', 'search button', 'blank' ); ?>
			</button>
		</div>
	</div>
</form>

