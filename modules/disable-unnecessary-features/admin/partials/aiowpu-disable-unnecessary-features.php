<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://2bytecode.com
 * @since      1.0.0
 *
 * @package    All_In_One_Wp_Utilities/modules/disable-unnecessary-features
 * @subpackage All_In_One_Wp_Utilities/modules/disable-unnecessary-features/admin
 */

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div data-id="aiowpu-disable-unnecessary-features" class="b2c-admin-settings tab tab-active">

	<div class="section_left">
		<form action="options.php#aiowpu-disable-unnecessary-features" method="post" enctype="multipart/form-data">

			<?php
			// output security fields.
			settings_fields( $this->option_group );

			// output setting sections.
			do_settings_sections( $this->admin_page_slug . '-' . $this->option_group );

			// submit button.
			submit_button();
			?>

		</form>
	</div>

	<div class="section_right">
		<?php do_action( 'aiowpu_disable_unnecessary_features_settings_rigt_section' ); ?>
	</div>

</div>
