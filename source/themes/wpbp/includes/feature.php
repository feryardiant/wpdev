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
 * Theme Feature Abstract Class.
 *
 * @category  Theme Feature.
 */
abstract class Feature {
	/**
	 * Theme Instance
	 *
	 * @since 0.1.0
	 * @var Theme
	 */
	protected $theme;

	/**
	 * Self instance.
	 *
	 * @since 0.1.0
	 * @var Feature
	 */
	protected static $instance;

	/**
	 * Initialize class.
	 *
	 * @since 0.1.0
	 * @param Theme $theme
	 */
	public function __construct( Theme $theme ) {
		$this->theme = $theme;

		self::$instance = $this;

		$this->initialize();
	}

	/**
	 * Prevent overwriting.
	 *
	 * @since 0.1.1
	 * @param string $name
	 * @param mixed  $value
	 */
	public function __set( $name, $value ) {
		// . doing nothing
	}

	/**
	 * Class initializer.
	 *
	 * @return void
	 */
	abstract protected function initialize() : void;
}
