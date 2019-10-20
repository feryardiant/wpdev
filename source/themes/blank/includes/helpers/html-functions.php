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
		$class = $attr['class'];
		if ( is_string( $class ) ) {
			$class = explode( ' ', $class );
		}

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

	return array_values( array_unique( $class ) );
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
	$begin = '<' . $tag;
	$close = '';

	if ( ! empty( $attr ) ) {
		$begin .= ' ' . make_attr_from_array( $attr );

		$id = get_identifier_attr_from_array( $attr );
		if ( $id ) {
			$close = ' <!-- ' . $id . ' -->';
		}
	}

	if ( true === $ends ) {
		return $begin . '/>' . $close;
	} elseif ( false === $ends ) {
		return $begin . '></' . $tag . '>' . $close;
	}

	if ( is_callable( $ends ) ) {
		$ends = call_user_func( $ends );
	}

	$output = $begin . '>' . $ends . '</' . $tag . '>' . $close;

	if ( $returns ) {
		return $output;
	}

	$attr = array_map( function ( $a ) {
		return 1;
	}, array_flip( array_keys( $attr ) ) );

	echo wp_kses( $output, [ $tag => $attr ] );
}
