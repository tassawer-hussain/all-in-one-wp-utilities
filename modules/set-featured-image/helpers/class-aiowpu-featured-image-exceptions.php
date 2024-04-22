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
 * @subpackage All_In_One_Wp_Utilities/modules/set-featured-image
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

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
 * @subpackage All_In_One_Wp_Utilities/modules/set-featured-image
 * @author     2ByteCode <support@2bytecode.com>
 */
class Aiowpu_Featured_Image_Exceptions {

	/**
	 * Exclude featured image from shortcode: wpuf_edit
	 *
	 * @param mixed  $false unused, just pass along.
	 * @param string $tag The shortcode.
	 *
	 * @return mixed
	 */
	public static function wp_user_frontend_pre( $false, $tag ) {
		if ( 'wpuf_edit' === $tag ) {
			add_filter( 'aiowpu_featured_image_id', '__return_null' );
		}

		return $false;
	}

	/**
	 * Exclude featured imagedfi from shortcode: wpuf_edit
	 *
	 * @param mixed  $output unused, just pass along.
	 * @param string $tag The shortcode.
	 *
	 * @return mixed
	 */
	public static function wp_user_frontend_after( $output, $tag ) {
		if ( 'wpuf_edit' === $tag ) {
			remove_filter( 'aiowpu_featured_image_id', '__return_null' );
		}

		return $output;
	}

	/**
	 * Exclude wp all import, the DFI during the import.
	 *
	 * @param int $dfi_id The DFI id.
	 *
	 * @return null|int
	 */
	public static function wp_all_import_dfi_workaround( $dfi_id ) {
		if ( function_exists( 'wp_all_import_get_import_id' ) && is_numeric( wp_all_import_get_import_id() ) ) {
			return null; // If a post is imported with WP All Import, set DFI id to null.
		} else {
			return $dfi_id;
		}
	}

}
