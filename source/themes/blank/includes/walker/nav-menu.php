<?php
/**
 * Blank Theme.
 *
 * @package  Blank
 * @since    0.2.0
 */

namespace Blank\Walker;

use function Blank\Helpers\make_attr_from_array;
use function Blank\Helpers\make_html_tag;

/**
 * Theme Style Class.
 *
 * @category Theme Menu
 */
class Nav_Menu extends \Walker_Nav_Menu {
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
	 * @return string|void
	 */
	public function fallback( $args ) {
		$attr = [ 'class' => 'menu-item' ];
		$args = is_array( $args ) ? (object) $args : $args;

		if ( current_user_can( 'edit_theme_options' ) ) {
			$tag  = 'a';
			$text = esc_html__( 'You don\'t have menu yet, please create one here', 'blank' );

			$attr['href'] = esc_url( admin_url( 'nav-menus.php' ) );
		} else {
			$tag  = 'div';
			$text = esc_html__( 'Menu items goes here', 'blank' );
		}

		$html = sprintf( $args->items_wrap, 'menu-' . $args->theme_location, $args->menu_class, make_html_tag( $tag, $attr, $text ) );

		if ( ! $args->echo ) {
			return $html;
		}

		echo wp_kses( $html, [
			'div' => [ 'class' => true ],
			'a'   => [
				'class' => true,
				'href'  => true,
			],
		] );
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

		$class = [ 'menu-dropdown', 'is-boxed' ];

		if ( $depth >= 1 ) {
			$class[] = 'submenu-depth-' . $depth;
		}

		$attributes = ( ! empty( $class ) ) ? ' ' . make_attr_from_array( [ 'class' => $class ] ) : '';
		$output    .= "{$eol}{$indent}<div{$attributes}>{$eol}";
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
			$output .= '<hr class="menu-divider">';
			return;
		}

		$item->classes = empty( $item->classes ) ? [] : (array) $item->classes;

		$attr = [
			'id'    => 'menu-item-' . $item->ID,
			'class' => apply_filters( 'blank_nav_menu_css_class', $item->classes, $item, $args, $depth ),
		];

		$href = esc_url( $item->url );

		if ( $args->walker->has_children ) {
			$output .= $eol . $indent . '<div ' . make_attr_from_array( $attr ) . '>';
			$output .= make_html_tag( 'a', [
				'class' => 'menu-link',
				'href'  => $href,
			], $title );
		} else {
			$attr['href'] = $href;
			$output      .= $eol . $indent . '<a ' . make_attr_from_array( $attr ) . '>' . $title;
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
