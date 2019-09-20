<?php
/**
 * WPBP Theme.
 *
 * @package    WordPress_Boilerplate
 * @subpackage WPBP_Theme
 * @since      0.1.0
 */

namespace WPBP;

/**
 * Theme Style Class.
 *
 * @category Theme Menu
 */
class Walker_Nav_Menu extends \Walker_Nav_Menu {
	/**
	 * Initialize class.
	 */
	public function __construct() {
		// .
	}

	/**
	 * Fallback output if no menu exists.
	 *
	 * @param  array $args
	 * @return void
	 */
	public function fallback( $args ) {
		$item = [];
		$args = is_array( $args ) ? (object) $args : $args;

		if ( current_user_can( 'edit_theme_options' ) ) {
			$href = admin_url( 'nav-menus.php' );

			$item[] = '<a class="navbar-item" href="' . esc_url( $href ) . '">' . esc_html__( 'Add a menu', 'wpbp' ) . '</a>';
		} else {
			$item[] = '<div class="navbar-item">' . esc_html__( 'Primary menu goes here', 'wpbp' ) . '</div>';
		}

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		printf( $args->items_wrap, $args->menu_id, $args->menu_class, join( '', $item ) );
		// phpcs:enable
	}

	/**
	 * Start Menu Level.
	 *
	 * @internal
	 * @since 0.1.1
	 * @param  string $output
	 * @param  int    $depth
	 * @param  array  $args
	 */
	public function start_lvl( &$output, $depth = 0, $args = [] ) {
		list( $indent, $eol ) = $this->get_indentation( $args, $depth );

		$classes = [ 'navbar-dropdown is-boxed' ];

		if ( $depth >= 1 ) {
			$classes[] = 'submenu-depth-' . $depth;
		}

		// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
		$classes = apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth );
		// phpcs:enable

		$attributes = ( ! empty( $classes ) ) ? 'class="' . esc_attr( join( ' ', $classes ) ) . '"' : '';

		$output .= "{$eol}{$indent}<div $attributes>{$eol}";
	}

	/**
	 * End Menu Level.
	 *
	 * @internal
	 * @since 0.1.1
	 * @param  string $output
	 * @param  int    $depth
	 * @param  array  $args
	 */
	public function end_lvl( &$output, $depth = 0, $args = [] ) {
		list( $indent, $eol ) = $this->get_indentation( $args, $depth );

		$output .= "{$eol}{$indent}</div>{$eol}";
	}

	/**
	 * Start Menu Eleent.
	 *
	 * @internal
	 * @since 0.1.1
	 * @param  string   $output
	 * @param  stdClass $item
	 * @param  int      $depth
	 * @param  array    $args
	 * @param  int      $id
	 */
	public function start_el( &$output, $item, $depth = 0, $args = [], $id = 0 ) {
		list( $indent, $eol ) = $this->get_indentation( $args, $depth );

		$title = $item->title ?: $item->post_title;

		if ( '---' === $title ) {
			$output .= '<hr class="navbar-divider">';
			return;
		}

		$item->classes   = empty( $item->classes ) ? [] : (array) $item->classes;
		$item->classes[] = 'navbar-item menu-item-' . $item->ID;
		$has_children    = $args->walker->has_children;

		if ( $has_children ) {
			$item->classes[] = 'is-hoverable has-dropdown';
		}

		// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
		$args    = apply_filters( 'nav_menu_item_args', $args, $item, $depth );
		$classes = apply_filters( 'nav_menu_css_class', array_filter( $item->classes ), $item, $args, $depth );
		// phpcs:enable

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
	 * End Menu Element.
	 *
	 * @internal
	 * @since 0.1.1
	 * @param  string   $output
	 * @param  stdClass $item
	 * @param  int      $depth
	 * @param  array    $args
	 */
	public function end_el( &$output, $item, $depth = 0, $args = [] ) {
		$eol = $this->get_indentation( $args, $depth )[1];
		$tag = in_array( 'has-children', $item->classes, true ) ? 'div' : 'a';

		$output .= "</{$tag}>{$eol}";
	}

	/**
	 * Get indentation and EOL.
	 *
	 * @param  stdClass $args
	 * @param  int      $depth
	 * @return array
	 */
	protected function get_indentation( &$args, int $depth = 0 ) {
		if ( is_array( $args ) ) {
			$args = (object) $args;
		}

		$is_discarded = isset( $args->item_spacing ) && 'discard' === $args->item_spacing;

		$tab = $is_discarded ? '' : "\t";
		$eol = $is_discarded ? '' : "\n";

		$indent = $depth > 0 ? str_repeat( $tab, $depth ) : $tab;

		return [ $indent, $eol ];
	}
}
