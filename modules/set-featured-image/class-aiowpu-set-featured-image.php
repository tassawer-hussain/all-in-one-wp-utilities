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
 * @subpackage All_In_One_Utilities/modules/set-featured-image
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
	 * @subpackage All_In_One_Utilities/modules/set-featured-image
	 * @author     2ByteCode <support@2bytecode.com>
	 */
	class Aiowpu_Set_Featured_Image extends Aiowpu_Module {

		/**
		 * Register module
		 */
		public function register() {
			$this->name     = esc_html__( 'Set Featured Image', 'all-in-one-utilities' );
			$this->desc     = esc_html__( 'Allows you to select a default featured image in the media settings.', 'all-in-one-utilities' );
			$this->slug     = 'set_featured_image';
			$this->type     = 'default';
			$this->category = 'tools';
			$this->priority = 120;
			$this->public   = true;
			$this->enabled  = false;
			$this->links    = array(
				array(
					'name' => esc_html__( 'Settings', 'all-in-one-utilities' ),
					'url'  => admin_url( sprintf( 'options-media.php#%s', aiowpu_get_page_slug( $this->slug ) ) ),
				),
				array(
					'name'   => esc_html__( 'View documentation', 'all-in-one-utilities' ),
					'url'    => aiowpu_get_setting( 'documentation' ) . '/set-featured-image/',
					'target' => '_blank',
				),
			);
		}

		/**
		 * Initialize module
		 */
		public function initialize() {

			// Admin area hooks.
			require_once dirname( __FILE__ ) . '/admin/class-aiowpu-set-featured-image-admin.php';
			new Aiowpu_Set_Featured_Image_Admin( $this->slug );

			// Public area hooks.
			require_once dirname( __FILE__ ) . '/public/class-aiowpu-set-featured-image-public.php';
			new Aiowpu_Set_Featured_Image_Public( $this->slug );

			// Exclude the featured image functionality for specific cases.
			require_once dirname( __FILE__ ) . '/helpers/class-aiowpu-featured-image-exceptions.php';
			$aiowpu_featured_image_exceptions = new Aiowpu_Featured_Image_Exceptions();

			/**
			 * Exception: https://wordpress.org/plugins/wp-user-frontend/
			 *
			 * @see https://wordpress.org/support/topic/couldnt-able-to-edit-default-featured-image-from-post/
			 */
			add_filter( 'pre_do_shortcode_tag', array( $aiowpu_featured_image_exceptions, 'wp_user_frontend_pre' ), 9, 2 );
			add_filter( 'do_shortcode_tag', array( $aiowpu_featured_image_exceptions, 'wp_user_frontend_after' ), 9, 2 );

			/**
			 * Exception: https://www.wpallimport.com/
			 *
			 * @see https://wordpress.org/support/topic/importing-images-into-woocommerce-using-cron/
			 */
			add_filter( 'dfi_thumbnail_id', array( $aiowpu_featured_image_exceptions, 'wp_all_import_dfi_workaround' ), 9 );

		}

	}

	new Aiowpu_Set_Featured_Image();
}
