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
 * @subpackage All_In_One_Wp_Utilities/modules/disable-unnecessary-features
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
	 * @subpackage All_In_One_Wp_Utilities/modules/disable-unnecessary-features
	 * @author     2ByteCode <support@2bytecode.com>
	 */
	final class Aiowpu_Disable_Unnecessary_Features_Admin extends Aiowpu_Module_Admin {

		/**
		 * Holds the instance.
		 *
		 * @var self
		 */
		protected static $inst = null;

		/**
		 * Holds the saved options.
		 *
		 * @var self
		 */
		protected $options;

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

			$this->options = get_option( 'aiowpu_disable_unnecessary_features_options', Aiowpu_Default_Settings_Options::aiowpu_disable_unnecessary_features_options() );

			// Admin area hooks.
			// Settings tabs options.
			require_once dirname( __FILE__ ) . '/settings/class-aiowpu-disable-unnecessary-features-settings.php';
			new Aiowpu_Disable_Unnecessary_Features_Settings( 'aiowpu_disable_unnecessary_features_opt_group', 'aiowpu_disable_unnecessary_features_options', 'aiowpu_settings' );

			// Run the functions to disable features on init hook.
			add_action( 'init', array( $this, 'aiowpu_disable_unnecessary_features_init' ) );

			// Display how much user saves on disabling the unnecessary features.
			add_action( 'aiowpu_disable_unnecessary_features_settings_rigt_section', array( $this, 'aiowpu_display_estimated_savings' ), 15 );

			// Display rating request in sidebar of settings page.
			add_action( 'aiowpu_disable_unnecessary_features_settings_rigt_section', array( $this, 'aiowpu_display_rating_request_link' ), 20 );

			// Display rating request in admin footer of settings page.
			add_filter( 'admin_footer_text', array( $this, 'aiowpu_display_rating_request_link_in_footer' ) );

		}

		/**
		 * Register the stylesheets and JavaScript for the admin area.
		 *
		 * @param string $page The current admin screen..
		 */
		public function admin_enqueue_scripts( $page ) {

			// Below script and style will only needed on the settings page.
			if ( 'aiowp-utilities_page_aiowpu_settings' !== $page ) {
				return;
			}

			wp_enqueue_style( 'aiowpu-disable-unnecessary-features', plugin_dir_url( __FILE__ ) . 'css/style.css', '', aiowpu_get_setting( 'version' ), 'all' );
			wp_enqueue_script( 'aiowpu-disable-unnecessary-features', plugin_dir_url( __FILE__ ) . 'js/script.js', array( 'jquery' ), aiowpu_get_setting( 'version' ), true );

		}

		/**
		 * Check either the feature is enabled or disabled.
		 *
		 * @param string $suffix Option name.
		 * @return boolean true if feature is enabled.
		 */
		public function aiowpu_check_option_selected( $suffix ) {
			return ( isset( $this->options[ 'aiowpu_duf_' . $suffix ] ) && 1 === $this->options[ 'aiowpu_duf_' . $suffix ] );
		}

		/**
		 * Display the estimated savings after disabling the unnecessary features.
		 *
		 * @return void
		 */
		public function aiowpu_display_estimated_savings() {

			$reqs = 0;
			$size = 0;
			$tags = 0;

			$saving_features = array(
				'emojis'    => array(
					'reqs' => 2,
					'size' => 16,
					'tags' => 2,
				),
				'embed'     => array(
					'reqs' => 1,
					'size' => 6,
					'tags' => 1,
				),
				'dashicons' => array(
					'reqs' => 1,
					'size' => 46,
					'tags' => 1,
				),
				'heartbeat' => array(
					'reqs' => 1,
					'size' => 6,
					'tags' => 1,
				),
				'generator' => array(
					'reqs' => 0,
					'size' => 0,
					'tags' => 1,
				),
				'xmlrpc'    => array(
					'reqs' => 0,
					'size' => 0,
					'tags' => 1,
				),
				'manifest'  => array(
					'reqs' => 0,
					'size' => 0,
					'tags' => 1,
				),
				'rsdlink'   => array(
					'reqs' => 0,
					'size' => 0,
					'tags' => 1,
				),
				'shortlink' => array(
					'reqs' => 0,
					'size' => 0,
					'tags' => 1,
				),
				'rssfeeds'  => array(
					'reqs' => 0,
					'size' => 0,
					'tags' => 2,
				),
				'restapi'   => array(
					'reqs' => 0,
					'size' => 0,
					'tags' => 1,
				),
				'blocks'    => array(
					'reqs' => 1,
					'size' => 29,
					'tags' => 1,
				),
			);

			foreach ( $saving_features as $key => $values ) {
				if ( $this->aiowpu_check_option_selected( $key ) ) {
					$reqs += $values['reqs'];
					$size += $values['size'];
					$tags += $values['tags'];
				}
			}

			echo sprintf(
				'<div class="aiowpu-duf-estimated-savings">
					<div class="desc">
						<h2>%s</h2>
						<p class="description">%s</p>
						<table class="form-table">
							<tbody>
								<tr>
									<th scope="row">%s</th>
									<td>%s</td>
								</tr>
								<tr>
									<th scope="row">%s</th>
									<td>%s</td>
								</tr>
								<tr>
									<th scope="row">%s</th>
									<td>%s</td>
								</tr>
							</tbody>

						</table>
					</div>
				</div>
				<hr>',
				esc_html__( 'Estimated Savings', 'all-in-one-wp-utilities' ),
				esc_html__( "By activating the chosen features, you've saved this amount thus far.", 'all-in-one-wp-utilities' ),
				esc_html__( 'File Requests', 'all-in-one-wp-utilities' ),
				esc_html( $reqs ),
				esc_html__( 'File Size', 'all-in-one-wp-utilities' ),
				esc_html( $size >= 1024 ? ( number_format( $size / 1024, 1 ) ) . 'Mb' : $size . 'kb' ),
				esc_html__( 'HTML Tags', 'all-in-one-wp-utilities' ),
				esc_html( $tags ),
			);

		}

		/**
		 * Display the 5-star rating request in sidebar of settings page.
		 *
		 * @return void
		 */
		public function aiowpu_display_rating_request_link() {

			echo sprintf(
				'<div class="aiowpu-duf-rating-request">
					<div class="desc">
						<h2>%s</h2>
						<p class="description">%s <a href="%s" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> %s</p>
					</div>
				</div>
				<hr>',
				esc_html__( 'Love Our Plugin?', 'all-in-one-wp-utilities' ),
				esc_html__( 'Show your support with a', 'all-in-one-wp-utilities' ),
				esc_url( 'https://wordpress.org/support/plugin/all-in-one-wp-utilities/reviews?rate=5#new-post' ),
				esc_html__( 'rating. A huge thanks in advance! Your feedback means the world to us.', 'all-in-one-wp-utilities' ),
			);

		}

		/**
		 * Display the 5-star rating request in footer of settings page.
		 *
		 * @param string $text Admin footer text.
		 * @return string
		 */
		public function aiowpu_display_rating_request_link_in_footer( $text ) {

			global $current_screen;

			if ( ! empty( $current_screen->id ) && strpos( $current_screen->id, 'aiowp-utilities_page_aiowpu_settings' ) !== false ) {
				return sprintf(
					'%s <a href="%s" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> %s',
					esc_html__( 'Show your support with a', 'all-in-one-wp-utilities' ),
					esc_url( 'https://wordpress.org/support/plugin/all-in-one-wp-utilities/reviews?rate=5#new-post' ),
					esc_html__( 'rating. A huge thanks in advance! Your feedback means the world to us.', 'all-in-one-wp-utilities' ),
				);

			}

			return $text;

		}

		/**
		 * Register other hooks to disable the selected features on init hook.
		 *
		 * @return void
		 */
		public function aiowpu_disable_unnecessary_features_init() {

			// Unset Self Pingbacks.
			add_action(
				'pre_ping',
				function ( &$links ) {
					$home = get_option( 'home' );
					foreach ( $links as $l => $link ) {
						if ( strpos( $link, $home ) === 0 ) {
							unset( $links [ $l ] );
						}
					}
				}
			);

			// Block User-Enumeration.
			if ( $this->aiowpu_check_option_selected( 'blockuserenumeration' ) ) {

				if ( ! is_admin() ) {
					// default URL format.
					if ( preg_match( '/author=([0-9]*)/i', $_SERVER['QUERY_STRING'] ) ) { // phpcs:ignore
						die();
					}

					add_filter(
						'redirect_canonical',
						function ( $redirect, $request ) {
							// permalink URL format.
							if ( preg_match( '/\?author=([0-9]*)(\/*)/i', $request ) ) {
								die();
							} else {
								return $redirect;
							}
						},
						10,
						2
					);
				}
			}

			// Disable Author Archives.
			if ( $this->aiowpu_check_option_selected( 'disableauthorarchives' ) ) {

				remove_action( 'template_redirect', 'redirect_canonical' );

				add_action(
					'template_redirect',
					function () {
						if ( is_author() ) {
							global $wp_query;
							$wp_query->set_404();
							status_header( 404 );
						} else {
							redirect_canonical();
						}
					}
				);
			}

			// Remove Capital P Dangit.
			if ( $this->aiowpu_check_option_selected( 'removecapitalpdangit' ) ) {

				remove_filter( 'the_title', 'capital_P_dangit', 11 );
				remove_filter( 'the_content', 'capital_P_dangit', 11 );
				remove_filter( 'comment_text', 'capital_P_dangit', 31 );

			}

			// Remove screen options and contextual help.
			if ( $this->aiowpu_check_option_selected( 'removescreenoptions' ) ) {

				// Remove help tab.
				add_action( 'admin_head', array( $this, 'aiowpu_remove_help_tabs_feature' ) );

				// Remove screen options.
				add_filter( 'screen_options_show_screen', '__return_false' );
			}

			// Remove Howdy.
			if ( $this->aiowpu_check_option_selected( 'removehowdy' ) ) {

				// replace howdy "greeting".
				add_action(
					'admin_bar_menu',
					function ( $wp_admin_bar ) {

						$wp_admin_bar->add_node(
							array(
								'id'    => 'my-account',
								'title' => __( 'My Profile', 'all-in-one-wp-utilities' ),
							)
						);

					}
				);
			}

			// Remove items from adminbar.
			if ( $this->aiowpu_check_option_selected( 'removeitemsadminbar' ) ) {

				// removes redundant items from adminbar.
				add_action(
					'admin_bar_menu',
					function ( $wp_admin_bar ) {

						// BACKEND.
						// remove WP logo and subsequent drop-down menu.
						$wp_admin_bar->remove_node( 'wp-logo' );

						// remove View Site text.
						$wp_admin_bar->remove_node( 'view-site' );

						// remove "+ New" drop-down menu.
						$wp_admin_bar->remove_node( 'new-content' );

						// remove Comments.
						$wp_admin_bar->remove_node( 'comments' );

						// remove plugin updates count.
						$wp_admin_bar->remove_node( 'updates' );

						// FRONTEND.
						// remove Dashboard link.
						$wp_admin_bar->remove_node( 'dashboard' );

						// remove Themes, Widgets, Menus, Header links.
						$wp_admin_bar->remove_node( 'appearance' );
					},
					9999
				);
			}

			// Clean Dashboard.
			if ( $this->aiowpu_check_option_selected( 'cleandashboard' ) ) {

				add_action(
					'wp_dashboard_setup',
					function () {
						remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' ); // Quick Press widget.
						remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' ); // Recent Drafts.
						remove_meta_box( 'dashboard_primary', 'dashboard', 'side' ); // WordPress.com Blog.
						remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' ); // Other WordPress News.
						remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' ); // Incoming Links.
						remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' ); // Plugins.
						remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' ); // Right Now.
						remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' ); // Activity.
						remove_meta_box( 'dashboard_site_health', 'dashboard', 'normal' ); // Site Health.
						remove_meta_box( 'dashboard_php_nag', 'dashboard', 'normal' ); // PHP nag.
						remove_action( 'welcome_panel', 'wp_welcome_panel' ); // Remove Welcome Panel.
					}
				);
			}

			// Disable Emojis support.
			if ( $this->aiowpu_check_option_selected( 'emojis' ) ) {

				remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
				remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
				remove_action( 'wp_print_styles', 'print_emoji_styles' );
				remove_action( 'admin_print_styles', 'print_emoji_styles' );
				remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
				remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
				remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
				add_filter( 'emoji_svg_url', '__return_false' );
				add_filter(
					'tiny_mce_plugins',
					function ( $plugins ) {
						return array_diff(
							$plugins,
							array(
								'wpemoji',
							)
						);
					}
				);

			}

			// Disable Embed support.
			if ( $this->aiowpu_check_option_selected( 'embed' ) ) {

				global $wp;
				$wp->public_query_vars = array_diff(
					$wp->public_query_vars,
					array(
						'embed',
					)
				);

				remove_action( 'rest_api_init', 'wp_oembed_register_route' );
				remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
				remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
				remove_action( 'wp_head', 'wp_oembed_add_host_js' );
				remove_filter( 'pre_oembed_result', 'wp_filter_pre_oembed_result', 10 );
				add_filter( 'embed_oembed_discover', '__return_false' );

				add_filter(
					'rewrite_rules_array',
					function ( $rules ) {
						foreach ( $rules as $rule => $rewrite ) {
							if ( strpos( $rewrite, 'embed=true' ) !== false ) {
								unset( $rules [ $rule ] );
							}
						}
						return $rules;
					}
				);
			}

			// Disable XML-RPC support.
			if ( $this->aiowpu_check_option_selected( 'xmlrpc' ) ) {

				add_filter( 'xmlrpc_enabled', '__return_false' );
				add_filter( 'pings_open', '__return_false', 9999 );
				add_filter(
					'wp_headers',
					function ( $headers ) {
						unset( $headers['X-Pingback'] );
						return $headers;
					}
				);

			}

			// Disable Generator support.
			if ( $this->aiowpu_check_option_selected( 'generator' ) ) {

				remove_action( 'wp_head', 'wp_generator' );

				add_filter(
					'the_generator',
					function () {
						return '';
					}
				);
			}

			// Disable WLW Manifest support. - THIS ONE NEED TO BE REMOVED FROM THE PLUGIN CODE.
			if ( $this->aiowpu_check_option_selected( 'manifest' ) ) {
				remove_action( 'wp_head', 'wlwmanifest_link' );
			}

			// Disable RSD Link support.
			if ( $this->aiowpu_check_option_selected( 'rsdlink' ) ) {
				remove_action( 'wp_head', 'rsd_link' );
			}

			// Disable Shortlink support.
			if ( $this->aiowpu_check_option_selected( 'shortlink' ) ) {

				remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
				remove_action( 'template_redirect', 'wp_shortlink_header', 11, 0 );

			}

			// Disable RSS Feeds support.
			if ( $this->aiowpu_check_option_selected( 'rssfeeds' ) ) {

				remove_action( 'wp_head', 'feed_links', 2 );
				remove_action( 'wp_head', 'feed_links_extra', 3 );

				add_action(
					'template_redirect',
					function () {

						if ( ! is_feed() || is_404() ) {
							return;
						}

						// phpcs:ignore
						if ( isset( $_GET ['feed'] ) ) {
							wp_safe_redirect( esc_url_raw( remove_query_arg( 'feed' ) ), 301 );
							exit();
						}

						if ( get_query_var( 'feed' ) !== 'old' ) {
							set_query_var( 'feed', '' );
						}

						redirect_canonical();
						wp_die(
							sprintf(
								"%s <a href='%s'>%s</a>!",
								esc_html__( 'RSS Feeds disabled, please visit the', 'all-in-one-wp-utilities' ),
								esc_url( home_url( '/' ) ),
								esc_html__( 'homepage', 'all-in-one-wp-utilities' )
							)
						);
					},
					1
				);
			}

			// Disable REST API support.
			if ( $this->aiowpu_check_option_selected( 'restapi' ) && ! is_admin() ) {

				remove_action( 'xmlrpc_rsd_apis', 'rest_output_rsd' );
				remove_action( 'wp_head', 'rest_output_link_wp_head', 10, 0 );
				remove_action( 'template_redirect', 'rest_output_link_header', 11, 0 );

				add_filter(
					'rest_authentication_errors',
					function ( $result ) {
						if ( empty( $result ) && ! is_admin() ) {
							return new WP_Error(
								'rest_authentication_error',
								__( 'Forbidden', 'all-in-one-wp-utilities' ),
								array(
									'status' => 403,
								)
							);
						}
						return $result;
					},
					20
				);
			}

			// Disable Block Library support.
			if ( $this->aiowpu_check_option_selected( 'blocks' ) ) {

				add_action(
					'wp_print_styles',
					function () {
						wp_dequeue_style( 'wp-block-library' );
					},
					100
				);

			}

			// Disable Application Passwords support.
			if ( $this->aiowpu_check_option_selected( 'applicationpasswords' ) ) {

				// completely disable the new Application Passwords functionality.
				add_filter( 'wp_is_application_passwords_available', '__return_false' );

			}

			// Disable Core Privacy Tools support.
			if ( $this->aiowpu_check_option_selected( 'coreprivacytools' ) ) {

				// Removes required user's capabilities for core privacy tools by adding the `do_not_allow` capability.
				add_filter( 'map_meta_cap', array( $this, 'aiowpu_disable_core_privacy_tools' ), 10, 2 );

				/**
				 * Short circuits the option for the privacy policy page to always return 0.
				 * The option is used by get_privacy_policy_url() among others.
				 */
				add_filter( 'pre_option_wp_page_for_privacy_policy', '__return_zero' );

				// Removes the default scheduled event used to delete old export files.
				remove_action( 'init', 'wp_schedule_delete_old_privacy_export_files' );

				// Removes the hook attached to the default scheduled event for removing old export files.
				remove_action( 'wp_privacy_delete_old_export_files', 'wp_privacy_delete_old_export_files' );

			}

			// Disable Site Health support.
			if ( $this->aiowpu_check_option_selected( 'sitehealth' ) ) {

				// disable the admin menu.
				add_action( 'admin_menu', array( $this, 'aiowpu_remove_site_health_menu' ) );

				// block site health page screen.
				add_action( 'current_screen', array( $this, 'aiowpu_remove_site_health_access' ) );

			}

			// Disable adjacent_posts support. - THIS FEATURES NEEDS TO BE DELETED.
			if ( $this->aiowpu_check_option_selected( 'adjacentposts' ) ) {

				// remove the next and previous post links.
				remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 );
				remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );

			}

			// Disable Version support.
			if ( $this->aiowpu_check_option_selected( 'version' ) ) {

				// Remove WordPress version var (?ver=) after styles and scripts.
				add_filter(
					'style_loader_src',
					function ( $src ) {
						if ( strpos( $src, 'ver=' ) ) {
							$src = remove_query_arg( 'ver', $src );
						}
						return $src;
					},
					9999
				);

				add_filter(
					'script_loader_src',
					function ( $src ) {
						if ( strpos( $src, 'ver=' ) ) {
							$src = remove_query_arg( 'ver', $src );
						}
						return $src;
					},
					9999
				);

			}

			// Disable s.w.org dns-prefetch support.
			if ( $this->aiowpu_check_option_selected( 'dnsprefetch' ) ) {

				remove_action( 'wp_head', 'wp_resource_hints', 2 );

			}

			// Disable PDF Thumbnails support.
			if ( $this->aiowpu_check_option_selected( 'pdfthumbnails' ) ) {

				add_filter(
					'fallback_intermediate_image_sizes',
					function() {
						return array();
					}
				);

			}

			// Empty Trash support - Empty trash sooner.
			if ( $this->aiowpu_check_option_selected( 'emptytrash' ) ) {

				if ( ! defined( 'EMPTY_TRASH_DAYS' ) ) {
					define( 'EMPTY_TRASH_DAYS', 7 );
				}
			}

			// Disable Plugin and Theme Editor support.
			if ( $this->aiowpu_check_option_selected( 'pluginandthemeeditor' ) ) {

				if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
					define( 'DISALLOW_FILE_EDIT', true );
				}
			}

			// Disable oEmbed support.
			if ( $this->aiowpu_check_option_selected( 'oembed' ) ) {

				// Since WordPress 4.4, oEmbed is installed and available by default.
				wp_deregister_script( 'wp-embed' );

			}

			// Disable Remote Block Patterns support.
			if ( $this->aiowpu_check_option_selected( 'remoteblockpatterns' ) ) {

				add_filter( 'should_load_remote_block_patterns', '__return_false' );
			}

		}

		/**
		 * Remove help tabs from the admin pages.
		 *
		 * @return void
		 */
		public function aiowpu_remove_help_tabs_feature() {

			$screen = get_current_screen();
			$screen->remove_help_tabs();

		}

		/**
		 * Removes required user's capabilities for core privacy tools by adding the
		 * `do_not_allow` capability.
		 *
		 *  - Disables the feature pointer.
		 *  - Removes the Privacy and Export/Erase Personal Data admin menu items.
		 *  - Disables the privacy policy guide and update bubbles.
		 *
		 * @param string[] $caps    Array of the user's capabilities.
		 * @param string   $cap     Capability name.
		 * @return string[] Array of the user's capabilities.
		 */
		public function aiowpu_disable_core_privacy_tools( $caps, $cap ) {

			switch ( $cap ) {

				case 'export_others_personal_data':
				case 'erase_others_personal_data':
				case 'manage_privacy_options':
					$caps[] = 'do_not_allow';
					break;

			}

			return $caps;
		}

		/**
		 * Remove the tools and site-health pages from the admin.
		 *
		 * @return void
		 */
		public function aiowpu_remove_site_health_menu() {

			remove_submenu_page( 'tools.php', 'site-health.php' );

		}

		/**
		 * Remove access to the site-health pages from the admin.
		 *
		 * @return void
		 */
		public function aiowpu_remove_site_health_access() {

			$screen = get_current_screen();

			// if screen id is site health.
			if ( 'site-health' === $screen->id ) {
				wp_safe_redirect( admin_url() );
				exit;
			}

		}

	}

}
