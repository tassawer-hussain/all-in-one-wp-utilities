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
 * @package    All_In_One_Utilities/modules/username-updater
 * @subpackage All_In_One_Utilities/modules/username-updater/admin
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
	 * @subpackage All_In_One_Utilities/modules/username-updater
	 * @author     2ByteCode <support@2bytecode.com>
	 */
	class Aiowpu_Username_Updater_Admin extends Aiowpu_Module_Admin {

		/**
		 * Error message.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string    $error_msg    Error message.
		 */
		protected $error_msg;

		/**
		 * Success message.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string    $success_msg    Success message.
		 */
		protected $success_msg;

		/**
		 * Initialize
		 */
		public function initialize() {

			// Update username action links displayed under each user in the Users list table.
			add_filter( 'user_row_actions', array( $this, 'aiowpu_display_update_username_link' ), 99, 2 );

			// Update username action links displayed under each user in the Network Admin Users list table.
			add_filter( 'ms_user_row_actions', array( $this, 'aiowpu_display_update_username_link' ), 99, 2 );

			// Admin sub-page to display update username form for a user.
			add_action( 'admin_menu', array( $this, 'aiowpu_create_update_username_subpage' ) );

			// Process the update username form submission.
			add_action( 'init', array( $this, 'aiowpu_update_username_on_form_submission' ) );

		}

		/**
		 * Register the stylesheets and JavaScript for the admin area.
		 *
		 * @param string $page The current admin screen..
		 */
		public function admin_enqueue_scripts( $page ) {
			wp_enqueue_style( 'aiowpu-username-updater', plugin_dir_url( __FILE__ ) . 'css/style.css', '', aiowpu_get_setting( 'version' ) );
		}

		/**
		 * Add link to update username in the user list table.
		 *
		 * @param string[] $actions     An array of action links to be displayed.
		 *                              Default 'Edit', 'Delete' for single site, and
		 *                              'Edit', 'Remove' for Multisite.
		 * @param WP_User  $user_object WP_User object for the currently listed user.
		 * @return string
		 */
		public function aiowpu_display_update_username_link( $actions, $user_object ) {

			$actions['update_username'] = "<a
				class='update_username'
				href='" . esc_url( wp_nonce_url( "admin.php?page=aiowpu-update-username&amp;update=$user_object->ID", 'aiowpu-update-username' ) ) . "'>"
			. __( 'Update username', 'all-in-one-utilities' ) .
			'</a>';

			return $actions;

		}

		/**
		 * Setting menu page.
		 *
		 * @since       1.0.0
		 */
		public function aiowpu_create_update_username_subpage() {

			add_users_page(
				__( 'All-In-One WP Utilities Update Username', 'all-in-one-utilities' ),
				__( 'Update Username', 'all-in-one-utilities' ),
				'manage_options',
				'aiowpu-update-username',
				array( $this, 'aiowpu_update_username_page_cb' )
			);

		}

		/**
		 * Update username subpage.
		 *
		 * @return void
		 */
		public function aiowpu_update_username_page_cb() {

			check_admin_referer( 'aiowpu-update-username' );

			if ( isset( $_GET['update'] ) && '' !== $_GET['update'] && is_numeric( $_GET['update'] ) ) {

				$user_id   = intval( $_GET['update'] );
				$user_info = get_userdata( $user_id );

				include apply_filters( 'aiowpu_update_username', plugin_dir_path( __FILE__ ) . 'partials/aiowpu-update-username.php' );

			} else { ?>
				<script>
					window.location='<?php echo esc_url( admin_url( 'users.php', 'https' ) ); ?>'
				</script>
					<?php
			}
		}

		/**
		 * Process the username update form submission.
		 *
		 * @return boolean
		 */
		public function aiowpu_update_username_on_form_submission() {

			$_post = filter_input_array( INPUT_POST );

			// EARLY BAIL.
			// - if username update form is not submitted.
			if ( ! isset( $_post['aiowpu_update_username'] ) ) {
				return false;
			}

			// - if nonce is not present.
			if ( ! wp_verify_nonce( $_post['_csrfToken'], 'aiowpu_update_username_action' ) ) {
				$this->error_msg = __( 'Invalid form submission.', 'all-in-one-utilities' );
				return false;
			}

			// Grab the usernames from the form submission.
			$name = sanitize_user( $_post['user_login'] );

			// - Username field is required.
			if ( empty( $name ) ) {
				$this->error_msg = __( 'Error : You can not enter an empty username.', 'all-in-one-utilities' );
				return false;
			}

			// - Username alread exist. Username must be unique.
			if ( username_exists( $name ) ) {
				$this->error_msg = sprintf( '%s (%s) %s.', esc_html__( 'Error: This username', 'all-in-one-utilities' ), $name, esc_html__( 'is already exist. ', 'all-in-one-utilities' ) );
				return false;
			}

			if ( isset( $_GET['update'] ) && '' !== $_GET['update'] && is_numeric( $_GET['update'] ) ) {
				$update_user_id = intval( $_GET['update'] );
			}

			// Run the query to update the username.
			$query_result = $this->aiowpu_run_query_to_update_username( $update_user_id, $name );

			// Check the SQL update query result.
			if ( ! $query_result ) {
				$this->error_msg = __( 'Error: There is something wrong in the SQL command to run.', 'all-in-one-utilities' );
				return false;
			}

			// Send email notification to the user.
			if ( ! isset( $_post['user_notification'] ) ) {
				$this->success_msg = __( 'Username Updated! As email notification is not enabled, the user was not notified.', 'all-in-one-utilities' );
				return true;
			}

			// Call the email notification function.
			$is_email_sent = $this->aiowpu_send_update_username_notification_to_user( $update_user_id );

			if ( $is_email_sent ) {
				$this->success_msg = __( 'Username Updated! Mail Sent to the user about username change.', 'all-in-one-utilities' );
				return true;
			} else {
				$this->error_msg = __( 'Username Updated! But mail could not be sent to the user about username change.', 'all-in-one-utilities' );
				return false;
			}

		}

		/**
		 * Run the update command to update username.
		 *
		 * @param int    $id ID of the user to update uername.
		 * @param string $name New username to update.
		 * @return int|false The number of rows updated, or false on error.
		 */
		public function aiowpu_run_query_to_update_username( $id, $name ) {

			global $wpdb;

			$result = $wpdb->update( // phpcs:ignore
				$wpdb->prefix . 'users', // table.
				array(
					'user_login'   => $name,
					'display_name' => $name,
				), // data.
				array( 'id' => $id ), // where.
				array( '%s', '%s' ), // data format.
				array( '%d' ) // where format.
			);

			return $result;

		}

		/**
		 * Notify user about his username change.
		 *
		 * @param int $user_id User ID whose username updated.
		 * @return bool Whether the email was sent successfully.
		 */
		public function aiowpu_send_update_username_notification_to_user( $user_id ) {

			$user_info = get_userdata( $user_id );
			$to        = $user_info->user_email;

			$blogname    = get_bloginfo( 'name' );
			$siteurl     = get_option( 'siteurl' );
			$admin_email = get_option( 'admin_email' );

			$subject = 'Your username has been changed on ' . $blogname;

			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type:text/html;charset=UTF-8' . "\r\n";
			$headers .= "From: $blogname <$admin_email>";

			$message = "
			<html>
			<head>
				<title>Username Updated</title>
			</head>
			<body>
				<p>Hi, $user_info->first_name</p>
				<p>Your username has been updated for the site $siteurl. Your new username is <b>$user_info->user_login</b></p>
				<p>Thank You,<br/>$blogname Team</p>
			</body>
			</html>
			";

			return wp_mail( $to, $subject, $message, $headers );

		}

	}
}
