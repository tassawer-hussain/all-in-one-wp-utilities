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
 * @package    All_In_One_Wp_Utilities
 * @subpackage All_In_One_Wp_Utilities/core
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

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
 * @package    All_In_One_Wp_Utilities
 * @subpackage All_In_One_Wp_Utilities/core
 * @author     2ByteCode <support@2bytecode.com>
 */
class Aiowpu_Default_Settings_Options {

	/**
	 * Static function to return default values of login page tab under settings page.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public static function aiowpu_disable_unnecessary_features_options() {

		return array(
			'aiowpu_duf_disableauthorarchives' => 'no',
			'aiowpu_duf_applicationpasswords'  => 'no',
			'aiowpu_duf_adjacentposts'         => 'no',
			'aiowpu_duf_blocks'                => 'no',
			'aiowpu_duf_coreprivacytools'      => 'no',
			'aiowpu_duf_removecapitalpdangit'  => 'no',
			'aiowpu_duf_cleandashboard'        => 'no',
			'aiowpu_duf_dashicons'             => 'no',
			'aiowpu_duf_emojis'                => 'no',
			'aiowpu_duf_embed'                 => 'no',
			'aiowpu_duf_emptytrash'            => 'no',
			'aiowpu_duf_generator'             => 'no',
			'aiowpu_duf_removehowdy'           => 'no',
			'aiowpu_duf_heartbeat'             => 'no',
			'aiowpu_duf_removeitemsadminbar'   => 'no',
			'aiowpu_duf_oembed'                => 'no',
			'aiowpu_duf_pdfthumbnails'         => 'no',
			'aiowpu_duf_pluginandthemeeditor'  => 'no',
			'aiowpu_duf_rsdlink'               => 'no',
			'aiowpu_duf_rssfeeds'              => 'no',
			'aiowpu_duf_restapi'               => 'no',
			'aiowpu_duf_remoteblockpatterns'   => 'no',
			'aiowpu_duf_shortlink'             => 'no',
			'aiowpu_duf_removescreenoptions'   => 'no',
			'aiowpu_duf_sitehealth'            => 'no',
			'aiowpu_duf_blockuserenumeration'  => 'no',
			'aiowpu_duf_version'               => 'no',
			'aiowpu_duf_manifest'              => 'no',
			'aiowpu_duf_xmlrpc'                => 'no',
		);

	}

}
