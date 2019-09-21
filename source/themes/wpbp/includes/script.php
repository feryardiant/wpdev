<?php
/**
 * WPBP Theme.
 *
 * @package     WordPress_Boilerplate
 * @subpackage  WPBP_Theme
 * @since       0.1.0
 */

namespace WPBP;

/**
 * Theme Style Class.
 *
 * @category  Theme Style
 */
class Script {
	/**
	 * Theme Instance
	 *
	 * @var Theme
	 */
	protected $theme;

	/**
	 * Initialize class.
	 *
	 * @since 0.1.0
	 * @param Theme $theme
	 */
	public function __construct( Theme $theme ) {
		$this->theme = $theme;

		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue' ] );
	}

	/**
	 * Enqueue scripts and styles
	 *
	 * @internal
	 * @since 0.1.0
	 * @return void
	 */
	public function enqueue() {
		$theme   = wpbp();
		$version = $theme->info( 'version' );

		wp_enqueue_script( 'wpbp-script', $theme->assets_url( 'navigation.js' ), [], $version, true );

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
}
