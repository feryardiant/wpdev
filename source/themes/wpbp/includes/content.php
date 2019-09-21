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
 * Theme Content Class.
 *
 * @category  Theme Content
 */
class Content extends Feature {
	/**
	 * Initialize class.
	 *
	 * @since 0.1.0
	 * @param Theme $theme
	 */
	public function __construct( Theme $theme ) {
		parent::__construct( $theme );

		add_filter( 'get_search_form', function () {
			return '';
		} );
	}
}