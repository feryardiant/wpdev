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
 * Theme Wrapper Class.
 *
 * @subpackage  Theme Wrapper
 */
class Wrapper {
	/**
	 * Main template.
	 *
	 * @var string
	 */
	private $main;

	/**
	 * Base template.
	 *
	 * @var string
	 */
	private $base;

	/**
	 * Initialize class.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {
		add_filter( 'template_include', [ $this, 'wrap' ], 99 );
	}

	/**
	 * Template Wrapper
	 *
	 * @param  string $template
	 * @return string
	 */
	public function wrap( $template ) {
		$this->main = $template;
		$this->base = substr( wp_basename( $this->main ), 0, -4 );

		if ( 'index' === $this->base ) {
			$this->base = false;
		}

		$templates = [ 'template-parts/wrapper.php' ];

		if ( $this->base ) {
			array_unshift( $templates, sprintf( 'template-parts/wrapper-%s.php', $this->base ) );
		}

		return locate_template( $templates );
	}

	/**
	 * Get base template.
	 *
	 * @return string
	 */
	public function get_base() {
		return $this->base;
	}

	/**
	 * Get main template.
	 *
	 * @return string
	 */
	public function get_main() {
		return $this->main;
	}
}
