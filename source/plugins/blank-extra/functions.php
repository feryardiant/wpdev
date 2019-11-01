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
define( 'BLANK_EXTRA_DIR', plugin_dir_path( __FILE__ ) );
define( 'BLANK_EXTRA_URL', plugin_dir_url( __FILE__ ) );

/**
 * Load the plugin textdomain
 */
add_action( 'plugins_loaded', function () {
	load_plugin_textdomain( 'blank-extra', false, plugin_basename( __DIR__ ) . '/languages' );
} );

$blank_plugin_should_load = true;

if ( ! version_compare( PHP_VERSION, '7.0', '>=' ) ) {
	$blank_plugin_should_load = falase;

	add_action( 'admin_notices', function () {
		$message = sprintf(
			/* translators: %s: PHP version */
			esc_html__( 'Blank requires PHP version %s+, plugin is currently NOT RUNNING.', 'blank-extra' ),
			'7.0'
		);

		echo wp_kses_post(
			sprintf( '<div class="error">%s</div>', wpautop( $message ) )
		);
	} );
}

if ( ! version_compare( get_bloginfo( 'version' ), '5.0', '>=' ) ) {
	$blank_plugin_should_load = falase;

	add_action( 'admin_notices', function () {
		$message = sprintf(
			/* translators: %s: WordPress version */
			esc_html__( 'Blank requires WordPress version %s+. Because you are using an earlier version, the plugin is currently NOT RUNNING.', 'blank-extra' ),
			'5.0'
		);

		echo wp_kses_post(
			sprintf( '<div class="error">%s</div>', wpautop( $message ) )
		);
	} );
}

if ( ! $blank_plugin_should_load ) {
	return;
}

require_once BLANK_PLUGIN_DIR . 'autoload.php';
