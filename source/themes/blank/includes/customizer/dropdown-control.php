<?php
/**
 * Blank Theme.
 *
 * @package  Blank
 * @since    0.2.1
 */

namespace Blank\Customizer;

/**
 * Theme Dropdown Control Class.
 *
 * @category  Customizer Control
 */
class Dropdown_Control extends Abstract_Control {
	/**
	 * @inheritDoc
	 * @var string
	 */
	public $type = 'blank-dropdown';

	/**
	 * @var integer
	 */
	public $multiple = 1;

	/**
	 * @inheritDoc
	 */
	public function to_json() {
		parent::to_json();

		$this->json['multiple'] = $this->multiple;
	}

	/**
	 * @inheritDoc
	 * @return void
	 */
	protected function control_template() {
		?>
		<select id="{{ data.id }}" data-multiple="{{ data.multiple }}">
			<option value="">Choose One</option>
			<# for ( key in data.choices ) { #>
				<option value="{{ data.value[ key ] }}">{{ data.choices[ data.value[ key ] ] }}</option>
			<# } #>
		</select>
		<?php
	}
}
