<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://2bytecode.com
 * @since      1.0.0
 *
 * @package    All_In_One_Wp_Utilities/modules/username-updater
 * @subpackage All_In_One_Wp_Utilities/modules/username-updater/admin
 */

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">

	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<?php if ( isset( $this->error_msg ) ) { ?>
		<div class='error'>
			<p>
				<strong><?php echo esc_html( $this->error_msg ); ?></strong>
			</p>
		</div>
	<?php } ?>

	<?php if ( isset( $this->success_msg ) ) { ?>
		<div class='updated'>
			<p>
				<strong><?php echo esc_html( $this->success_msg ); ?></strong>
			</p>
		</div>
	<?php } ?>

</div>

<form method="post" id="user_udate" action="<?php echo esc_url( $_server['REQUEST_URI'] ); ?>">

	<table class="form-table">

		<tr class="user-user-login-wrap">
			<th><label for="olduser_login"><?php echo esc_html( __( 'Old Username', 'all-in-one-wp-utilities' ) ); ?></label></th>
			<td><strong><?php echo esc_html( $user_info->user_login ); ?></strong></td>
		</tr>

		<tr class="user-user-login-wrap">
			<th><label for="user_login"><?php echo esc_html( __( 'New Username', 'all-in-one-wp-utilities' ) ); ?></label></th>
			<td>
				<input
					type="text"
					name="user_login"
					class="regular-text"
					id="user_login"
					value="<?php echo ( ! empty( $_post['user_login'] ) ) ? esc_attr( $name ) : ''; ?>"/>
			</td>
		</tr>

		<tr>
			<th><?php echo esc_html( __( 'Send User Notification', 'all-in-one-wp-utilities' ) ); ?></th>
			<td>
				<label for="user_notification">
					<input
						type="checkbox"
						name="user_notification"
						id="user_notification"
						value="yes"
						<?php echo isset( $_post['user_notification'] ) ? esc_attr( "checked='checked'" ) : ''; ?>/>
					<?php echo esc_html( __( 'Send the user an email about their updated username.', 'all-in-one-wp-utilities' ) ); ?>
				</label>
			</td>
		</tr>

	</table>

	<?php wp_nonce_field( 'aiowpu_update_username_action', '_csrfToken' ); ?>

	<input type="submit" name="aiowpu_update_username" id="submit" class="button button-primary" value="Update Username">

</form>

<p>
	<a href="<?php echo esc_url( admin_url( 'users.php', 'https' ) ); ?>">
	<-- <?php esc_html_e( 'Go Back', 'easy_username_updater' ); ?>
	</a>
</p>
