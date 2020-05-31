<?php
/**
 * Blank Theme.
 *
 * @package  Blank
 * @since    0.2.1
 */

namespace Blank\Customizer;

/**
 * Theme Basic Control Class.
 *
 * @category  Customizer Control
 */
class Basic_Control extends Abstract_Control {
	/**
	 * @inheritDoc
	 * @var string
	 */
	public $type = 'blank-basic';

	/**
	 * @inheritDoc
	 * @return void
	 */
	protected function control_template() {
		?>
		<input id="{{ data.id }}" type="{{ data.type }}" value="{{ data.value }}" />
		<?php
	}
}
