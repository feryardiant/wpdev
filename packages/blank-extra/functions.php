<?php
/**
 * Plugin Name: Blank Extra
 * Plugin URI:  https://github.com/feryardiant/wpdev
 * Description: A Companion plugin for Blank Theme
 * Version:     0.2.4
 * Author:      Fery Wardiyanto
 * Author URI:  https://feryardiant.id/
 * Text Domain: blank-extra
 * License:     GPL2+
 * License      URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package  Blank
 * @since    0.1.0
 */

/**
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BLANK_EXTRA_BASE', plugin_basename( __FILE__ ) );

defined( 'BLANK_EXTRA_DIR' ) || define( 'BLANK_EXTRA_DIR', plugin_dir_path( __FILE__ ) );
defined( 'BLANK_EXTRA_URL' ) || define( 'BLANK_EXTRA_URL', plugin_dir_url( __FILE__ ) );

/**
 * Load the plugin textdomain
 */
add_action(
	'plugins_loaded',
	function () {
		load_plugin_textdomain( 'blank-extra', false, BLANK_EXTRA_DIR . '/languages' );
	}
);

add_filter(
	'blank_init',
	function ( $features ) {
		$features[] = Blank_Extra\Options::class;

		if ( class_exists( Jetpack::class ) ) {
			$features[] = Blank_Extra\Integrations\JetPack::class;
		}

		return $features;
	}
);
