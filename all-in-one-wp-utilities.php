<?php
/**
 * The plugin bootstrap file
 *
 * @link              https://2bytecode.com
 * @since             1.0.0
 * @package           All_In_One_Wp_Utilities
 *
 * @wordpress-plugin
 * Plugin Name:       All-in-One WP Utilities
 * Plugin URI:        https://wordpress.org/plugins/all-in-one-wp-utilities
 * Description:       Must use utilities for your WordPress site. Turn on/off features with a single click.
 * Version:           1.0.0
 * Author:            2ByteCode
 * Author URI:        https://2bytecode.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       all-in-one-wp-utilities
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die( 'No script kiddies please!' );
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'ALL_IN_ONE_WP_UTILITIES_VERSION', '1.0.0' );

/**
 * Variables
 */
define( 'AIOWPU_URL', plugin_dir_url( __FILE__ ) );
define( 'AIOWPU_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Plugin Activation.
 *
 * @param bool $networkwide The networkwide.
 */
function activate_all_in_one_wp_utilities( $networkwide ) {
	do_action( 'aiowpu_plugin_activation', $networkwide );
}
register_activation_hook( __FILE__, 'activate_all_in_one_wp_utilities' );

/**
 * Plugin Deactivation.
 *
 * @param bool $networkwide The networkwide.
 */
function deactivate_all_in_one_wp_utilities( $networkwide ) {
	do_action( 'aiowpu_plugin_deactivation', $networkwide );
}
register_deactivation_hook( __FILE__, 'deactivate_all_in_one_wp_utilities' );

/**
 * Language
 */
function aiowpu_load_plugin_textdomain() {
	load_plugin_textdomain(
		'all-in-one-wp-utilities',
		false,
		plugin_basename( AIOWPU_PATH ) . '/languages/'
	);
}
add_action( 'plugins_loaded', 'aiowpu_load_plugin_textdomain' );

/**
 * Plugins Settings Page.
 *
 * @param array $actions An array of plugin action links.
 */
function aiowpu_action_links( $actions ) {
	$actions[] = sprintf( '<a href="%s">%s</a>', aiowpu_get_page_url( 'manager', 'admin' ), esc_html__( 'Settings', 'all-in-one-wp-utilities' ) );

	return $actions;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'aiowpu_action_links' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'core/class-all-in-one-wp-utilities.php';
