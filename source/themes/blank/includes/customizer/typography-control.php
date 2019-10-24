<?php
/**
 * Blank Theme.
 *
 * @package  Blank
 * @since    0.2.1
 */

namespace Blank\Customizer;

/**
 * Theme Typography Control Class.
 *
 * @category  Customizer Control
 */
class Typography_Control extends Abstract_Control {
	/**
	 * @inheritDoc
	 * @var string
	 */
	public $type = 'blank-typography';

	/**
	 * @inheritDoc
	 */
	public function to_json() {
		parent::to_json();

		$theme   = blank();
		$choises = [];

		foreach ( $theme->typography->get_fonts() as $font ) {
			$choises[ $font->family ] = $font->family;
		}

		$this->json['choices'] = $choises;
	}

	/**
	 * @inheritDoc
	 * @return void
	 */
	protected function content_template() {
		?>
		<header>
			<label for="{{ data.id }}-family" class="customize-control-title">{{{ data.label }}}</label>
			<# if ( data.description ) { #>
				<span class="description customize-control-description">{{{ data.description }}}</span>
			<# } #>
		</header>

		<section data-type="{{ data.type }}" class="blank-control-typography">
			<div class="blank-control-row">
				<span>Font Family</span>
				<select id="{{ data.id }}-family" class="blank-control-typography-family" {{{ data.link }}} data-value="{{ data.value.family }}">
					<# for ( key in data.choices ) { #>
						<option value="{{ data.value[ key ] }}">{{ data.choices[ key ] }}</option>
					<# } #>
				</select>
			</div>

			<div class="blank-control-row" data-value="{{ data.value.size }}" data-default="{{ data.default.size }}">
				<span>Font Size</span>
				<input id="{{ data.id }}-size" class="blank-control-typography-size" type="number" />
			</div>

			<div class="blank-control-row" data-value="{{ data.value.variant }}" data-default="{{ data.default.variant }}">
				<span>Font Variant</span>
				<input id="{{ data.id }}-variant" class="blank-control-typography-variant" type="text"/>
			</div>
		</section>
		<?php
	}
}
