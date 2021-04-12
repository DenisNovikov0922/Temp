<?php
/**
 * Clear contact form logs.
 *
 * @package Ultimate_Dashboard_PRO
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$nonce      = isset( $_GET['nonce'] ) ? $_GET['nonce'] : '';
	$post_id    = isset( $_GET['post_id'] ) ? absint( $_GET['post_id'] ) : 0;
	$page       = get_post( $post_id );
	$check      = get_post_meta( $post_id, 'udb_contact_form_logs', true );
	$is_deleted = delete_post_meta( $post_id, 'udb_contact_form_logs' );

	if ( ! wp_verify_nonce( $nonce, 'udb_clear_contact_form_' . $post_id ) ) {
		wp_send_json_error( __( 'Invalid token', 'ultimatedashboard' ) );
	}

	if ( ! $page ) {
		wp_send_json_error( __( 'Post not found', 'ultimatedashboard' ) );
	}

	if ( $is_deleted ) {

		wp_send_json_success(
			array(
				'message' => '<div class="udb-form-widget-success-notice">' . __( 'Log deleted', 'ultimatedashboard' ) . '</div>',
			)
		);

	} else {

		wp_send_json_error(
			array(
				'message' => '<div class="udb-form-widget-error-notice">' . __( 'Unable to delete log entries. Please try again later.', 'ultimatedashboard' ) . '</div>',
			)
		);

	}

};
