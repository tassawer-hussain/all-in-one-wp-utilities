<?php
/**
 * Output the roles checklist.
 *
 * @var $roles array All WordPress roles in name => label pairs.
 * @var $user_roles array An array of role names belonging to the current user.
 *
 * @package    All_In_One_Wp_Utilities/modules/user-multiple-roles
 * @subpackage All_In_One_Wp_Utilities/modules/user-multiple-roles/templates
 */

?>
<h3><?php esc_html_e( 'Permissions', 'all-in-one-wp-utilities' ); ?></h3>

<table class="form-table">

	<tr>
		<th><?php esc_html_e( 'User Roles', 'all-in-one-wp-utilities' ); ?></th>

		<td>
			<?php
			foreach ( $roles as $name => $label ) :
				$input_uniq_id = uniqid();
				?>

				<label for="aiowpu-multiple-roles-<?php echo esc_attr( $name . '-' . $input_uniq_id ); ?>">
					<input
						id="aiowpu-multiple-roles-<?php echo esc_attr( $name . '-' . $input_uniq_id ); ?>"
						type="checkbox"
						name="aiowpu_multiple_roles[]"
						value="<?php echo esc_attr( $name ); ?>"

						<?php
						if ( ! is_null( $user_roles ) ) : // Edit user page.
							checked( in_array( $name, $user_roles, true ) );
						elseif ( ! empty( $selected_roles ) ) : // Add new user page.
							checked( in_array( $name, $selected_roles, true ) );
						endif;
						?>
					/>
					<?php echo esc_html( translate_user_role( $label ) ); ?>
				</label>
				<br />

			<?php endforeach; ?>
		</td>

	</tr>
</table>
