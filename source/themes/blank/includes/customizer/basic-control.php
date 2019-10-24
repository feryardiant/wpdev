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
	protected function content_template() {
		?>
		<header>
			<label for="{{ data.id }}" class="customize-control-title">{{{ data.label }}}</label>
			<# if ( data.description ) { #>
				<span class="description customize-control-description">{{{ data.description }}}</span>
			<# } #>
		</header>

		<section>
			<input id="{{ data.id }}" type="{{ data.type }}" {{{ data.link }}} value="{{ data.value }}" />
		</section>
		<?php
	}
}
