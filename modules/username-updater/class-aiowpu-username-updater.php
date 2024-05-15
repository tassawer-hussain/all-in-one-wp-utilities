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
 * @package    All_In_One_Utilities/modules
 * @subpackage All_In_One_Utilities/modules/username-updater
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( class_exists( 'Aiowpu_Module' ) ) {
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
	 * @package    All_In_One_Utilities/modules
	 * @subpackage All_In_One_Utilities/modules/username-updater
	 * @author     2ByteCode <support@2bytecode.com>
	 */
	class Aiowpu_Username_Updater extends Aiowpu_Module {

		/**
		 * Register module
		 */
		public function register() {
			$this->name     = esc_html__( 'Username Updater', 'all-in-one-utilities' );
			$this->desc     = esc_html__( 'Allow admin to update username of any user easily and notify the user via email.', 'all-in-one-utilities' );
			$this->slug     = 'username_updater';
			$this->type     = 'default';
			$this->category = 'tools';
			$this->priority = 110;
			$this->public   = true;
			$this->enabled  = true;
			$this->links    = array(
				array(
					'name'   => esc_html__( 'View documentation', 'all-in-one-utilities' ),
					'url'    => aiowpu_get_setting( 'documentation' ) . '/username-updater/',
					'target' => '_blank',
				),
			);
		}

		/**
		 * Initialize module
		 */
		public function initialize() {

			// Admin area hooks.
			require_once dirname( __FILE__ ) . '/admin/class-aiowpu-username-updater-admin.php';

			new Aiowpu_Username_Updater_Admin( $this->slug );
		}

	}

	new Aiowpu_Username_Updater();
}
