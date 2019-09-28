<?php
/**
 * WPBP Theme.
 *
 * @package    WordPress_Boilerplate
 * @subpackage WPBP_Theme
 * @since      0.1.0
 */

namespace WPBP;

/**
 * Theme Option.
 *
 * @category  Theme Option
 */
class Option extends Feature {
	/**
	 * Options items
	 *
	 * @var array
	 */
	private $items = [];

	/**
	 * Initialize class.
	 *
	 * @since 0.1.1
	 */
	protected function initialize() : void {
		// .
	}

	/**
	 * Get Theme Modification value.
	 *
	 * @since 0.1.1
	 * @param  string $name
	 * @param  mixed  $default
	 * @return mixed
	 */
	public function get( $name, $default = null ) {
		$theme_mods = get_theme_mods()[ $this->theme->slug ] ?? [];
		$theme_mod  = apply_filters( 'wpbp_default_' . $name, ( $theme_mods[ $name ] ?? null ) );

		return $theme_mod ?: $default;
	}

	/**
	 * Admin Page view.
	 *
	 * @since 0.1.0
	 * @return mixed
	 */
	public function admin_page() {
		?>
		<div class="wrap">

			<h1><?php esc_html_e( 'Theme Options', 'wpbp' ); ?></h1>

			<div id="poststuff">

				<form id="roles" action="#" method="post">

				</form> <!-- #roles -->

			</div> <!-- #poststuff -->

		</div> <!-- .wrap -->
		<?php
	}
}
