<?php
/**
 * Admin bar logo url field.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$branding           = get_option( 'udb_branding' );
	$admin_bar_logo_url = isset( $branding['admin_bar_logo_url'] ) ? $branding['admin_bar_logo_url'] : false;

	echo '<input type="url" name="udb_branding[admin_bar_logo_url]" class="regular-text" value="' . esc_attr( $admin_bar_logo_url ) . '" />';

};
