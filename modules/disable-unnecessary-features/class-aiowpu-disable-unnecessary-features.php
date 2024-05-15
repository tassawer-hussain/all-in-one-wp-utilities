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
 * @subpackage All_In_One_Utilities/modules/disable-unnecessary-features
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
	 * @subpackage All_In_One_Utilities/modules/disable-unnecessary-features
	 * @author     2ByteCode <support@2bytecode.com>
	 */
	class Aiowpu_Disable_Unnecessary_Features extends Aiowpu_Module {

		/**
		 * Register module
		 */
		public function register() {
			$this->name     = esc_html__( 'Disable Unnecessary Features', 'all-in-one-utilities' );
			$this->desc     = esc_html__( 'Allow you to disable all unnecessary WordPress features to speed up your website', 'all-in-one-utilities' );
			$this->slug     = 'disable_unnecessary_features';
			$this->type     = 'default';
			$this->category = 'tools';
			$this->priority = 130;
			$this->public   = true;
			$this->enabled  = false;
			$this->links    = array(
				array(
					'name' => esc_html__( 'Settings', 'all-in-one-utilities' ),
					'url'  => aiowpu_get_page_url( 'settings#aiowpu-disable-unnecessary-features', 'admin' ),
				),
				array(
					'name'   => esc_html__( 'View documentation', 'all-in-one-utilities' ),
					'url'    => aiowpu_get_setting( 'documentation' ) . '/disable-unnecessary-features/',
					'target' => '_blank',
				),
			);
		}

		/**
		 * Initialize module
		 */
		public function initialize() {

			// Exclude the featured image functionality for specific cases.
			require_once dirname( __FILE__ ) . '/helpers/helper-disable-unncessary-features.php';

			// Admin area hooks.
			require_once dirname( __FILE__ ) . '/admin/class-aiowpu-disable-unnecessary-features-admin.php';
			new Aiowpu_Disable_Unnecessary_Features_Admin( $this->slug );

			// Public area hooks.
			require_once dirname( __FILE__ ) . '/public/class-aiowpu-disable-unnecessary-features-public.php';
			new Aiowpu_Disable_Unnecessary_Features_Public( $this->slug );

		}

	}

	new Aiowpu_Disable_Unnecessary_Features();
}
