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
	protected function initialize(): void {
		add_filter(
			'blank_options_panel',
			function ( $panels ) {
				$panels['extras'] = [
					'title' => __( 'Extras', 'blank' ),
					'panel' => [ $this, 'panel_extras' ],
				];

				return $panels;
			},
			10,
			1
		);
	}

	/**
	 * Get extras panel content.
	 *
	 * @since 0.2.6
	 */
	public function panel_extras() {
		return '<p>Extras Panel</p>';
	}
}
