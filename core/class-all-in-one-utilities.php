<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://2bytecode.com
 * @since      1.0.0
 *
 * @package    All_In_One_Utilities
 * @subpackage All_In_One_Utilities/core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'All_In_One_Utilities' ) ) {
	/**
	 * The core plugin class.
	 *
	 * This is used to define internationalization, admin-specific hooks, and
	 * public-facing site hooks.
	 *
	 * Also maintains the unique identifier of this plugin as well as the current
	 * version of the plugin.
	 *
	 * @since      1.0.0
	 * @package    All_In_One_Utilities
	 * @subpackage All_In_One_Utilities/core
	 * @author     2ByteCode <support@2bytecode.com>
	 */
	class All_In_One_Utilities {

		/**
		 * The loader that's responsible for maintaining and registering all hooks that power
		 * the plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      All_In_One_Wp_Utilities_Loader    $loader    Maintains and registers all hooks for the plugin.
		 */
		protected $loader;

		/**
		 * The unique identifier of this plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
		 */
		protected $plugin_name;

		/**
		 * The current version of the plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string    $version    The current version of the plugin.
		 */
		protected $version;

		/**
		 * The plugin data array.
		 *
		 * @var array $data The plugin data array.
		 */
		public $data = array();

		/**
		 * The plugin settings array.
		 *
		 * @var array $settings The plugin data array.
		 */
		public $settings = array();

		/**
		 * Load the required dependencies for this plugin.
		 *
		 * Include the following files that make up the plugin:
		 *
		 * - All_In_One_Wp_Utilities_Loader. Orchestrates the hooks of the plugin.
		 * - All_In_One_Wp_Utilities_i18n. Defines internationalization functionality.
		 * - All_In_One_Wp_Utilities_Admin. Defines all hooks for the admin area.
		 * - All_In_One_Wp_Utilities_Public. Defines all hooks for the public side of the site.
		 *
		 * Create an instance of the loader which will be used to register the hooks
		 * with WordPress.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function load_dependencies() {

			// The class responsible for orchestrating the actions and filters of the core plugin.
			require_once AIOWPU_CORE_PATH . 'class-all-in-one-wp-utilities-loader.php';

			// Include core.
			require_once AIOWPU_CORE_PATH . 'aiowpu-api.php';
			require_once AIOWPU_CORE_PATH . 'aiowpu-functions.php';
			require_once AIOWPU_CORE_PATH . 'aiowpu-helpers.php';

			// Include core classes.
			require_once AIOWPU_CORE_PATH . 'class-aiowpu-module.php';
			require_once AIOWPU_CORE_PATH . 'class-aiowpu-module-admin.php';
			require_once AIOWPU_CORE_PATH . 'class-aiowpu-module-public.php';

			// Include 2ByteCode Setting API Library.
			require_once AIOWPU_CORE_PATH . 'class-aiowpu-default-settings-options.php';
			require_once AIOWPU_CORE_PATH . 'settings-api/classes/class-b2c-settings-fields-callbacks.php';
			require_once AIOWPU_CORE_PATH . 'settings-api/class-settings-api-2bc.php';

			// The class responsible for defining all actions that occur in the admin area.
			require_once AIOWPU_PATH . 'admin/class-all-in-one-wp-utilities-admin.php';

			// The class responsible for defining all actions that occur in the public-facing side.
			require_once AIOWPU_PATH . 'public/class-all-in-one-wp-utilities-public.php';

			// Include modules.
			aiowpu_load_files( 'modules' );

			$this->loader = new All_In_One_Wp_Utilities_Loader();

		}

		/**
		 * Register all of the hooks related to the admin area functionality
		 * of the plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function define_admin_hooks() {

			$plugin_admin = new All_In_One_Wp_Utilities_Admin( $this->get_plugin_name(), $this->get_version() );

			$this->loader->add_action( 'init', $plugin_admin, 'handler_actions' );
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
			$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_menu_page' );

			// Create an instance of settings API class.
			$setting_menu = new Settings_API_2BC( $this->get_plugin_name(), $this->get_version(), 'aiowpu_settings', esc_html__( 'All-in-One Utilities Settings', 'all-in-one-utilities' ), esc_html__( 'Settings', 'all-in-one-utilities' ) );
			$this->loader->add_action( 'admin_enqueue_scripts', $setting_menu, 'enqueue_styles' );
			$this->loader->add_action( 'admin_enqueue_scripts', $setting_menu, 'enqueue_scripts' );
			$this->loader->add_action( 'admin_menu', $setting_menu, 'b2c_settings_admin_menu' );

		}

		/**
		 * Register all of the hooks related to the public-facing functionality
		 * of the plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function define_public_hooks() {

			$plugin_public = new All_In_One_Wp_Utilities_Public( $this->get_plugin_name(), $this->get_version() );

			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		}

		/**
		 * Run the loader to execute all of the hooks with WordPress.
		 *
		 * @since    1.0.0
		 */
		public function run() {

			if ( ! function_exists( 'get_plugin_data' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			// Get plugin data.
			$plugin_data = get_plugin_data( AIOWPU_PATH . '/all-in-one-utilities.php' );

			if ( defined( 'ALL_IN_ONE_UTILITIES_VERSION' ) ) {
				$this->version = ALL_IN_ONE_UTILITIES_VERSION;
			} else {
				$this->version = $plugin_data['Version'];
			}

			$this->plugin_name = 'all-in-one-utilities';

			// Settings.
			$this->settings = array(
				'name'          => esc_html__( 'All-in-One WP Utilities', 'all-in-one-utilities' ),
				'version'       => $plugin_data['Version'],
				'documentation' => $plugin_data['AuthorURI'] . '/documentation/all-in-one-utilities', // https://2bytecode.com/documentation/all-in-one-utilities/ .
			);

			$this->load_dependencies();
			$this->define_admin_hooks();
			$this->define_public_hooks();

			// Actions.
			$this->loader->add_action( 'aiowpu_plugin_activation', $this, 'activation' );
			$this->loader->add_action( 'plugins_loaded', $this, 'check_version' );

			$this->loader->run();
		}

		/**
		 * Hook activation
		 */
		public function activation() {

			if ( get_option( 'aiowpu_db_version' ) ) {
				return;
			}

			update_option( 'aiowpu_db_version', aiowpu_raw_setting( 'version' ), true );
		}

		/**
		 * Check current version
		 */
		public function check_version() {

			// Version Data.
			$new = aiowpu_raw_setting( 'version' );

			// Get db version.
			$current = get_option( 'aiowpu_db_version', $new );

			// If versions don't match.
			if ( ! empty( $current ) && ! empty( $new ) && $current !== $new ) {

				/**
				 * If different versions call a special hook.
				 *
				 * @param string $current Current version.
				 * @param string $new     New version.
				 */
				do_action( 'aiowpu_plugin_upgrade', $current, $new );

				update_option( 'aiowpu_db_version', $new );

			} elseif ( ! empty( $new ) && $current !== $new ) {

				update_option( 'aiowpu_db_version', $new );
			}
		}

		/**
		 * The name of the plugin used to uniquely identify it within the context of
		 * WordPress and to define internationalization functionality.
		 *
		 * @since     1.0.0
		 * @return    string    The name of the plugin.
		 */
		public function get_plugin_name() {
			return $this->plugin_name;
		}

		/**
		 * The reference to the class that orchestrates the hooks with the plugin.
		 *
		 * @since     1.0.0
		 * @return    All_In_One_Wp_Utilities_Loader    Orchestrates the hooks of the plugin.
		 */
		public function get_loader() {
			return $this->loader;
		}

		/**
		 * Retrieve the version number of the plugin.
		 *
		 * @since     1.0.0
		 * @return    string    The version number of the plugin.
		 */
		public function get_version() {
			return $this->version;
		}

		/**
		 * Returns true if has setting.
		 *
		 * @param string $name The name.
		 */
		public function has_setting( $name ) {
			return isset( $this->settings[ $name ] );
		}

		/**
		 * Returns a setting.
		 *
		 * @param string $name The name.
		 */
		public function get_setting( $name ) {
			return isset( $this->settings[ $name ] ) ? $this->settings[ $name ] : null;
		}

		/**
		 * Updates a setting.
		 *
		 * @param string $name  The name.
		 * @param mixed  $value The value.
		 */
		public function update_setting( $name, $value ) {
			$this->settings[ $name ] = $value;
			return true;
		}

		/**
		 * Returns data.
		 *
		 * @param string $name The name.
		 */
		public function get_data( $name ) {
			return isset( $this->data[ $name ] ) ? $this->data[ $name ] : null;
		}

		/**
		 * Sets data.
		 *
		 * @param string $name  The name.
		 * @param mixed  $value The value.
		 */
		public function set_data( $name, $value ) {
			$this->data[ $name ] = $value;
		}

	}

	/**
	 * Begins execution of the plugin.
	 *
	 * The main function responsible for returning the one true Instance to functions everywhere.
	 * Use this function like you would a global variable, except without needing to declare the global.
	 *
	 * Example: <?php $aiowp_utilities = aiowpu_init(); ?>
	 *
	 * @since    1.0.0
	 */
	function aiowpu_init() {

		// Globals.
		global $aiowpu_instance;

		// Init.
		if ( ! isset( $aiowpu_instance ) ) {
			$aiowpu_instance = new All_In_One_Utilities();
			$aiowpu_instance->run();
		}

		return $aiowpu_instance;

	}

	// Initialize.
	aiowpu_init();
}
