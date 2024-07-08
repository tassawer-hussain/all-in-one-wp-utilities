<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://2bytecode.com/
 * @since      1.0.0
 *
 * @package    2ByteCode
 * @subpackage 2ByteCode/Settings_Api
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Inlcude Class -> Bytecode_Settings_Fields_Callbacks.
require_once dirname( __FILE__ ) . '/classes/class-bytecode-settings-fields-callbacks.php';

if ( ! class_exists( 'ByteCode_Settings_API' ) ) {
	/**
	 * WordPress Settings API.
	 *
	 * Defines the plugin name, version, and enqueue the stylesheet and JavaScript for Settings API.
	 *
	 * @package    2ByteCode
	 * @subpackage 2ByteCode/Settings_Api
	 * @author     2ByteCode <support@2bytecode.com>
	 */
	class ByteCode_Settings_API {

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
		 * The settings page slug.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string    $setting_page_slug    Slug of the setting page.
		 */
		private $setting_page_slug;

		/**
		 * The settings page title.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string    $setting_page_title    Title of the setting page.
		 */
		private $setting_page_title;

		/**
		 * The settings page menu title.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string    $setting_menu_title    Title of the setting page menu.
		 */
		private $setting_menu_title;

		/**
		 * The settings page tabs.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string    $settings_tabs    Tabs of the setting page menu.
		 */
		public $settings_tabs;

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    1.0.0
		 * @param      string $plugin_name        The name of this plugin.
		 * @param      string $version            The version of this plugin.
		 * @param      string $setting_page_slug  Slug of the setting page.
		 * @param      string $setting_page_title Title of the setting page.
		 * @param      string $setting_menu_title Title of the setting menu.
		 */
		public function __construct( $plugin_name, $version, $setting_page_slug, $setting_page_title, $setting_menu_title ) {

			$this->plugin_name        = $plugin_name;
			$this->version            = $version;
			$this->setting_page_slug  = $setting_page_slug;
			$this->setting_page_title = $setting_page_title;
			$this->setting_menu_title = $setting_menu_title;

		}

		/**
		 * Register the stylesheets for the settings api.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_styles() {

			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_style( 'settings-api-2bc', plugin_dir_url( __FILE__ ) . 'css/settings-api-2bc.css', array(), $this->version, 'all' );

		}

		/**
		 * Register the JavaScript for the settings api.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_scripts() {

			wp_enqueue_media();
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_script( 'jquery-ui-core' );
			wp_enqueue_script( 'settings-api-2bc', plugin_dir_url( __FILE__ ) . 'js/settings-api-2bc.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( 'settings-api-2bc-color-alpha', plugin_dir_url( __FILE__ ) . 'js/wp-color-picker-alpha.min.js', array( 'wp-color-picker' ), $this->version, true );

		}

		/**
		 * Add settings menu in admin for configuration.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function b2c_settings_admin_menu() {

			add_submenu_page(
				'aiowpu_manager',
				$this->setting_page_title,
				$this->setting_menu_title,
				'manage_options',
				$this->setting_page_slug,
				array(
					$this,
					'b2c_settings_admin_menu_cb',
				),
				'100',
			);

		}

		/**
		 * Settings menu callback function.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function b2c_settings_admin_menu_cb() {
			/**
			 * Filter -> For Adding tabs
			 */
			$this->settings_tabs = apply_filters( 'bytecode_settings_api_tabs', $this->settings_tabs );

			require_once dirname( __FILE__ ) . '/partials/admin-menu-settings-api-2bc.php';
		}
	}

}
