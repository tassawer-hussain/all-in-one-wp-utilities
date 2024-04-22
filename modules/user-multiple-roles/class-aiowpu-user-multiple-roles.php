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
 * @package    All_In_One_Wp_Utilities/modules
 * @subpackage All_In_One_Wp_Utilities/modules/user-multiple-roles
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
	 * @package    All_In_One_Wp_Utilities/modules
	 * @subpackage All_In_One_Wp_Utilities/modules/user-multiple-roles
	 * @author     2ByteCode <support@2bytecode.com>
	 */
	class Aiowpu_User_Multiple_Roles extends Aiowpu_Module {

		/**
		 * Register module
		 */
		public function register() {
			$this->name     = esc_html__( 'User Multiple Roles', 'all-in-one-wp-utilities' );
			$this->desc     = esc_html__( 'Allow users to have multiple roles on one site', 'all-in-one-wp-utilities' );
			$this->slug     = 'user_multiple_roles';
			$this->type     = 'default';
			$this->category = 'tools';
			$this->priority = 100;
			$this->public   = true;
			$this->enabled  = true;
			$this->links    = array(
				array(
					'name'   => esc_html__( 'View documentation', 'all-in-one-wp-utilities' ),
					'url'    => aiowpu_get_setting( 'documentation' ) . '/user-multiple-roles/',
					'target' => '_blank',
				),
			);
		}

		/**
		 * Initialize module
		 */
		public function initialize() {

			// Helpers Functions for the module.
			require_once dirname( __FILE__ ) . '/helpers/helper-user-multiple-roles.php';

			// Admin area hooks.
			require_once dirname( __FILE__ ) . '/admin/class-aiowpu-user-multiple-roles-admin.php';

			new Aiowpu_User_Multiple_Roles_Admin( $this->slug );
		}

	}

	new Aiowpu_User_Multiple_Roles();
}
