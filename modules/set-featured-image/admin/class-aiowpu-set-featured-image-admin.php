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

if ( class_exists( 'Aiowpu_Module_Admin' ) ) {
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
	final class Aiowpu_Set_Featured_Image_Admin extends Aiowpu_Module_Admin {

		/**
		 * Holds the instance.
		 *
		 * @var self
		 */
		protected static $inst = null;

		/**
		 * Create instance of this class.
		 *
		 * @return self
		 */
		public static function instance() {
			if ( null === static::$inst ) {
				static::$inst = new self();
			}
			return static::$inst;
		}

		/**
		 * Initialize module
		 */
		public function initialize() {

			// Admin area hooks.
			// add the settings field to the media page.
			add_action( 'admin_init', array( $this, 'aiowpu_media_setting_for_featured_image' ) );

			// get the preview image ajax call.
			add_action( 'wp_ajax_aiowpu_change_featured_image_preview', array( $this, 'aiowpu_preview_featured_image_in_media_settings' ) );

		}

		/**
		 * Register the stylesheets and JavaScript for the admin area.
		 *
		 * @param string $page The current admin screen..
		 */
		public function admin_enqueue_scripts( $page ) {

			if ( 'options-media.php' !== $page ) {
				return;
			}

			wp_enqueue_style( 'aiowpu-featured-image', plugin_dir_url( __FILE__ ) . 'css/style.css', '', aiowpu_get_setting( 'version' ), 'all' );

			wp_enqueue_media(); // scripts used for uploader.

			// Remove the default WordPress role dropdown from the DOM.
			wp_enqueue_script( 'aiowpu-featured-image', plugin_dir_url( __FILE__ ) . 'js/script.js', array( 'jquery' ), aiowpu_get_setting( 'version' ), true );
			wp_localize_script(
				'aiowpu-featured-image',
				'aiowpu_featured_image',
				array(
					'manager_title'  => __( 'Select default featured image', 'all-in-one-utilities' ),
					'manager_button' => __( 'Set default featured image', 'all-in-one-utilities' ),
					'aiowpufi_nonce' => wp_create_nonce( 'aiowpu_featured_image_nonce' ),
				)
			);

		}

		/**
		 * Register the setting on the media settings page.
		 *
		 * @return void
		 */
		public function aiowpu_media_setting_for_featured_image() {

			// Step 1 - Register the settings.
			register_setting(
				'media',                            // settings page.
				'aiowpu_featured_image_id',         // option name.
				array( &$this, 'aiowpu_validate_featured_image_id' ) // validation callback.
			);

			// Step 2 - Register the section.
			add_settings_section(
				'aiowpu-featured-image',
				sprintf( '<span id="%s">%s</span>', aiowpu_get_page_slug( $this->slug ), esc_html__( 'Set Default Featured Image', 'all-in-one-utilities' ) ),
				array( &$this, 'aiowpu_featured_image_section_description' ),
				'media'
			);

			// Step 3 - Add fields to the section. Can be add any number of fields. - Added 4 fields.
			add_settings_field(
				'aiowpu_dfi',
				_x( 'Default featured image', 'Label on the settings page.', 'all-in-one-utilities' ),
				array( &$this, 'aiowpu_featured_image_settings_html' ),
				'media',
				'aiowpu-featured-image',
			);
		}

		/**
		 * Is the given input a valid image.
		 *
		 * @param string|int $thumbnail_id The saving thumbnail.
		 *
		 * @return int|false
		 */
		public function aiowpu_validate_featured_image_id( $thumbnail_id ) {

			if ( wp_attachment_is_image( absint( $thumbnail_id ) ) ) {
				return absint( $thumbnail_id );
			}

			return false;
		}

		/**
		 * Description for featured image section on media page.
		 *
		 * @return void
		 */
		public function aiowpu_featured_image_section_description() {

			echo '<p>' . esc_html__( 'The image set here will serve as the default featured image for all post types that support thumbnails, in instances where the user hasn\'t configured one.', 'all-in-one-utilities' ) . '</p>';

		}

		/**
		 * Display the buttons and a preview on the media settings page.
		 *
		 * @return void
		 */
		public function aiowpu_featured_image_settings_html() {

			$value = get_option( 'aiowpu_featured_image_id' );

			$rm_btn_class = 'button button-disabled';

			if ( ! empty( $value ) ) {
				echo $this->preview_featured_image( $value ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				$rm_btn_class = 'button';
			}

			include apply_filters( 'aiowpu_featured_image_html', plugin_dir_path( __FILE__ ) . 'partials/aiowpu-featured-image.php' );

		}

		/**
		 * The callback for the ajax call when the DFI changes
		 *
		 * @return void It's an ajax call.
		 */
		public function aiowpu_preview_featured_image_in_media_settings() {

			// check nonce.
			check_ajax_referer( 'aiowpu_featured_image_nonce', 'nonce' );

			if ( ! empty( $_POST['image_id'] ) && absint( $_POST['image_id'] ) ) {
				$img_id = absint( $_POST['image_id'] );
				echo wp_kses_post( $this->preview_featured_image( $img_id ) );
			}
			die(); // ajax call..

		}

		/**
		 * Get an image and wrap it in a div
		 *
		 * @param int $image_id A valid attachment image ID.
		 *
		 * @return string
		 */
		public function preview_featured_image( $image_id ) {

			$output  = '<div id="preview-image" style="float:left; padding: 0 5px 0 0;">';
			$output .= wp_get_attachment_image( $image_id, array( 80, 60 ), true );
			$output .= '</div>';

			return $output;
		}

	}

}
