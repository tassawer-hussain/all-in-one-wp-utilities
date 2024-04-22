<?php
/**
 * The api functions for the plugin
 *
 * @package    All_In_One_Wp_Utilities
 * @subpackage All_In_One_Wp_Utilities/core
 * @version    1.0.0
 * @since      1.0.0
 */

/**
 * This function will return true for a non empty array
 *
 * @param array $array Array.
 */
function aiowpu_is_array( $array ) {

	return ( is_array( $array ) && ! empty( $array ) );

}

/**
 * This function will return true for an empty var (allows 0 as true)
 *
 * @param mixed $value Value.
 */
function aiowpu_is_empty( $value ) {

	return ( empty( $value ) && ! is_numeric( $value ) );

}

/**
 * Alias of aiowpu_init()->has_setting()
 *
 * @param string $name The name.
 */
function aiowpu_has_setting( $name = '' ) {

	return aiowpu_init()->has_setting( $name );

}

/**
 * Alias of aiowpu_init()->get_setting()
 *
 * @param string $name The name.
 */
function aiowpu_raw_setting( $name = '' ) {

	return aiowpu_init()->get_setting( $name );

}

/**
 * Alias of aiowpu_init()->update_setting()
 *
 * @param string $name The name.
 * @param mixed  $value The value.
 */
function aiowpu_update_setting( $name, $value ) {

	return aiowpu_init()->update_setting( $name, $value );

}

/**
 * Alias of aiowpu_init()->get_setting()
 *
 * @param string $name  The name.
 * @param mixed  $value The value.
 */
function aiowpu_get_setting( $name, $value = null ) {

	// Check settings.
	if ( aiowpu_has_setting( $name ) ) {
		$value = aiowpu_raw_setting( $name );
	}

	// Filter.
	$value = apply_filters( "aiowpu_settings_{$name}", $value );

	return $value;
}

/**
 * This function will add a value into the settings array found in the acf object
 *
 * @param string $name  The name.
 * @param mixed  $value The value.
 */
function aiowpu_append_setting( $name, $value ) {

	// Vars.
	$setting = aiowpu_raw_setting( $name );

	// Bail ealry if not array.
	if ( ! is_array( $setting ) ) {
		$setting = array();
	}

	// Append.
	$setting[] = $value;

	// Update.
	return aiowpu_update_setting( $name, $setting );
}

/**
 * Returns data.
 *
 * @param string $name  The name.
 */
function aiowpu_get_data( $name ) {
	return aiowpu_init()->get_data( $name );
}

/**
 * Sets data.
 *
 * @param string $name  The name.
 * @param mixed  $value The value.
 */
function aiowpu_set_data( $name, $value ) {
	return aiowpu_init()->set_data( $name, $value );
}
