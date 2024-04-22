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
 * @package    All_In_One_Wp_Utilities/modules/user-multiple-roles
 * @subpackage All_In_One_Wp_Utilities/modules/user-multiple-roles/admin
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
	 * @package    All_In_One_Wp_Utilities/modules
	 * @subpackage All_In_One_Wp_Utilities/modules/user-multiple-roles
	 * @author     2ByteCode <support@2bytecode.com>
	 */
	class Aiowpu_User_Multiple_Roles_Admin extends Aiowpu_Module_Admin {

		/**
		 * Initialize
		 */
		public function initialize() {

			// Replace the default role column from the user list table.
			add_filter( 'manage_users_columns', array( $this, 'aiowpu_replace_user_role_column' ), 11 );
			add_filter( 'manage_users_custom_column', array( $this, 'aiowpu_output_user_role_column_content' ), 10, 3 );

			// Display user roles checklist on add new user, user edit screen and user profile pages.
			add_action( 'show_user_profile', array( $this, 'aiowpu_output_user_roles_checklist' ) );
			add_action( 'edit_user_profile', array( $this, 'aiowpu_output_user_roles_checklist' ) );
			add_action( 'user_new_form', array( $this, 'aiowpu_output_user_roles_checklist' ) );

			// Set the user roles on profile update.
			add_action( 'profile_update', array( $this, 'aiowpu_process_user_multiple_roles' ) );

			// For new user form (in Backoffice)
			// In multisite, user_register hook is too early so wp_mu_activate_user add user role after.
			if ( is_multisite() ) {

				if ( version_compare( get_bloginfo( 'version' ), '4.8', '>=' ) ) {
					add_filter( 'signup_site_meta', array( $this, 'aiowpu_add_roles_in_signup_meta_recently' ), 10, 7 );
				} else {
					add_action( 'after_signup_user', array( $this, 'aiowpu_add_roles_in_signup_meta' ), 10, 4 );
				}
				add_action( 'wpmu_activate_user', array( $this, 'aiowpu_add_roles_after_activation' ), 10, 3 );

			} else {
				add_action( 'user_register', array( $this, 'aiowpu_process_user_multiple_roles' ) );
			}

		}

		/**
		 * Register the stylesheets and JavaScript for the admin area.
		 *
		 * @param string $page The current admin screen..
		 */
		public function admin_enqueue_scripts( $page ) {

			if ( 'user-edit.php' !== $page && 'user-new.php' !== $page ) {
				return;
			}

			// Remove the default WordPress role dropdown from the DOM.
			wp_enqueue_script( 'aiowpu-user-multiple-roles', plugin_dir_url( __FILE__ ) . 'js/scripts.js', array( 'jquery' ), aiowpu_get_setting( 'version' ), true );

		}

		/**
		 * Remove the default role column and replace it with a custom version.
		 *
		 * @param  array $columns Existing columns in name => label pairs.
		 * @return array An updated list of columns.
		 */
		public function aiowpu_replace_user_role_column( $columns ) {

			// remove existing column.
			unset( $columns['role'] );

			$columns['aiowpu_multiple_roles_column'] = __( 'Roles', 'all-in-one-wp-utilities' );
			return $columns;

		}

		/**
		 * Output the content of the Roles column.
		 *
		 * @param  string $output  The existing HTML to display. Should be empty.
		 * @param  string $column  The name of the current column.
		 * @param  int    $user_id The user ID whose roles are about to be displayed.
		 * @return string The new HTML output.
		 */
		public function aiowpu_output_user_role_column_content( $output, $column, $user_id ) {

			if ( 'aiowpu_multiple_roles_column' !== $column ) {
				return $output;
			}

			$roles = aiowpu_get_user_roles( $user_id );

			ob_start();
			include apply_filters( 'aiowpu_multiple_roles_column_template', plugin_dir_path( dirname( __FILE__ ) ) . 'templates/user-listtable-column.php' );

			return ob_get_clean();
		}

		/**
		 * Output the checklist view. If the user is not allowed to edit roles,
		 * nothing will appear.
		 *
		 * @param object $user The current user object.
		 */
		public function aiowpu_output_user_roles_checklist( $user ) {

			if ( ! aiowpu_can_update_roles() ) {
				return;
			}

			wp_nonce_field( 'aiowpu-update-multiple-roles', 'aiowpu_multiple_roles_nonce' );

			$posted_data = filter_input_array( INPUT_POST );

			$roles          = aiowpu_get_editable_roles();
			$user_roles     = isset( $user->roles ) ? $user->roles : null;
			$selected_roles = isset( $posted_data['createuser'] ) ? $this->get_validated_roles_from_post() : array();

			include apply_filters( 'mdmr_checklist_template', plugin_dir_path( dirname( __FILE__ ) ) . 'templates/user-roles-checklist.php' );
		}

		/**
		 * Retreives the roles from the POST data and validates them.
		 */
		public function get_validated_roles_from_post() {

			$posted_data = filter_input_array( INPUT_POST );

			$roles = ( isset( $posted_data['aiowpu_multiple_roles'] ) && is_array( $posted_data['aiowpu_multiple_roles'] ) ) ? $posted_data['aiowpu_multiple_roles'] : array();

			$editable_roles = aiowpu_get_editable_roles();
			$ret_roles      = array();

			foreach ( $roles as $role ) {
				if ( in_array( $role, $editable_roles, true ) ) {
					$ret_roles[] = $role;
				}
			}

			return $roles;
		}

		/**
		 * Check if the aiowpu_multiple_roles_nonce is set and valid for the given action.
		 *
		 * @param string $action The nonce action.
		 * @return bool
		 */
		public function is_nonce_valid( $action ) {

			$posted_data = filter_input_array( INPUT_POST );

			return isset( $posted_data['aiowpu_multiple_roles_nonce'] )
			&& wp_verify_nonce( $posted_data['aiowpu_multiple_roles_nonce'], 'aiowpu-update-multiple-roles' );
		}

		/**
		 * Update the given user's roles as long as we've passed the nonce
		 * and permissions checks.
		 *
		 * @param int $user_id The user ID whose roles might get updated.
		 */
		public function aiowpu_process_user_multiple_roles( $user_id ) {

			// The checklist is not always rendered when this method is triggered on 'profile_update' (i.e. when updating a profile programmatically),
			// First check that the 'aiowpu_multiple_roles_nonce' is available, else bail. If we continue to process and update_roles(), all user roles will be lost.
			// We check for 'aiowpu_multiple_roles_nonce' rather than 'aiowpu_multiple_roles' as this input/variable will be empty if all role inputs are left unchecked.

			if ( ! $this->is_nonce_valid( 'aiowpu-update-multiple-roles' ) ) {
				return;
			}

			if ( ! aiowpu_can_update_roles() ) {
				return;
			}

			$new_roles = $this->get_validated_roles_from_post();

			aiowpu_update_roles( $user_id, $new_roles );
		}

		/**
		 * Add multiple roles after user activation
		 *
		 * @param int    $user_id Current user ID.
		 * @param string $password Current user password.
		 * @param array  $meta User meta data.
		 * @return void
		 */
		public function aiowpu_add_roles_after_activation( $user_id, $password, $meta ) {

			if ( ! empty( $meta['aiowpu_user_roles'] ) ) {
				aiowpu_update_roles( $user_id, $meta['aiowpu_user_roles'] );
			}

		}

		/**
		 * Undocumented function
		 *
		 * @param array  $meta Signup meta data. Default empty array.
		 * @param string $domain The requested domain.
		 * @param string $path The requested path.
		 * @param string $title The requested site title.
		 * @param string $user The user’s requested login name.
		 * @param string $user_email The user’s email address.
		 * @param string $key The user’s activation key.
		 * @return void
		 */
		public function aiowpu_add_roles_in_signup_meta_recently( $meta, $domain, $path, $title, $user, $user_email, $key ) {

			if ( ! $this->is_nonce_valid( 'aiowpu-update-multiple-roles' ) ) {
				return;
			}

			if ( ! aiowpu_can_update_roles() ) {
				return;
			}

			$new_roles = $this->get_validated_roles_from_post();

			if ( empty( $new_roles ) ) {
				return;
			}

			$meta['aiowpu_user_roles'] = $new_roles;

			return $meta;
		}

		/**
		 * Add multiple roles in the $meta array in wp_signups db table
		 *
		 * @param string $user The user’s requested login name.
		 * @param string $user_email The user’s email address.
		 * @param string $key The user’s activation key.
		 * @param array  $meta Signup meta data. Default empty array.
		 * @return void
		 */
		public function aiowpu_add_roles_in_signup_meta( $user, $user_email, $key, $meta ) {

			if ( ! $this->is_nonce_valid( 'aiowpu-update-multiple-roles' ) ) {
				return;
			}

			if ( ! aiowpu_can_update_roles() ) {
				return;
			}

			$new_roles = $this->get_validated_roles_from_post();
			if ( empty( $new_roles ) ) {
				return;
			}

			global $wpdb;

			// Get user signup
			// Suppress errors in case the table doesn't exist.
			$suppress = $wpdb->suppress_errors();
			$signup   = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->signups} WHERE user_email = %s", $user_email ) ); // phpcs:ignore

			$wpdb->suppress_errors( $suppress );

			if ( empty( $signup ) || is_wp_error( $signup ) ) {
				return new WP_Error( 'aiowpu_get_user_signups_failed' );
			}

			// Add multiple roles to a new array in meta var.
			$meta                      = maybe_unserialize( $meta );
			$meta['aiowpu_user_roles'] = $new_roles;
			$meta                      = maybe_serialize( $meta );

			// Update user signup with good meta.
			$where        = array( 'signup_id' => (int) $signup->signup_id );
			$where_format = array( '%d' );
			$formats      = array( '%s' );
			$fields       = array( 'meta' => $meta );
			$result       = $wpdb->update( $wpdb->signups, $fields, $where, $formats, $where_format ); // phpcs:ignore

			// Check for errors.
			if ( empty( $result ) && ! empty( $wpdb->last_error ) ) {
				return new WP_Error( 'aiowpu_update_user_signups_failed' );
			}
		}

	}

}
