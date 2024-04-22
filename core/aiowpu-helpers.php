<?php
/**
 * The basic helpers functions
 *
 * @package    All_In_One_Wp_Utilities
 * @subpackage All_In_One_Wp_Utilities/core
 * @version    1.0.0
 * @since      1.0.0
 */

/**
 * Generate uuid hash
 *
 * @param string $name   The name.
 * @param string $action The action.
 */
function aiowpu_uuid_hash( $name = '_wpnonce', $action = -1 ) {
	$user = wp_get_current_user();
	$uid  = (int) $user->ID;

	if ( ! $uid ) {
		$uid = apply_filters( 'nonce_user_logged_out', $uid, $action );
	}

	$token = wp_get_session_token();
	$i     = wp_nonce_tick();

	$hash = substr( wp_hash( $i . '|' . $action . '|' . $uid . '|' . $token, 'nonce' ), -12, 10 );

	if ( ! isset( ${'_REQUEST'}[ $name ] ) ) {
		${'_REQUEST'}[ $name ] = $hash;
	}
}
