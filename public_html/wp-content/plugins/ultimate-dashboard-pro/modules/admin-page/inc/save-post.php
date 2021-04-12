<?php
/**
 * Admin page saving process.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $module, $post_id ) {

	// Custom js.
	if ( isset( $_POST['udb_custom_js'] ) ) {
		update_post_meta( $post_id, 'udb_custom_js', $_POST['udb_custom_js'] );
	}

	// Allowed roles.
	if ( isset( $_POST['udb_allowed_roles'] ) ) {
		$allowed_roles = is_array( $_POST['udb_allowed_roles'] ) ? $_POST['udb_allowed_roles'] : array();

		foreach ( $allowed_roles as &$allowed_role ) {
			$allowed_role = sanitize_text_field( $allowed_role );
		}

		update_post_meta( $post_id, 'udb_allowed_roles', $allowed_roles );
	}

};
