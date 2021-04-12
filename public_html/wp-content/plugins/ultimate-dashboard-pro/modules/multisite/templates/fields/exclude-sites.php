<?php
/**
 * Exclude sites field.
 *
 * @package Ultimate_Dashboard_Pro
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$exclude = get_site_option( 'udb_multisite_exclude' );

	echo '<input type="text" placeholder="3, 14, 291" name="udb_multisite_exclude" value="' . esc_attr( $exclude ) . '" />';

	echo '<p>' . __( 'Comma separated list of subsite ID\'s. Exclude certain websites from the Blueprint.', 'ultimatedashboard' ) . '</p>';

};
