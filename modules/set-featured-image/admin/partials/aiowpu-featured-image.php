<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://2bytecode.com
 * @since      1.0.0
 *
 * @package    All_In_One_Utilities/modules/set-featured-image
 * @subpackage All_In_One_Utilities/modules/set-featured-image/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<input
	id="dfi_id"
	type="hidden"
	value="<?php echo esc_attr( $value ); ?>"
	name="aiowpu_featured_image_id"/>

<a
	id="dfi-set-dfi"
	class="button"
	title="<?php esc_attr_e( 'Select default featured image', 'all-in-one-utilities' ); ?>"
	href="#">
	<span style="margin-top: 3px;" class="dashicons dashicons-format-image"></span>
	<?php esc_html_e( 'Select default featured image', 'all-in-one-utilities' ); ?>
</a>

<div style="margin-top:5px;">
	<a
		id="dfi-no-fdi"
		class="<?php echo esc_attr( $rm_btn_class ); ?>"
		title="<?php esc_attr_e( "Don't use a default featured image", 'all-in-one-utilities' ); ?>"
		href="#">
		<?php esc_html_e( "Don't use a default featured image", 'all-in-one-utilities' ); ?>
	</a>
</div>
