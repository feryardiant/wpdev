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
	 * @var Theme
	 */
	protected $theme;

	/**
	 * Self instance.
	 *
	 * @var Style
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
	}
}
