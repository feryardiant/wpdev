<?php
/**
 * WPBP autoloader
 *
 * @package  Blank
 * @since    0.2.0
 */

namespace Blank\Helpers;

/**
 * Convert array to HTML attributes.
 *
 * @since 0.1.1
 * @param array  $attr
 * @param string $attr_sep
 * @param bool   $quoted
 * @return string
 */
function make_attr_from_array( array $attr, string $attr_sep = ' ', bool $quoted = true ) : string {
	$output = [];

	foreach ( array_filter( $attr ) as $name => $value ) {
		if ( empty( $value ) ) {
			$output[] = $name;
			continue;
		}

		if ( 'class' === $name ) {
			$value = normalize_class_attr( (array) $value );
		}

		if ( 'style' === $name ) {
			$value = make_attr_from_array( (array) $value, '; ', false );
		}

		if ( is_array( $value ) ) {
			$value = join( ' ', $value );
		}

		$output[] = $name . '=' . ( $quoted ? '"' . $value . '"' : $value );
	}

	return join( $attr_sep, $output );
}

/**
 * Get id or first class attribute from array.
 *
 * @param  array $attr
 * @return string|null
 */
function get_identifier_attr_from_array( array $attr ) : ?string {
	if ( isset( $attr['id'] ) ) {
		return '#' . $attr['id'];
	}

	if ( isset( $attr['class'] ) ) {
		$class = is_string( $attr['class'] )
			? explode( ' ', $attr['class'] )
			: (array) $attr['class'];

		return '.' . $class[0];
	}

	return null;
}

/**
 * HTML Class attribute normalizer.
 *
 * @param  string|array $class
 * @param  string|array ...$classes
 * @return array
 */
function normalize_class_attr( $class, ...$classes ) : array {
	if ( ! empty( $classes ) ) {
		$class = array_merge( [ $class ], $classes );
	}

	$class = array_merge( ...array_map( function ( $class ) {
		if ( is_string( $class ) ) {
			return explode( ' ', $class );
		}

		return normalize_class_attr( $class );
	}, $class ) );

	return array_values( array_unique( array_filter( $class ) ) );
}

/**
 * Create HTML element.
 *
 * @since 0.2.1
 * @param string|array         $tag
 * @param array                $attr
 * @param string|bool|callable $ends
 * @param bool                 $returns
 * @return string|void
 */
function make_html_tag( $tag, $attr = [], $ends = false, $returns = true ) {
	if ( ! $tag ) {
		return;
	}

	$begin = '<' . $tag;
	$close = PHP_EOL;

	if ( ! empty( $attr ) ) {
		$begin .= ' ' . make_attr_from_array( $attr );

		$id    = get_identifier_attr_from_array( $attr );
		$close = $id ? ' <!-- ' . $id . ' -->' . $close : $close;
	}

	if ( is_callable( $ends ) ) {
		$ends = call_user_func( $ends, blank() );
	}

	if ( true === $ends ) {
		return $begin . '/>' . $close;
	} elseif ( false === $ends ) {
		return $begin . '></' . $tag . '>' . $close;
	}

	$kses = [ $tag ];

	if ( is_array( $ends ) ) {
		$inner = [];

		foreach ( $ends as $sub_tag => $param ) {
			if ( is_string( $param ) && is_numeric( $sub_tag ) ) {
				$kses[]  = [ 'div', 'a', 'span' ];
				$inner[] = PHP_EOL . $param;
				continue;
			}

			$param = wp_parse_args( $param, [
				'attr' => [],
				'ends' => false,
			] );

			$inner[] = PHP_EOL . make_html_tag( $sub_tag, $param['attr'], $param['ends'], true );
			$kses[]  = $sub_tag;

			if ( is_array( $param['ends'] ) ) {
				foreach ( array_keys( $param['ends'] ) as $end_tag ) {
					$kses[] = $end_tag;
				}
			}
		}

		$ends = join( '', $inner );
	}

	$output = $begin . '>' . $ends . '</' . $tag . '>' . $close;

	if ( $returns ) {
		return $output;
	}

	foreach ( $kses as $el ) {
		if ( is_array( $el ) ) {
			$kses = array_merge( $kses, get_allowed_attr( $el ) );
			continue;
		}

		$kses[ $el ] = get_allowed_attr( $el );
	}

	echo wp_kses( $output, $kses );
}

/**
 * Retrieve allowed HTML attribute for given tag.
 *
 * @param  string|array $tag
 * @param  array        $attr
 * @return array
 */
function get_allowed_attr( $tag, array $attr = [] ) : array {
	static $allowed_kses;

	if ( ! $allowed_kses ) {
		$allowed_kses = wp_kses_allowed_html( 'post' );
	}

	if ( is_array( $tag ) ) {
		return array_reduce( $tag, function ( $tags, $tag ) {
			$tags[ $tag ] = get_allowed_attr( $tag );
			return $tags;
		}, [] );
	}

	// Additional attr from schema.org.
	$extra_attr = [
		'itemscope' => 1,
		'itemprop'  => 1,
		'itemtype'  => 1,
	];

	if ( ! empty( $attr ) ) {
		$extra_attr = array_merge(
			$extra_attr,
			array_map( function () {
				return 1;
			}, array_flip( array_keys( $attr ) ) )
		);
	}

	$allowed_attr = array_key_exists( $tag, $allowed_kses ) ? $allowed_kses[ $tag ] : $allowed_kses['div'];

	switch ( $tag ) {
		case 'form':
			$extra_attr['action'] = 1;
			$extra_attr['method'] = 1;
			break;
		case 'input':
		case 'select':
		case 'option':
		case 'button':
			$extra_attr['value']         = 1;
			$extra_attr['type']          = 1;
			$extra_attr['name']          = 1;
			$extra_attr['placeholder']   = 1;
			$extra_attr['aria-controls'] = 1;
			$extra_attr['aria-expanded'] = 1;
			break;
	}

	return array_merge( $allowed_attr, $extra_attr );
}

/**
 * Retrieve Schema.org attributes array for given $context.
 *
 * @link https://schema.org/docs/gs.html
 * @param  string $context
 * @return array
 */
function get_schema_org_attr( string $context ) : array {
	$attr = [ 'itemscope' => null ];

	switch ( $context ) {
		case 'header':
			$attr['itemtype'] = 'http://schema.org/WPHeader';
			return $attr;

		case 'logo':
			$attr['itemtype'] = 'http://schema.org/Brand';
			return $attr;

		case 'navigation':
			$attr['itemtype'] = 'http://schema.org/SiteNavigationElement';
			return $attr;

		case 'blog':
			$attr['itemtype'] = 'http://schema.org/Blog';
			return $attr;

		case 'breadcrumb':
			$attr['itemtype'] = 'http://schema.org/BreadcrumbList';
			return $attr;

		case 'breadcrumb_list':
			$attr['itemprop'] = 'itemListElement';
			$attr['itemtype'] = 'http://schema.org/ListItem';
			return $attr;

		case 'breadcrumb_itemprop':
			$attr['itemprop'] = 'breadcrumb';
			return $attr;

		case 'sidebar':
			$attr['itemtype'] = 'http://schema.org/WPSideBar';
			return $attr;

		case 'footer':
			$attr['itemtype'] = 'http://schema.org/WPFooter';
			return $attr;

		case 'headline':
			$attr['itemprop'] = 'headline';
			return $attr;

		case 'entry_content':
			$attr['itemprop'] = 'text';
			return $attr;

		case 'publish_date':
			$attr['itemprop'] = 'datePublished';
			return $attr;

		case 'author_name':
			$attr['itemprop'] = 'name';
			return $attr;

		case 'author_link':
			$attr['itemprop'] = 'author';
			return $attr;

		case 'item':
			$attr['itemprop'] = 'item';
			return $attr;

		case 'url':
			$attr['itemprop'] = 'url';
			return $attr;

		case 'position':
			$attr['itemprop'] = 'position';
			return $attr;

		case 'image':
			$attr['itemprop'] = 'image';
			return $attr;

		default:
			return [];
	}
}
