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
		list( $indent, $eol, $tab ) = $this->get_indentation( $args, $depth );

		$class = [ 'menu-dropdown', 'is-boxed', 'menu-depth-' . $depth ];

		if ( $args->walker->has_children ) {
			$indent .= $tab;
		}

		$attributes = ( ! empty( $class ) ) ? ' ' . make_attr_from_array( [ 'class' => $class ] ) : '';
		$output    .= "{$indent}<div{$attributes}>{$eol}";
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
		list( $indent, $eol, $tab ) = $this->get_indentation( $args, $depth );

		if ( $args->walker->has_children || $depth > 0 ) {
			$indent .= $tab;
		}

		$output .= "{$indent}{$tab}</div> <!-- .menu-dropdown -->{$eol}";
	}

	/**
	 * Start Menu Eleent.
	 *
	 * @internal
	 * @since 0.1.1
	 * @param  string         $output
	 * @param  stdClass       $item
	 * @param  int            $depth
	 * @param  array|stdClass $args
	 * @param  int            $id
	 */
	public function start_el( &$output, $item, $depth = 0, $args = [], $id = 0 ) {
		list( $indent, $eol, $tab ) = $this->get_indentation( $args, $depth );

		$title = $item->title ?: $item->post_title;

		if ( ! empty( $item->description ) ) {
			$title = '<span class="menu-title">' . $title . '</span>' .
					'<span class="menu-description">' . $item->description . '</span>';
		}

		if ( '---' === $title ) {
			$output .= $indent . '<hr class="menu-divider">' . $eol;
			return;
		}

		$classes = array_diff(
			empty( $item->classes ) ? [] : (array) $item->classes,
			[ 'current_page_item', 'page_item', 'current-page-ancestor', 'current_page_parent', 'current_page_ancestor' ]
		);

		$item->classes = array_replace(
			$classes,
			array_fill_keys( array_keys( $classes, 'current-menu-ancestor', true ), 'is-active' ),
			array_fill_keys( array_keys( $classes, 'current-menu-item', true ), 'is-active' ),
			array_fill_keys( array_keys( $classes, 'menu-item-has-children', true ), 'has-children' )
		);

		$attr = [
			'id'    => 'menu-item-' . $item->ID,
			'class' => apply_filters( 'blank_nav_menu_css_class', $item->classes, $item, $args, $depth ),
		];

		if ( ! empty( $item->attr_title ) ) {
			$attr['title'] = $item->attr_title;
		}

		$link_attr = [ 'href' => esc_url( $item->url ) ];

		if ( ! empty( $item->target ) ) {
			$link_attr['target'] = $item->target;
		}

		if ( ! empty( $item->xfn ) ) {
			$link_attr['rel'] = $item->xfn;
		}

		if ( $args->walker->has_children ) {
			$link_attr['class'] = 'menu-link';

			$output .= $indent . '<div ' . make_attr_from_array( $attr ) . '>' . $eol;
			$output .= $indent . $tab . make_html_tag( 'a', $link_attr, $title );
		} else {
			if ( $depth > 0 ) {
				$indent .= str_repeat( $tab, $depth );
			}

			$attr = array_merge( $attr, $link_attr );

			$attr['href'] = esc_url( $item->url );
			$output      .= $indent . '<a ' . make_attr_from_array( $attr ) . '>' . $title;
		}
	}

	/**
	 * End Menu Element.
	 *
	 * @internal
	 * @since 0.1.1
	 * @param  string         $output
	 * @param  stdClass       $item
	 * @param  int            $depth
	 * @param  array|stdClass $args
	 */
	public function end_el( &$output, $item, $depth = 0, $args = [] ) {
		list( $indent, $eol, $tab ) = $this->get_indentation( $args, $depth );
		$has_children               = in_array( 'has-children', $item->classes, true );

		if ( $depth > 0 ) {
			$indent .= $tab;
		}

		$tag = $has_children ? 'div' : 'a';
		$ind = $has_children ? $indent : '';

		$output .= "{$ind}</{$tag}> <!-- #menu-item-{$item->ID} -->{$eol}";
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
		$mlt = $args->walker->has_children ? 2 : 1;

		$indent = $depth > 0 ? str_repeat( $tab, $depth + $mlt ) : $tab;

		return [ $indent, $eol, $tab ];
	}
}
