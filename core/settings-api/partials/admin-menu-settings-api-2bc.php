<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://2bytecode.com/
 * @since      1.0.0
 *
 * @package    2ByteCode/Settings_Api
 * @subpackage 2ByteCode/Settings_Api/partials
 */

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">
	<div id="icon-themes" class="icon32"></div>
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	<?php settings_errors(); ?>

	<h2 class="nav-tab-wrapper">
		<?php
		$counter = 1;
		foreach ( $this->settings_tabs as $tab_key => $tab_title ) :
			$is_active = ( 1 === $counter ) ? ' nav-tab-active' : '';
			printf(
				'<a href="#" class="nav-tab b2c-settings-tab b2c-tab %1$s" data-id="%2$s">%3$s</a>',
				esc_attr( $is_active ),
				esc_attr( $tab_key ),
				esc_html( $tab_title )
			);
			$counter++;
		endforeach;
		?>
	</h2>

	<?php do_action( 'b2c_settings_api_sections' ); ?>

</div>
