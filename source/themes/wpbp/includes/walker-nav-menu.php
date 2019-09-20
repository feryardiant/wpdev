<?php
/**
 * WPBP Theme.
 *
 * @package     WordPress_Boilerplate
 * @subpackage  WPBP_Theme
 * @since       0.1.0
 */

namespace WPBP;

/**
 * Theme Style Class.
 *
 * @subpackage  Theme Style
 */
class Walker_Nav_Menu extends \Walker_Nav_Menu {
	/**
	 * Initialize class.
	 */
	public function __construct() {
		add_filter('wp_nav_menu_items', function ( $item, $args ) {
			var_dump($item);
			var_dump($args);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function start_lvl( &$output, $depth = 0, $args = [] ) {
		list( $indent, $eol ) = $this->get_indentation( $args, $depth );

		$classes    = apply_filters( 'nav_menu_submenu_css_class', [ 'navbar-dropdown' ], $args, $depth );
		$attributes = ( ! empty( $classes ) ) ? 'class="' . esc_attr( join( ' ', $classes ) ) . '"' : '';

		$output .= "{$eol}{$indent}<div $attributes>{$eol}";
	}

	/**
	 * @inheritDoc
	 */
	public function end_lvl( &$output, $depth = 0, $args = [] ) {
		list( $indent, $eol ) = $this->get_indentation( $args, $depth );

		$output .= "{$eol}{$indent}</div>{$eol}";
	}

	/**
	 * @inheritDoc
	 */
	public function start_el( &$output, $item, $depth = 0, $args = [], $id = 0 ) {
		list( $indent, $eol ) = $this->get_indentation( $args, $depth );

		$title = $item->title ?: $item->post_title;

		if ( $title === '---' ) {
			$output .= '<hr class="navbar-divider">';
			return;
		}

		$item->classes   = empty( $item->classes ) ? [] : (array) $item->classes;
		$item->classes[] = 'navbar-item menu-item-' . $item->ID;
		$has_children    = $args['walker']->has_children;

		if ( $has_children ) {
			$item->classes[] = 'has-dropdown is-hoverable';
		}

		$args      = apply_filters( 'nav_menu_item_args', $args, $item, $depth );
		$classes   = apply_filters( 'nav_menu_css_class', array_filter( $item->classes ), $item, $args, $depth );
		$item_atts = 'id="' . esc_attr( 'menu-item-' . $item->ID ) . '"';

		if ( ! empty( $classes ) ) {
			$item_atts .= ' class="' . esc_attr( join( ' ', $classes ) ) . '"';
		}

		$id   = $id ? 'id="' . esc_attr( 'menu-item-' . $item->ID ) . '"' : '';
		$href = 'href="' . esc_url( $item->url ) . '"';

		if ( $has_children ) {
			$output .= $eol . $indent . '<div ' . $item_atts . '>';
			$output .= '<a class="navbar-link" ' . $href . '>' . $title . '</a>';

			$item->classes[] = 'has-children';
		} else {
			$output .= $eol . $indent . '<a ' . $item_atts . ' ' . $href . '>' . $title;
		}
	}

	/**
	 * @inheritDoc
	 */
	public function end_el( &$output, $item, $depth = 0, $args = [] ) {
		$eol = $this->get_indentation( $args, $depth )[ 1 ];
		$tag = in_array( 'has-children', $item->classes ) ? 'div' : 'a';

		$output .= "</{$tag}>{$eol}";
	}

	/**
	 * Get indentation and EOL.
	 *
	 * @param  stdClass $args
	 * @param  int      $depth
	 * @return array
	 */
	protected function get_indentation( $args, int $depth = 0 ) {
		$is_discarded = isset( $args->item_spacing ) && 'discard' === $args->item_spacing;

		$tab = $is_discarded ? '' : "\t";
		$eol = $is_discarded ? '' : "\n";

		$indent = $depth > 0 ? str_repeat( $tab, $depth ) : $tab;

		return [ $indent, $eol ]; 
	}
}