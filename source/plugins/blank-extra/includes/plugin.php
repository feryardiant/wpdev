<?php
/**
 * Blank Extra Plugin.
 *
 * @package  Blank
 * @since    0.2.0
 */

namespace Blank_Extra;

/**
 * Theme Setup Class.
 */
final class Plugin {
	/**
	 * Initialize class.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {
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
			'blank-extra-options',
			function () {
				include_once $this->get_dir( 'templates/admin/options.php' );
			}
		);
	}
}
