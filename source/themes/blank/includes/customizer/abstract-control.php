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
	 * @inheritDoc
	 * @return void
	 */
	public function to_json() {
		parent::to_json();

		if ( isset( $this->default ) ) {
			$this->json['default'] = $this->default;
		} else {
			$this->json['default'] = $this->setting->default;
		}

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
		$class = 'customize-control customize-control-blank customize-control-' . $this->type;

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
}
