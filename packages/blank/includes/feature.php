<?php
/**
 * Blank Theme.
 *
 * @package  Blank
 * @since    0.2.0
 */

namespace Blank;

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
	private static $instance;

	/**
	 * Initialize class.
	 *
	 * @since 0.1.0
	 * @param Theme $theme
	 */
	final public function __construct( Theme $theme ) {
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
	 * @codeCoverageIgnore
	 */
	final public function __set( $name, $value ) {
		// . doing nothing
	}

	/**
	 * Class initializer.
	 *
	 * @since 0.1.1
	 * @return void
	 * @codeCoverageIgnore
	 */
	abstract protected function initialize() : void;

	/**
	 * Get instance of child class.
	 *
	 * @since 0.1.1
	 * @return self
	 * @throws \RuntimeException If not initialized.
	 */
	final public static function get_instance() {
		if ( self::$instance ) {
			return self::$instance;
		}

		throw new \RuntimeException(
			sprintf( 'Class %s was not initialized', self::class )
		);
	}
}
