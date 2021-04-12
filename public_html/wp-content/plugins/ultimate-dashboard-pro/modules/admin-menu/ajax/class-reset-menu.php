<?php
/**
 * Reset menu.
 *
 * @package Ultimate_Dashboard_PRO
 */

namespace UdbPro\AdminMenu\Ajax;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Class to handle ajax request to reset admin menu.
 */
class Reset_Menu {

	/**
	 * The class instance.
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Get instance of the class.
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Reset menu.
	 */
	public function reset() {
		$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';
		$role  = isset( $_POST['role'] ) ? $_POST['role'] : '';

		if ( ! wp_verify_nonce( $nonce, 'udb_admin_menu_reset_menu' ) ) {
			wp_send_json_error( __( 'Invalid token', 'ultimatedashboard' ) );
		}

		if ( ! $role ) {
			wp_send_json_error( __( 'Role is not specified', 'ultimatedashboard' ) );
		}

		if ( 'all' === $role ) {
			delete_option( 'udb_admin_menu' );
		} else {
			$menu = get_option( 'udb_admin_menu', array() );

			if ( isset( $menu[ $role ] ) ) {
				unset( $menu[ $role ] );
				update_option( 'udb_admin_menu', $menu );
			}
		}

		wp_send_json_success( esc_attr( $role ) . ' ' . __( 'Menu reset successfully', 'ultimatedashboard' ) );
	}

}
