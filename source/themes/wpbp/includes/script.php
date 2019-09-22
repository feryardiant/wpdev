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
 * Theme Script Class.
 *
 * @category  Theme Script
 */
class Script extends Feature {
	/**
	 * Initialize class.
	 *
	 * @since 0.1.0
	 * @param Theme $theme
	 */
	public function __construct( Theme $theme ) {
		parent::__construct( $theme );

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
		$version = $this->theme->info( 'version' );

		wp_enqueue_script( 'wpbp-script', $this->theme->assets_url( 'navigation.js' ), [], $version, true );

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
}
