<?php
/**
 * Accent color field.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$branding     = get_option( 'udb_branding' );
	$accent_color = isset( $branding['accent_color'] ) ? $branding['accent_color'] : '#0073AA';

	echo '<input type="text" name="udb_branding[accent_color]" value="' . esc_attr( $accent_color ) . '" class="udb-color-field udb-branding-color-field" data-default="#0073aa" />';

};
