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
class Aiowpu_Module_Admin {

	/**
	 * The module slug.
	 *
	 * @var string $slug The module slug.
	 */
	public $slug = null;

	/**
	 * __construct
	 *
	 * This function will initialize the initialize
	 *
	 * @param string $slug The module slug.
	 */
	public function __construct( $slug = null ) {

		// Init slug of module.
		$this->slug = $slug;

		// Initialize.
		$this->initialize();

		// Actions.
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	/**
	 * Initialize
	 *
	 * This function will initialize the module
	 */
	public function initialize() {

		/* do nothing */
	}

	/**
	 * Load the required dependencies for this module.
	 *
	 * @param string $page Current page.
	 */
	public function admin_enqueue_scripts( $page ) {

		/* do nothing */
	}

}
