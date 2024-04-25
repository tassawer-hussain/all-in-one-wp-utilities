<?php
/**
 * Helpers User Multiple Roles
 *
 * @package    All_In_One_Wp_Utilities/modules/disable-unnecessary-features
 * @subpackage All_In_One_Wp_Utilities/modules/disable-unnecessary-features/helper
 */

/**
 * Return the database type.
 *
 * @return string
 */
function aiowpu_database_type() {

	$dbtype = get_transient( 'aiowpu_database_type' );

	if ( false === $dbtype ) {

		global $wpdb;
		$vers = $wpdb->get_var( 'SELECT VERSION() as mysql_version' ); // phpcs:ignore
		if ( stripos( $vers, 'MARIA' ) !== false ) {
			$dbtype = 'MARIA';
		}
		$dbtype = 'MYSQL';

		// expire after 1 week.
		set_transient( 'aiowpu_database_type', $dbtype, 604800 );

	}

	return $dbtype;

}

/**
 * Return the database version.
 *
 * @return string
 */
function aiowpu_database_version() {

	$dbversion = get_transient( 'aiowpu_database_version' );

	if ( false === $dbversion ) {

		global $wpdb;
		$vers      = $wpdb->get_var( 'SELECT VERSION() as mysql_version' ); // phpcs:ignore
		$dbversion = explode( '-', $vers ) [0]; // trim any extra information.

		set_transient( 'aiowpu_database_version', $dbversion, 604800 ); // expire after 1 week.

	}

	return $dbversion;
}

/**
 * Return badge for MySQL Database.
 *
 * @return string
 */
function aiowpu_database_badge_mysql() {

	$ver = aiowpu_database_version();
	$col = 'error';

	if ( version_compare( $ver, '5.6', '>=' ) ) {
		$col = 'warning';
	}

	if ( version_compare( $ver, '5.7', '>=' ) ) {
		$col = 'info';
	}

	return $col;
}

/**
 * Return badge for MariaDB Database.
 *
 * @return string
 */
function aiowpu_database_badge_maria() {

	$ver = aiowpu_database_version();
	$col = 'error';

	if ( version_compare( $ver, '10.0', '>=' ) ) {
		$col = 'warning';
	}

	if ( version_compare( $ver, '10.1', '>=' ) ) {
		$col = 'info';
	}

	return $col;
}

/**
 * Return PHP Version information.
 *
 * @return string
 */
function aiowpu_php_version() {

	return explode( '-', phpversion() ) [0]; // trim any extra information.

}

/**
 * Return badge for PHP version.
 *
 * @return string
 */
function aiowpu_php_version_badge() {

	$ver = aiowpu_php_version();
	$col = 'error';

	if ( version_compare( $ver, '7.2', '>=' ) ) {
		$col = 'warning';
	}

	if ( version_compare( $ver, '7.3', '>=' ) ) {
		$col = 'info';
	}

	return $col;
}

/**
 * Display server requirements information on "Disble Unnecessary Features" settings page.
 *
 * @return void
 */
function aiowpu_display_server_requirements() {

	$dbtype    = aiowpu_database_type();
	$dbversion = aiowpu_database_version();
	$dbbadge   = ( 'MYSQL' === $dbtype ) ? aiowpu_database_badge_mysql() : aiowpu_database_badge_maria();

	echo sprintf(
		'<div class="aiowpu-server-requirements">
			<div class="desc">
				<h2>%s</h2>
				<p class="description">%s</p>
				<p class="description">%s</p>
			</div>
			<div class="badge">
				<a href="%s" target="_blank" class="update-message notice inline notice-%s notice-alt">PHP %s</a>&nbsp;
				<a href="%s" target="_blank" class="update-message notice inline notice-%s notice-alt">%s %s</a>
			</div>
		</div>
		<hr>',
		esc_html__( 'Server Requirements Check', 'all-in-one-wp-utilities' ),
		esc_html__( 'In simple terms, if both boxes are blue, you\'re good to go.', 'all-in-one-wp-utilities' ),
		esc_html__( 'However, if one of them is red, it\'s a sign that you might need to upgrade your hosting for better site performance.', 'all-in-one-wp-utilities' ),
		esc_url( 'https://www.php.net/supported-versions.php' ),
		esc_attr( aiowpu_php_version_badge() ),
		esc_html( aiowpu_php_version() ),
		esc_url( 'https://www.fromdual.com/support-for-mysql-from-oracle' ),
		esc_attr( $dbbadge ),
		esc_html( $dbtype ),
		esc_html( $dbversion ),
	);

}
add_action( 'aiowpu_disable_unnecessary_features_settings_rigt_section', 'aiowpu_display_server_requirements', 5 );

/**
 * Display bulk select unselect buttons.
 *
 * @return void
 */
function aiowpu_display_bulk_select_action() {

	$dbtype    = aiowpu_database_type();
	$dbversion = aiowpu_database_version();
	$dbbadge   = ( 'MYSQL' === $dbtype ) ? aiowpu_database_badge_mysql() : aiowpu_database_badge_maria();

	echo sprintf(
		'<div class="aiowpu-duf-bulk-actions">
			<div class="desc">
				<h2>%s</h2>
				<p class="description">%s</p>
				<button id="btn-all-select" class="button button-secondary" style="width: auto; height: 32px;">Select All</button>
				<button id="btn-all-unselect" class="button button-secondary" style="width: auto; height: 32px;">UnSelect ALL</button>
			</div>
		</div>
		<hr>',
		esc_html__( 'Check / UnCheck All', 'all-in-one-wp-utilities' ),
		esc_html__( 'Use these buttons to select/unselect all options with one click.', 'all-in-one-wp-utilities' ),
	);

}
add_action( 'aiowpu_disable_unnecessary_features_settings_rigt_section', 'aiowpu_display_bulk_select_action', 10 );

