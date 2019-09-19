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
class Style {
	/**
	 * Style maker.
	 *
	 * @param  \Closure|array $styles
	 * @return string
	 * @throws \InvalidArgumentException If parameter not an array or instance of Closure.
	 */
	public static function make( $styles ) {
		static $self;

		if ( null === $self ) {
			$self = new self();
		}

		if ( $styles instanceof \Closure ) {
			$styles = $styles( $self );
		}

		if ( ! is_array( $styles ) ) {
			throw new \InvalidArgumentException( 'Param 1 should be instance of Closure or an array' );
		}

		$inline = [];

		foreach ( $styles as $selector => $style ) {
			$inline[] = $selector . ' {' . PHP_EOL;

			foreach ( $style as $name => $value ) {
				$inline[] = $name . ': ' . $value . ';' . PHP_EOL;
			}

			$inline[] = '}' . PHP_EOL;
		}

		return PHP_EOL . implode( ' ', $inline );
	}

	/**
	 * Escape file url.
	 *
	 * @param  string $url
	 * @return string
	 */
	public function url( string $url ) : string {
		return 'url(' . esc_url( $url ) . ')';
	}
}
