<?php
/**
 * Blank Theme.
 *
 * @package  Blank
 * @since    0.2.0
 */

namespace Blank;

use function Blank\Helpers\make_html_tag;

/**
 * Theme Widget Class.
 *
 * @category  Widget
 */
class Options extends Feature {
	/**
	 * Panels definition
	 *
	 * @var array
	 */
	private $panels = [];

	/**
	 * Initialize class.
	 *
	 * @since 0.1.1
	 */
	protected function initialize(): void {
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

		$this->panels = apply_filters(
			'blank_options_panel',
			[
				'welcome' => [
					'title' => __( 'Welcome', 'blank' ),
					'panel' => [ $this, 'panel_welcome' ],
				],
				'general' => [
					'title' => __( 'General', 'blank' ),
					'panel' => [ $this, 'panel_general' ],
				],
			]
		);
	}

	/**
	 * Get panel navigations.
	 *
	 * @since 0.2.6
	 */
	public function get_navigations() {
		$html = [];

		foreach ( $this->panels as $id => $props ) {
			$html[] = [
				'tag'  => 'li',
				'ends' => [
					'a' => [
						'attr' => [ 'href' => '#panel-section-' . $id ],
						'ends' => $props['title'],
					],
				],
			];
		}

		make_html_tag( 'ul', [], $html, false );
	}

	/**
	 * Get panel sections.
	 *
	 * @since 0.2.6
	 */
	public function get_sections() {
		$html = [];

		foreach ( $this->panels as $id => $props ) {
			$html[] = [
				'tag'  => 'section',
				'attr' => [ 'id' => 'panel-section-' . $id ],
				'ends' => $props['panel'],
			];
		}

		make_html_tag( 'main', [ 'id' => 'panel-sections' ], $html, false );
	}

	/**
	 * Get general panel content.
	 *
	 * @since 0.2.6
	 */
	public function panel_general() {
		return '<p>General Panel</p>';
	}

	/**
	 * Get welcome panel content.
	 *
	 * @since 0.2.6
	 */
	public function panel_welcome() {
		return '<p>Welcome Panel</p>';
	}
}
