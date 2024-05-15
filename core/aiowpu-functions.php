<?php
/**
 * The basic functions for the plugin and modules
 *
 * @package    All_In_One_Utilities
 * @subpackage All_In_One_Utilities/core
 * @version    1.0.0
 * @since      1.0.0
 */

/**
 * This function return unique slug name to refer to this menu by.
 *
 * @param string $slug The module slug.
 */
function aiowpu_get_page_slug( $slug ) {

	return sprintf( 'aiowpu_%s', $slug );

}

/**
 * This function return admin page url.
 *
 * @param string $slug The module slug.
 * @param string $type The type page.
 */
function aiowpu_get_page_url( $slug, $type = 'general' ) {

	switch ( $type ) {

		case 'general':
			return admin_url( sprintf( 'options-general.php?page=%s', aiowpu_get_page_slug( $slug ) ) );

		case 'writing':
			return admin_url( sprintf( 'options-writing.php?page=%s', aiowpu_get_page_slug( $slug ) ) );

		case 'reading':
			return admin_url( sprintf( 'options-reading.php?page=%s', aiowpu_get_page_slug( $slug ) ) );

		case 'discussion':
			return admin_url( sprintf( 'options-reading.php?page=%s', aiowpu_get_page_slug( $slug ) ) );

		case 'media':
			return admin_url( sprintf( 'options-media.php?page=%s', aiowpu_get_page_slug( $slug ) ) );

		case 'permalink':
			return admin_url( sprintf( 'options-permalink.php?page=%s', aiowpu_get_page_slug( $slug ) ) );

		case 'themes':
			return admin_url( sprintf( 'themes.php?page=%s', aiowpu_get_page_slug( $slug ) ) );

		case 'admin':
			return admin_url( sprintf( 'admin.php?page=%s', aiowpu_get_page_slug( $slug ) ) );

		default:
			return admin_url( sprintf( '%s?page=%s', $type, aiowpu_get_page_slug( $slug ) ) );
	}
}

/**
 * This function checks if the module is enabled
 *
 * @param string $slug The module slug.
 */
function aiowpu_module_enabled( $slug ) {

	$module = aiowpu_get_module_meta( $slug );

	// Default status.
	$status = $module['enabled'];

	// Check database.
	if ( 'default' === $module['type'] ) {
		$enabled = get_option( 'aiowpu_enabled_' . $slug, $module['enabled'] );

		$status = '0' === $enabled ? false : $enabled;
	}

	return apply_filters( 'aiowpu_module_enabled', $status, $slug );
}

/**
 * This function return meta info of module
 *
 * @param string $slug  The slug.
 * @param string $field The field.
 */
function aiowpu_get_module_meta( $slug, $field = false ) {

	$modules = (array) aiowpu_get_data( 'modules' );

	if ( $field ) {
		if ( isset( $modules[ $slug ][ $field ] ) ) {
			return $modules[ $slug ][ $field ];
		}
	}

	if ( isset( $modules[ $slug ] ) ) {
		return $modules[ $slug ];
	}

}

/**
 * This function return modules.
 */
function aiowpu_get_modules() {

	$modules = aiowpu_get_data( 'modules' );

	// Sort modules.
	if ( is_array( $modules ) && $modules ) {

		$modules_keys     = array_keys( $modules );
		$modules_priority = array();
		$modules_name     = array();

		foreach ( $modules as $key => $row ) {
			$modules_priority[ $key ] = $row['priority'];
			$modules_name[ $key ]     = $row['name'];
		}
		array_multisort( $modules_priority, SORT_ASC, $modules_name, SORT_ASC, $modules, $modules_keys );

		$modules = array_combine( $modules_keys, $modules );
	}

	return $modules;
}

/**
 * This function checks if the module is enabled
 *
 * @param array $info The module info.
 */
function aiowpu_register_module_info( $info ) {

	$modules = (array) aiowpu_get_data( 'modules' );

	// Slug of module.
	$slug = $info['slug'];

	// Check exists slug.
	if ( $slug ) {

		// Enabled load extensions.
		$module_enabled = get_option( 'aiowpu_enabled_' . $slug, $info['enabled'] );

		if ( $module_enabled && 'default' === $info['type'] && $info['load_extensions'] ) {
			foreach ( $info['load_extensions'] as $extension ) {
				$modules[ $extension ]['enabled'] = true;
			}
		}

		// Add new info.
		if ( isset( $modules[ $slug ] ) ) {
			$modules[ $slug ] = array_merge( (array) $info, (array) $modules[ $slug ] );
		} else {
			$modules[ $slug ] = (array) $info;
		}

		// Update info.
		aiowpu_set_data( 'modules', $modules );
	}
}

/**
 * This function include files to the plugin.
 *
 * @param  string $dir Directory where you need to search for files.
 */
function aiowpu_load_files( $dir ) {

	$path = AIOWPU_PATH . $dir;

	// Loop through files.
	foreach ( glob( $path . '/*' ) as $file ) {

		$basename = basename( $file );

		if ( is_file( $file ) ) {
			require_once wp_normalize_path( $file );
		} elseif ( file_exists( sprintf( '%1$s/%2$s/%2$s.php', $path, $basename ) ) ) {
			require_once wp_normalize_path( sprintf( '%1$s/%2$s/%2$s.php', $path, $basename ) );
		} elseif ( file_exists( sprintf( '%1$s/%2$s/class-aiowpu-%2$s.php', $path, $basename ) ) ) {
			require_once wp_normalize_path( sprintf( '%1$s/%2$s/class-aiowpu-%2$s.php', $path, $basename ) );
		}
	}

}
