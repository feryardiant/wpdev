<?php
/**
 * Blank Theme.
 *
 * @package  Blank
 * @since    0.2.1
 */

namespace Blank\Customizer;

/**
 * Theme Abstract Control Class.
 *
 * @category  Customizer Control
 */
abstract class Abstract_Control extends \WP_Customize_Control {
	/**
	 * Is current control available for multi devices?.
	 *
	 * @var int
	 */
	public $multi_devices = 0;

	/**
	 * @inheritDoc
	 * @return void
	 */
	public function to_json() {
		parent::to_json();

		$this->json['default'] = $this->default ?? $this->setting->default;
		$this->json['value']   = $this->value();
		$this->json['choices'] = $this->choices;
		$this->json['link']    = $this->get_link();
		$this->json['id']      = $this->normalized_id();
	}

	/**
	 * @inheritDoc
	 * @return void
	 */
	public function enqueue() {
		$theme = blank();

		wp_enqueue_script(
			'blank-customizer-control',
			$theme->asset->get_uri( 'customizer-controls.js' ),
			[ 'customize-controls' ],
			$theme->version,
			true
		);
	}

	/**
	 * @inheritDoc
	 * @return void
	 */
	protected function render() {
		$id    = 'customize-control-' . $this->normalized_id();
		$class = 'customize-control customize-control-blank-options customize-control-' . $this->type;

		printf( '<li id="%s" class="%s"></li>', esc_attr( $id ), esc_attr( $class ) );
	}

	/**
	 * Get normalized ID.
	 *
	 * @return string
	 */
	protected function normalized_id() {
		return str_replace( [ '_', '[', ']' ], [ '-', '-', '' ], strtolower( $this->id ) );
	}

	/**
	 * @inheritDoc
	 * @return void
	 */
	protected function render_content() {
		// .
	}

	/**
	 * @inheritDoc
	 * @return void
	 */
	protected function content_template() {
		?>
		<div class="blank-control" data-type="{{ data.type }}">
			<header>
				<button {{{ data.link }}} type="button" class="blank-control-revert"></button>
				<h3 class="blank-control-title">{{{ data.label }}}</h3>
				<# if ( data.description ) { #><p class="blank-control-description">{{{ data.description }}}</p><# } #>
			</header>

			<section><?php $this->control_template(); ?></section>
		</div>
		<?php
	}

	/**
	 * Custom Control Template
	 *
	 * @return void
	 */
	abstract protected function control_template();
}
