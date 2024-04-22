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
	 * @package    All_In_One_Wp_Utilities/modules
	 * @subpackage All_In_One_Wp_Utilities/modules/set-featured-image
	 * @author     2ByteCode <support@2bytecode.com>
	 */
	class Aiowpu_Set_Featured_Image_Public extends Aiowpu_Module_Public {
		/**
		 * Initialize
		 */
		public function initialize() {

			// set dfi meta key on every occasion.
			add_filter( 'get_post_metadata', array( $this, 'aiowpu_set_featured_image_meta_key' ), 10, 4 );

			// display a default featured image.
			add_filter( 'post_thumbnail_html', array( $this, 'aiowpu_display_featured_image' ), 20, 5 );

		}

		/**
		 * Add the aiowpu_featured_image_id to the meta data if needed.
		 *
		 * @param null|mixed $null      Should be null, we don't use it because we update the meta cache.
		 * @param int        $object_id ID of the object metadata is for.
		 * @param string     $meta_key  Optional. Metadata key. If not specified, retrieve all metadata for
		 *                              the specified object.
		 * @param bool       $single    Optional, default is false. If true, return only the first value of the
		 *                              specified meta_key. This parameter has no effect if meta_key is not specified.
		 *
		 * @return string|string[] Single metadata value, or array of values
		 */
		public function aiowpu_set_featured_image_meta_key( $null, $object_id, $meta_key, $single ) {

			// Only affect thumbnails on the frontend, do allow ajax calls.
			if ( ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) ) {
				return $null;
			}

			// Check only empty meta_key and '_thumbnail_id'.
			if ( ! empty( $meta_key ) && '_thumbnail_id' !== $meta_key ) {
				return $null;
			}

			// Check if this post type supports featured images.
			$post_type = get_post_type( $object_id );
			if ( false !== $post_type && ! post_type_supports( $post_type, 'thumbnail' ) ) {
				return $null; // post type does not support featured images.
			}

			// Get current Cache.
			$meta_cache = wp_cache_get( $object_id, 'post_meta' );

			/**
			 * Empty objects probably need to be initiated.
			 *
			 * @see get_metadata() in /wp-includes/meta.php
			 */
			if ( ! $meta_cache ) {
				$meta_cache = update_meta_cache( 'post', array( $object_id ) );
				if ( ! empty( $meta_cache[ $object_id ] ) ) {
					$meta_cache = $meta_cache[ $object_id ];
				} else {
					$meta_cache = array();
				}
			}

			// Is the _thumbnail_id present in cache?
			if ( ! empty( $meta_cache['_thumbnail_id'][0] ) ) {
				return $null; // it is present, don't check anymore.
			}

			// Get the Default Featured Image ID.
			$aiowpu_featured_image_id = get_option( 'aiowpu_featured_image_id' );

			// Set the dfi in cache.
			$meta_cache['_thumbnail_id'][0] = apply_filters( 'aiowpu_featured_image_id', $aiowpu_featured_image_id, $object_id );
			wp_cache_set( $object_id, $meta_cache, 'post_meta' );

			return $null;
		}

		/**
		 * Set a default featured image if it is missing
		 *
		 * @param string         $html              The post thumbnail HTML.
		 * @param int            $post_id           The post ID.
		 * @param int            $post_thumbnail_id The post thumbnail ID.
		 * @param string|int[]   $size              The post thumbnail size. Image size or array of width and height.
		 * @param string|mixed[] $attr              values (in that order). Default 'post-thumbnail'.
		 *
		 * @return string
		 */
		public function aiowpu_display_featured_image( $html, $post_id, $post_thumbnail_id, $size, $attr ) {

			$default_thumbnail_id = get_option( 'aiowpu_featured_image_id' ); // select the default thumb.

			// If an image is set return that image.
			if ( (int) $default_thumbnail_id !== (int) $post_thumbnail_id ) {
				return $html;
			}

			// Attributes can be a query string, parse that.
			if ( is_string( $attr ) ) {
				wp_parse_str( $attr, $attr );
			}

			if ( isset( $attr['class'] ) ) {
				// There already are classes, we trust those.
				$attr['class'] .= ' aiowpu-featured-img';
			} else {
				// No classes in the attributes, try to get them form the HTML.
				$img = new \WP_HTML_Tag_Processor( $html );
				if ( $img->next_tag() ) {
					$attr['class'] = trim( $img->get_attribute( 'class' ) . ' aiowpu-featured-img' );
				}
			}

			$html = wp_get_attachment_image( $default_thumbnail_id, $size, false, $attr );
			return apply_filters( 'aiowpu_featured_image_html', $html, $post_id, $default_thumbnail_id, $size, $attr );
		}

	}

}
