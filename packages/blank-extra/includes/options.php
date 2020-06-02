<?php
/**
 * Blank Theme.
 *
 * @package  Blank
 * @since    0.2.0
 */

namespace Blank_Extra;

use Blank\Feature;

/**
 * Theme Widget Class.
 *
 * @category  Widget
 */
class Options extends Feature {
	/**
	 * Initialize class.
	 *
	 * @since 0.1.1
	 */
	protected function initialize() : void {
		add_action( 'admin_menu', [ $this, 'admin_menu' ] );
	}

	/**
	 * Theme option panel.
	 *
	 * @link https://developer.wordpress.org/reference/functions/add_theme_page/
	 * @since 0.1.0
	 * @return mixed
	 * @codeCoverageIgnore
	 */
	public function admin_menu() {
		add_theme_page(
			/* translators: %s: Theme name. */
			sprintf( __( '%s Option Panel', 'blank-extra' ), 'Blank' ),
			__( 'Theme Option', 'blank-extra' ),
			'edit_theme_options',
			'blank-options',
			function () {
				include_once $this->theme->get_dir( 'templates/admin/options.php' );
			}
		);
	}
}
