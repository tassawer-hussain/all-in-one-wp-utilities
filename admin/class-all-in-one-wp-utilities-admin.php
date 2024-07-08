<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://2bytecode.com
 * @since      1.0.0
 *
 * @package    All_In_One_Utilities
 * @subpackage All_In_One_Utilities/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    All_In_One_Utilities
 * @subpackage All_In_One_Wp_Utilities/admin
 * @author     2ByteCode <support@2bytecode.com>
 */
class All_In_One_Wp_Utilities_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The message output.
	 *
	 * @var string $msg The message output.
	 */
	public $msg;

	/**
	 * The slug name to refer to this menu by.
	 *
	 * @var string $menu_slug The menu slug.
	 */
	public $menu_slug = 'manager';

	/**
	 * List of all available modules.
	 *
	 * @var array $modules The menu slug.
	 */
	public $modules = array();

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		$this->modules = aiowpu_get_modules();

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/all-in-one-wp-utilities-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/all-in-one-wp-utilities-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add menu page
	 */
	public function add_menu_page() {

		$svg = '<svg width="18px" height="18px" viewBox="0 0 18 18" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g fill="#81878B"><path d="M6.78872178,17.7263347 L7.27177123,13.7724855 L8.84170382,13.7724855 C11.0605419,13.7724855 12.7351366,13.2073097 13.6980286,12.160688 C14.6399882,11.1349987 14.7865152,9.87905267 14.7865152,8.87429582 C14.7865152,7.9532687 14.6609206,6.69732264 13.6142989,5.67163335 C12.5676771,4.64594406 11.1442716,4.43661972 9.55340659,4.43661972 L4.88547371,4.43661972 L3.45307884,16.0879292 C1.35084819,14.4404374 5.68434189e-14,11.8779725 5.68434189e-14,9 C5.68434189e-14,4.02943725 4.02943725,0 9,0 C13.9705627,0 18,4.02943725 18,9 C18,13.9705627 13.9705627,18 9,18 C8.23700819,18 7.49619203,17.9050548 6.78872178,17.7263347 Z M9.05102816,7.36716054 C9.55340659,7.36716054 10.3069742,7.3462281 10.7674878,7.8276741 C10.9768121,8.03699844 11.165204,8.41378226 11.165204,9.02082286 C11.165204,9.33480937 11.1233392,9.85812024 10.7256229,10.2558365 C10.1813796,10.8000798 9.2603525,10.8419447 8.75797408,10.8419447 L7.64855505,10.8419447 L8.06720374,7.36716054 L9.05102816,7.36716054 Z" id="Powerkit"></path></g></svg>';

		$svg = 'data:image/svg+xml;base64,' . base64_encode( $svg ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode

		add_menu_page(
			esc_html__( 'All-in-One Utilities', 'all-in-one-utilities' ),
			esc_html__( 'All-in-One Utilities', 'all-in-one-utilities' ),
			'manage_options',
			aiowpu_get_page_slug( $this->menu_slug ),
			array( $this, 'settings_page' ),
			$svg
		);
	}

	/**
	 * Settings
	 */
	public function settings_page() {

		aiowpu_uuid_hash();

		$page_link = aiowpu_get_page_url( $this->menu_slug, 'admin' );

		// Check wpnonce.
		if ( ! isset( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ) ) ) {
			return;
		}

		// Filter modules.
		if ( isset( $_REQUEST['filter'] ) ) {
			$filter = sanitize_key( $_REQUEST['filter'] );
		}

		// Output Message.
		if ( $this->msg ) {
			echo wp_kses( $this->msg, 'post' );
		}

		require_once AIOWPU_PATH . 'admin/partials/all-in-one-wp-utilities-admin-display.php';

	}

	/**
	 * Handler actions.
	 */
	public function handler_actions() {

		// Check wpnonce.
		if ( ! isset( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ) ) ) {
			return;
		}

		if ( ! isset( $_REQUEST['action'] ) ) {
			return;
		}

		$action = sanitize_title( wp_unslash( $_REQUEST['action'] ) );

		// Bulk Actions.
		if ( isset( $_REQUEST['checked'] ) && is_array( $_REQUEST['checked'] ) ) {
			$checked = array_map( 'sanitize_key', $_REQUEST['checked'] );

			foreach ( $checked as $slug ) {
				if ( 'activate-selected' === $action ) {
					$this->set_module_state( $slug, 1 );
				} elseif ( 'deactivate-selected' === $action ) {
					$this->set_module_state( $slug, 0 );
				}
			}
		}

		if ( ! isset( $_REQUEST['slug'] ) ) { // Input var ok.
			return;
		}

		$slug = sanitize_key( $_REQUEST['slug'] ); // Input var ok.

		// Activate module.
		if ( 'activate' === $action && $slug ) {
			$this->set_module_state( $slug, 1 );
		}

		// Deactivate module.
		if ( 'deactivate' === $action && $slug ) {
			$this->set_module_state( $slug, 0 );
		}
	}

	/**
	 * Set state module.
	 *
	 * @param string $slug  The slug module.
	 * @param bool   $state The state module.
	 */
	public function set_module_state( $slug, $state ) {

		update_option( sprintf( 'aiowpu_enabled_%s', $slug ), $state );

		$moduel_name = $this->modules[ $slug ]['name'];

		if ( $state ) {
			$this->msg = sprintf( '<div id="message" class="updated fade"><p><strong>%s</strong> %s</p></div>', $moduel_name, esc_html__( 'module activated.', 'all-in-one-utilities' ) );
		} else {
			$this->msg = sprintf( '<div id="message" class="updated fade"><p><strong>%s</strong> %s</p></div>', $moduel_name, esc_html__( 'module deactivated.', 'all-in-one-utilities' ) );
		}
	}

}
