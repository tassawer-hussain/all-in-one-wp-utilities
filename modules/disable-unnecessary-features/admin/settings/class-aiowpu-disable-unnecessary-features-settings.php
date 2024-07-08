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
 * @subpackage All_In_One_Utilities/modules/disable-unnecessary-features
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the settings section for disable unnecessary features.
 *
 * @since      1.0.0
 * @package    All_In_One_Utilities/modules
 * @subpackage All_In_One_Utilities/modules/disable-unnecessary-features
 * @author     2ByteCode <support@2bytecode.com>
 */
class Aiowpu_Disable_Unnecessary_Features_Settings extends Bytecode_Settings_Fields_Callbacks {

	/**
	 * Settings options group name.
	 *
	 * @var string $option_group Setting options group name.
	 */
	public $option_group;

	/**
	 * WP Options table option name.
	 *
	 * @var string $option_name Option name.
	 */
	public $option_name;

	/**
	 * Admin page slug.
	 *
	 * @var string $admin_page_slug Admin page slug where this option will list.
	 */
	public $admin_page_slug;

	/**
	 * Options name.
	 *
	 * @var string $options WP_option table meta_key.
	 */
	public $options;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param    string $option_group   The name of this plugin.
	 * @param    string $option_name    The version of this plugin.
	 * @param    string $slug           Admin page slug.
	 * @since    1.0.0
	 */
	public function __construct( $option_group, $option_name, $slug ) {

		$this->option_group    = $option_group;
		$this->option_name     = $option_name;
		$this->admin_page_slug = $slug;
		$this->options         = get_option( $this->option_name, $this->b2c_add_settings_defualt_options() );

		parent::__construct();

		// Filter -> Add Settings Tab.
		add_filter( 'bytecode_settings_api_tabs', array( $this, 'b2c_add_settings_tab' ), 10 );

		// Action -> Add Settings Section.
		add_action( 'bytecode_settings_api_sections', array( $this, 'b2c_add_settings_section' ), 11 );

		// Register Settings Fields.
		add_action( 'admin_init', array( $this, 'b2c_register_setting_fields' ) );
	}

	/**
	 * Abstract method for adding setting tab.
	 *
	 * @since 1.0.0
	 * @param  array $tabs Array of settings tabs.
	 * @return array
	 */
	public function b2c_add_settings_tab( $tabs ) : array {
		$tabs['aiowpu-disable-unnecessary-features'] = esc_html__( 'Disable Unnecessary Features', 'all-in-one-utilities' );
		return $tabs;
	}

	/**
	 * Abstract method for adding setting section.
	 *
	 * @return void
	 */
	public function b2c_add_settings_section() {

		include apply_filters( 'aiowpu_disable_unnecessary_features_html', plugin_dir_path( dirname( __FILE__ ) ) . 'partials/aiowpu-disable-unnecessary-features.php' );

	}

	/**
	 * Abstract method for adding setting fields.
	 *
	 * @return void
	 */
	public function b2c_register_setting_fields() {

		// Register Settings.
		register_setting(
			$this->option_group,
			$this->option_name,
			array( $this, 'b2c_validate_setting_fields' )
		);

		// "Disable Unnecessary Features" Settings section.
		add_settings_section(
			'aiowpu_disable_unnecessary_things',
			esc_html__( 'Minimizing Excess Functionality for Efficiency', 'all-in-one-utilities' ),
			array( $this, 'setting_section_description_cb' ),
			$this->admin_page_slug . '-' . $this->option_group
		);

		// "Disable Unnecessary Features" section fields.
		$aiowpu_disable_unnecessary_things_fields = array(

			// Author Archives - aiowpu_duf_disableauthorarchives.
			array(
				'id'    => 'aiowpu_duf_disableauthorarchives',
				'title' => esc_html__( 'Author Archives', 'all-in-one-utilities' ),
				'type'  => 'checkbox',
				'args'  => array(
					'id'    => 'aiowpu_duf_disableauthorarchives',
					'label' => __( 'Disable author archives pages', 'all-in-one-utilities' ),
					'desc'  => esc_html__( 'Author archives page where all posts authored by that particular author are displayed. URL like example.com/author/authorname', 'all-in-one-utilities' ),
					'class' => 'b2c_checkbox',
				),
			),

			// Application Passwords - aiowpu_duf_applicationpasswords.
			array(
				'id'    => 'aiowpu_duf_applicationpasswords',
				'title' => esc_html__( 'Application Passwords', 'all-in-one-utilities' ),
				'type'  => 'checkbox',
				'args'  => array(
					'id'    => 'aiowpu_duf_applicationpasswords',
					'label' => __( 'Completely disable the new Application Passwords', 'all-in-one-utilities' ),
					'desc'  => esc_html__( 'This functionality added in WP version 5.6. By default, Application Passwords is available to all sites using SSL or to local environments.', 'all-in-one-utilities' ),
					'class' => 'b2c_checkbox',
				),
			),

			// Adjacent Posts - aiowpu_duf_adjacentposts.
			array(
				'id'    => 'aiowpu_duf_adjacentposts',
				'title' => esc_html__( 'Adjacent Posts', 'all-in-one-utilities' ),
				'type'  => 'checkbox',
				'args'  => array(
					'id'    => 'aiowpu_duf_adjacentposts',
					'label' => __( 'Remove the next and previous post links from the header', 'all-in-one-utilities' ),
					'desc'  => esc_html__( 'This functionality add the next and previous post links to the <head> section of your WordPress site\'s HTML markup.', 'all-in-one-utilities' ),
					'class' => 'b2c_checkbox',
				),
			),

			// Block Library - aiowpu_duf_blocks.
			array(
				'id'    => 'aiowpu_duf_blocks',
				'title' => esc_html__( 'Block Library', 'all-in-one-utilities' ),
				'type'  => 'checkbox',
				'args'  => array(
					'id'    => 'aiowpu_duf_blocks',
					'label' => __( 'Disable the Gutenberg blocks library if you are using Classic Editor', 'all-in-one-utilities' ),
					'desc'  => esc_html__( 'Saves 1 file request and ~29kb', 'all-in-one-utilities' ),
					'class' => 'b2c_checkbox',
				),
			),

			// Core Privacy Tools - aiowpu_duf_coreprivacytools.
			array(
				'id'    => 'aiowpu_duf_coreprivacytools',
				'title' => esc_html__( 'Core Privacy Tools', 'all-in-one-utilities' ),
				'type'  => 'checkbox',
				'args'  => array(
					'id'    => 'aiowpu_duf_coreprivacytools',
					'label' => __( 'Disable the Core Privacy Tools', 'all-in-one-utilities' ),
					'desc'  => esc_html__( 'Tools like \'Export Personal Data\', \'Erase Personal Data\' etc.', 'all-in-one-utilities' ),
					'class' => 'b2c_checkbox',
				),
			),

			// capital_P_dangit - aiowpu_duf_removecapitalpdangit.
			array(
				'id'    => 'aiowpu_duf_removecapitalpdangit',
				'title' => esc_html__( 'capital_P_dangit', 'all-in-one-utilities' ),
				'type'  => 'checkbox',
				'args'  => array(
					'id'    => 'aiowpu_duf_removecapitalpdangit',
					'label' => __( 'Disable capital_P_dangit Filter', 'all-in-one-utilities' ),
					'desc'  => esc_html__( 'Changes the incorrect capitalization of Wordpress into WordPress. WordPress uses it to filter the content, the title and comment text.', 'all-in-one-utilities' ),
					'class' => 'b2c_checkbox',
				),
			),

			// Clean Dashboard - aiowpu_duf_cleandashboard.
			array(
				'id'    => 'aiowpu_duf_cleandashboard',
				'title' => esc_html__( 'Clean Dashboard', 'all-in-one-utilities' ),
				'type'  => 'checkbox',
				'args'  => array(
					'id'    => 'aiowpu_duf_cleandashboard',
					'label' => __( 'Clean up Dasboard from bloat', 'all-in-one-utilities' ),
					'desc'  => esc_html__( 'Improves preformance and saves valuable space. Remove widgets from dashboard like Quick Press, Recent Drafts, WordPress.com Blog, Other News etc', 'all-in-one-utilities' ),
					'class' => 'b2c_checkbox',
				),
			),

			// Dashicons - aiowpu_duf_dashicons.
			array(
				'id'    => 'aiowpu_duf_dashicons',
				'title' => esc_html__( 'Dashicons', 'all-in-one-utilities' ),
				'type'  => 'checkbox',
				'args'  => array(
					'id'    => 'aiowpu_duf_dashicons',
					'label' => __( 'Disable support for Dashicons <u>when not logged in', 'all-in-one-utilities' ),
					'desc'  => esc_html__( 'This will remove dashicons script and style files from front-end. Saves 1 file request and ~46kb', 'all-in-one-utilities' ),
					'class' => 'b2c_checkbox',
				),
			),

			// Emojis - aiowpu_duf_emojis.
			array(
				'id'    => 'aiowpu_duf_emojis',
				'title' => esc_html__( 'Emojis', 'all-in-one-utilities' ),
				'type'  => 'checkbox',
				'args'  => array(
					'id'    => 'aiowpu_duf_emojis',
					'label' => __( 'Disable support for emojis in posts', 'all-in-one-utilities' ),
					'desc'  => esc_html__( 'Saves at least 1 file request and ~16kb', 'all-in-one-utilities' ),
					'class' => 'b2c_checkbox',
				),
			),

			// Embed Objects - aiowpu_duf_embed.
			array(
				'id'    => 'aiowpu_duf_embed',
				'title' => esc_html__( 'Embed Objects', 'all-in-one-utilities' ),
				'type'  => 'checkbox',
				'args'  => array(
					'id'    => 'aiowpu_duf_embed',
					'label' => __( 'Disable support for embedding objects in posts', 'all-in-one-utilities' ),
					'desc'  => esc_html__( 'Saves at least 1 file request and ~6kb.', 'all-in-one-utilities' ),
					'class' => 'b2c_checkbox',
				),
			),

			// Empty Trash - aiowpu_duf_emptytrash.
			array(
				'id'    => 'aiowpu_duf_emptytrash',
				'title' => esc_html__( 'Empty Trash', 'all-in-one-utilities' ),
				'type'  => 'checkbox',
				'args'  => array(
					'id'    => 'aiowpu_duf_emptytrash',
					'label' => __( 'Shorten the time posts are kept in the trash', 'all-in-one-utilities' ),
					'desc'  => esc_html__( 'By default trash posts delete automatically after 30 days. This will set to 1 week.', 'all-in-one-utilities' ),
					'class' => 'b2c_checkbox',
				),
			),

			// Generator - aiowpu_duf_generator.
			array(
				'id'    => 'aiowpu_duf_generator',
				'title' => esc_html__( 'Generator', 'all-in-one-utilities' ),
				'type'  => 'checkbox',
				'args'  => array(
					'id'    => 'aiowpu_duf_generator',
					'label' => __( 'Disable the generator tag', 'all-in-one-utilities' ),
					'desc'  => esc_html__( 'This tan includes Wordpress version number in wp_head tag.', 'all-in-one-utilities' ),
					'class' => 'b2c_checkbox',
				),
			),

			// Howdy in adminbar - aiowpu_duf_removehowdy.
			array(
				'id'    => 'aiowpu_duf_removehowdy',
				'title' => esc_html__( 'Howdy in adminbar', 'all-in-one-utilities' ),
				'type'  => 'checkbox',
				'args'  => array(
					'id'    => 'aiowpu_duf_removehowdy',
					'label' => __( 'Remove Howdy from adminbar', 'all-in-one-utilities' ),
					'desc'  => esc_html__( 'This will remove "Howdy, username" from top right and replace it with "My Profile". ', 'all-in-one-utilities' ),
					'class' => 'b2c_checkbox',
				),
			),

			// Heartbeat - aiowpu_duf_heartbeat.
			array(
				'id'    => 'aiowpu_duf_heartbeat',
				'title' => esc_html__( 'Heartbeat', 'all-in-one-utilities' ),
				'type'  => 'checkbox',
				'args'  => array(
					'id'    => 'aiowpu_duf_heartbeat',
					'label' => __( 'Disable support for auto-save functionality when not editing a page/post', 'all-in-one-utilities' ),
					'desc'  => esc_html__( 'Saves 1 file request and ~6kb.', 'all-in-one-utilities' ),
					'class' => 'b2c_checkbox',
				),
			),

			// Navigation items in adminbar - aiowpu_duf_removeitemsadminbar.
			array(
				'id'    => 'aiowpu_duf_removeitemsadminbar',
				'title' => esc_html__( 'Navigation items in adminbar', 'all-in-one-utilities' ),
				'type'  => 'checkbox',
				'args'  => array(
					'id'    => 'aiowpu_duf_removeitemsadminbar',
					'label' => __( 'Remove reduntant items from adminbar', 'all-in-one-utilities' ),
					'desc'  => esc_html__( 'These items are removed: wp-logo, view-site, new-content, comments, updates, dashboard, appearance.', 'all-in-one-utilities' ),
					'class' => 'b2c_checkbox',
				),
			),

			// oEmbed - aiowpu_duf_oembed.
			array(
				'id'    => 'aiowpu_duf_oembed',
				'title' => esc_html__( 'oEmbed', 'all-in-one-utilities' ),
				'type'  => 'checkbox',
				'args'  => array(
					'id'    => 'aiowpu_duf_oembed',
					'label' => __( 'Remove oEmbed Scripts', 'all-in-one-utilities' ),
					'desc'  => esc_html__( 'Since WordPress 4.4, oEmbed is installed and available by default. WordPress assumes you\'ll want to easily embed media like tweets and YouTube videos so includes the scripts as standard. If you don\'t need oEmbed, you can remove it.', 'all-in-one-utilities' ),
					'class' => 'b2c_checkbox',
				),
			),

			// PDF Thumbnails - aiowpu_duf_pdfthumbnails.
			array(
				'id'    => 'aiowpu_duf_pdfthumbnails',
				'title' => esc_html__( 'PDF Thumbnails', 'all-in-one-utilities' ),
				'type'  => 'checkbox',
				'args'  => array(
					'id'    => 'aiowpu_duf_pdfthumbnails',
					'label' => __( 'This option disables PDF thumbnails.', 'all-in-one-utilities' ),
					'desc'  => esc_html__( "When you upload an image in pdf format, by default WordPress only generates the 'thumbnail', 'medium' and 'large' sizes — not all the other sizes your theme may use.", 'all-in-one-utilities' ),
					'class' => 'b2c_checkbox',
				),
			),

			// Plugin and Theme Editor - aiowpu_duf_pluginandthemeeditor.
			array(
				'id'    => 'aiowpu_duf_pluginandthemeeditor',
				'title' => esc_html__( 'Plugin and Theme Editor', 'all-in-one-utilities' ),
				'type'  => 'checkbox',
				'args'  => array(
					'id'    => 'aiowpu_duf_pluginandthemeeditor',
					'label' => __( 'Disables the plugins and theme editor tools.', 'all-in-one-utilities' ),
					'desc'  => esc_html__( 'Prevent the admin to accidentally modified the theme and plugin files using the Editor.', 'all-in-one-utilities' ),
					'class' => 'b2c_checkbox',
				),
			),

			// Really Simple Discovery - aiowpu_duf_rsdlink.
			array(
				'id'    => 'aiowpu_duf_rsdlink',
				'title' => esc_html__( 'Really Simple Discovery', 'all-in-one-utilities' ),
				'type'  => 'checkbox',
				'args'  => array(
					'id'    => 'aiowpu_duf_rsdlink',
					'label' => __( 'Disable the Really Simple Discovery (RSD) tag', 'all-in-one-utilities' ),
					'desc'  => esc_html__( 'This protocol never became popular. WordPress added this link in the head tag.', 'all-in-one-utilities' ),
					'class' => 'b2c_checkbox',
				),
			),

			// RSS Feeds - aiowpu_duf_rssfeeds.
			array(
				'id'    => 'aiowpu_duf_rssfeeds',
				'title' => esc_html__( 'Author Archives', 'all-in-one-utilities' ),
				'type'  => 'checkbox',
				'args'  => array(
					'id'    => 'aiowpu_duf_rssfeeds',
					'label' => __( 'Disable the RSS feed links and disable the feeds', 'all-in-one-utilities' ),
					'desc'  => esc_html__( 'Will redirect to the homepage instead.', 'all-in-one-utilities' ),
					'class' => 'b2c_checkbox',
				),
			),

			// REST API - aiowpu_duf_restapi.
			array(
				'id'    => 'aiowpu_duf_restapi',
				'title' => esc_html__( 'REST API', 'all-in-one-utilities' ),
				'type'  => 'checkbox',
				'args'  => array(
					'id'    => 'aiowpu_duf_restapi',
					'label' => __( 'Disable the REST API links and disable the endpoints', 'all-in-one-utilities' ),
					'desc'  => esc_html__( 'Disable the REST API when not on admin pages.', 'all-in-one-utilities' ),
					'class' => 'b2c_checkbox',
				),
			),

			// Remote Block Patterns - aiowpu_duf_remoteblockpatterns.
			array(
				'id'    => 'aiowpu_duf_remoteblockpatterns',
				'title' => esc_html__( 'Remote Block Patterns', 'all-in-one-utilities' ),
				'type'  => 'checkbox',
				'args'  => array(
					'id'    => 'aiowpu_duf_remoteblockpatterns',
					'label' => __( 'Disable Remote Block Patterns', 'all-in-one-utilities' ),
					'desc'  => esc_html__( 'Disable it if you want to improve pattern inserter loading performance or you have privacy concerns regarding loading remote asset.', 'all-in-one-utilities' ),
					'class' => 'b2c_checkbox',
				),
			),

			// Short Link - aiowpu_duf_shortlink.
			array(
				'id'    => 'aiowpu_duf_shortlink',
				'title' => esc_html__( 'Short Link', 'all-in-one-utilities' ),
				'type'  => 'checkbox',
				'args'  => array(
					'id'    => 'aiowpu_duf_shortlink',
					'label' => __( 'Disable the Short Link tag', 'all-in-one-utilities' ),
					'desc'  => esc_html__( 'Search engines ignore this tag completely. WordPress injects rel=shortlink into the head if a shortlink is defined for the current page.', 'all-in-one-utilities' ),
					'class' => 'b2c_checkbox',
				),
			),

			// Screen options and help - aiowpu_duf_removescreenoptions.
			array(
				'id'    => 'aiowpu_duf_removescreenoptions',
				'title' => esc_html__( 'Screen options and help', 'all-in-one-utilities' ),
				'type'  => 'checkbox',
				'args'  => array(
					'id'    => 'aiowpu_duf_removescreenoptions',
					'label' => __( 'Disable screen options and contextual help menu.', 'all-in-one-utilities' ),
					'desc'  => esc_html__( 'These options appear on the top right section of the WP listtables likes All Posts, All Pages etc.', 'all-in-one-utilities' ),
					'class' => 'b2c_checkbox',
				),
			),

			// Site Health - aiowpu_duf_sitehealth.
			array(
				'id'    => 'aiowpu_duf_sitehealth',
				'title' => esc_html__( 'Site Health', 'all-in-one-utilities' ),
				'type'  => 'checkbox',
				'args'  => array(
					'id'    => 'aiowpu_duf_sitehealth',
					'label' => __( 'Disable the Site Health page', 'all-in-one-utilities' ),
					'desc'  => esc_html__( 'The site health check shows information about your WordPress configuration and items that may need your attention.', 'all-in-one-utilities' ),
					'class' => 'b2c_checkbox',
				),
			),

			// User Enumeration - aiowpu_duf_blockuserenumeration.
			array(
				'id'    => 'aiowpu_duf_blockuserenumeration',
				'title' => esc_html__( 'User Enumeration', 'all-in-one-utilities' ),
				'type'  => 'checkbox',
				'args'  => array(
					'id'    => 'aiowpu_duf_blockuserenumeration',
					'label' => __( 'Block User-Enumeration', 'all-in-one-utilities' ),
					'desc'  => esc_html__( 'The Author Archives pages and WordPress REST API exposes endpoints that allow fetching user information.', 'all-in-one-utilities' ),
					'class' => 'b2c_checkbox',
				),
			),

			// Version - aiowpu_duf_version.
			array(
				'id'    => 'aiowpu_duf_version',
				'title' => esc_html__( 'Version', 'all-in-one-utilities' ),
				'type'  => 'checkbox',
				'args'  => array(
					'id'    => 'aiowpu_duf_version',
					'label' => __( 'Remove WordPress version var (?ver=) after styles and scripts.', 'all-in-one-utilities' ),
					'desc'  => esc_html__( 'This is prevent to the attackers which version of the plugin are you using.', 'all-in-one-utilities' ),
					'class' => 'b2c_checkbox',
				),
			),

			// WLW Manifest - aiowpu_duf_manifest.
			array(
				'id'    => 'aiowpu_duf_manifest',
				'title' => esc_html__( 'WLW Manifest', 'all-in-one-utilities' ),
				'type'  => 'checkbox',
				'args'  => array(
					'id'    => 'aiowpu_duf_manifest',
					'label' => __( 'Disable the Windows Live Writer manifest tag', 'all-in-one-utilities' ),
					'desc'  => esc_html__( 'WLW was discontinued in Jan 2017. WLW manifest is no longer in use and no longer included in core since WordPress version 6.3.0.', 'all-in-one-utilities' ),
					'class' => 'b2c_checkbox',
				),
			),

			// XML-RPC & Pingback - aiowpu_duf_xmlrpc.
			array(
				'id'    => 'aiowpu_duf_xmlrpc',
				'title' => esc_html__( 'XML-RPC & Pingback', 'all-in-one-utilities' ),
				'type'  => 'checkbox',
				'args'  => array(
					'id'    => 'aiowpu_duf_xmlrpc',
					'label' => __( 'Disable support for third-party application access.', 'all-in-one-utilities' ),
					'desc'  => esc_html__( 'Third-party applications such as mobile apps.', 'all-in-one-utilities' ),
					'class' => 'b2c_checkbox',
				),
			),

		);

		// Add filter to add more fields.
		$aiowpu_disable_unnecessary_things_fields = apply_filters( 'aiowpu_disable_unnecessary_things_fields', $aiowpu_disable_unnecessary_things_fields );

		// loop throuht the fields array.
		foreach ( $aiowpu_disable_unnecessary_things_fields as $field ) {
			add_settings_field(
				$field['id'],
				$field['title'],
				array( $this, 'b2c_callback_to_display_field_' . $field['type'] ),
				$this->admin_page_slug . '-' . $this->option_group,
				'aiowpu_disable_unnecessary_things',
				$field['args'],
			);
		}

	}

	/**
	 * Function to return default values for the settings
	 *
	 * @return array
	 */
	public function b2c_add_settings_defualt_options() : array {
		return Aiowpu_Default_Settings_Options::aiowpu_disable_unnecessary_features_options();
	}

	/**
	 * Validate the settings fields values.
	 *
	 * @param  array $input Raw values received on form submission.
	 * @return array $input validated options
	 */
	public function b2c_validate_setting_fields( $input ) {

		// filter function to validate user added fields.
		$input = apply_filters( 'validate_aiowpu_disable_unnecessary_things_fields', $input );

		$duf_fields = array_keys( Aiowpu_Default_Settings_Options::aiowpu_disable_unnecessary_features_options() );

		foreach ( $duf_fields as $key ) {

			if ( ! isset( $input[ $key ] ) ) {
				$input[ $key ] = null;
			}

			$input[ $key ] = ( '1' === $input[ $key ] ? 1 : 0 );

		}

		return $input;
	}

	/**
	 * Display the section description.
	 *
	 * @param  array $args Parameters received from the add_settings_section function.
	 * @return void
	 */
	public function setting_section_description_cb( $args ) {

		$desc = '';

		if ( 'aiowpu_disable_unnecessary_things' === $args['id'] ) {
			$desc = esc_html__( "This module helps optimize your site's performance by disabling unused options that may slow it down. It's important to note that this isn't a caching plugin, but it's designed to work seamlessly with any caching plugin you choose to use.", 'all-in-one-utilities' );
		}

		$this->b2c_display_setting_section_description( $desc );
	}

}
