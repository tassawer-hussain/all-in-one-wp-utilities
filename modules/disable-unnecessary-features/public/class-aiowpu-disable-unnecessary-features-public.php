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

if ( class_exists( 'Aiowpu_Module_Public' ) ) {
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
	class Aiowpu_Disable_Unnecessary_Features_Public extends Aiowpu_Module_Public {

		/**
		 * Holds the saved options.
		 *
		 * @var self
		 */
		protected $options;

		/**
		 * Initialize
		 */
		public function initialize() {

			$this->options = get_option( 'aiowpu_disable_unnecessary_features_options', Aiowpu_Default_Settings_Options::aiowpu_disable_unnecessary_features_options() );

		}

		/**
		 * Load the required dependencies for this module.
		 */
		public function wp_enqueue_scripts() {

			// Disable Dashicons support for frontend.
			if ( $this->aiowpu_check_option_selected( 'dashicons' ) && ! is_user_logged_in() ) {

				wp_dequeue_style( 'dashicons' );
				wp_deregister_style( 'dashicons' );

			}

			// Disable Heartbeat support for frontend.
			if ( $this->aiowpu_check_option_selected( 'heartbeat' ) ) {

				global $pagenow;

				if ( 'post.php' !== $pagenow && 'post-new.php' !== $pagenow ) {
					wp_deregister_script( 'heartbeat' );
				}
			}
		}

		/**
		 * Check either the feature is enabled or disabled.
		 *
		 * @param string $suffix Option name.
		 * @return boolean true if feature is enabled.
		 */
		public function aiowpu_check_option_selected( $suffix ) {
			return ( isset( $this->options[ 'aiowpu_duf_' . $suffix ] ) && 1 === $this->options[ 'aiowpu_duf_' . $suffix ] );
		}

	}

}
