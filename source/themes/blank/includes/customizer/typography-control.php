<?php
/**
 * Blank Theme.
 *
 * @package  Blank
 * @since    0.2.1
 */

namespace Blank\Customizer;

use Blank\Typography;

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

		/** @var Typography $typo */
		$typo     = blank( 'typography' );
		$families = array_reduce( $typo->get_fonts(), function ( $families, $font ) {
			$source = $font->source;

			if ( ! isset( $families[ $source ] ) ) {
				$families[ $source ] = [];
			}

			$families[ $source ][] = $font->family;
			return $families;
		}, [] );

		$this->json['choices'] = [
			'families'   => $families,
			'variants'   => Typography::VARIANTS,
			'subsets'    => Typography::SUBSETS,
			'size_units' => [
				'px',
				'em',
				'rem',
				'pt',
				'vw',
			],
			'heights'    => [
				'px',
				'em',
				'pt',
				'%',
			],
			'devices'    => [
				'mobile',
				'tablet',
				'desktop',
			],
		];
	}

	/**
	 * @inheritDoc
	 * @return void
	 */
	protected function control_template() {
		?>
		<div class="blank-control-row">
			<div class="blank-control-column font-family">
				<label for="{{ data.id }}-family">Font Family</label>
				<select id="{{ data.id }}-family" class="family input" data-value="{{ data.value.family }}">
					<# for ( group in data.choices.families ) { #>
						<optgroup label="{{ group }}">
							<# for ( family of	 data.choices.families[ group ] ) { #>
								<option value="{{ family }}">{{ family }}</option>
							<# } #>
						</optgroup>
					<# } #>
				</select>
			</div>

			<div class="blank-control-column variants">
				<label for="{{ data.id }}-variant">Font Variant</label>
				<select id="{{ data.id }}-variant" class="variant input" data-value="{{ data.value.variant }}">
					<# for ( variant in data.choices.variants ) { #>
						<option value="{{ variant }}">{{ data.choices.variants[ variant ] }}</option>
					<# } #>
				</select>
			</div>
		</div>

		<div class="blank-control-row">
			<div class="blank-control-column font-size">
				<label for="{{ data.id }}-size-value">Font Size</label>
				<div class="blank-control-inputs-group">
					<input id="{{ data.id }}-size-value" value="{{ data.value.size[0] }}" min="1" class="size-value input" type="number"/>
					<select id="{{ data.id }}-size-unit" data-value="{{ data.value.size[1] }}" class="size-unit input">
						<# for ( unit of data.choices.size_units ) { #>
							<option value="{{ unit }}">{{ unit }}</option>
						<# } #>
					</select>
				</div>
			</div>

			<div class="blank-control-column line-height">
				<label for="{{ data.id }}-height-value">Line Height</label>
				<div class="blank-control-inputs-group">
					<input id="{{ data.id }}-height-value" value="{{ data.value.height[0] }}" min="1" class="height-value input" type="number"/>
					<select id="{{ data.id }}-height-unit" data-value="{{ data.value.height[1] }}" class="height-unit input">
						<# for ( height of data.choices.heights ) { #>
							<option value="{{ height }}">{{ height }}</option>
						<# } #>
					</select>
				</div>
			</div>
		</div>
		<?php
	}
}
