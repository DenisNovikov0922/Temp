<?php
function edd_register_option() {
	// creates our settings in the options table
	register_setting( 'udb_license', 'ultimate_dashboard_license_key', 'udb_sanitize_license' );
}
add_action( 'admin_init', 'edd_register_option' );

function udb_sanitize_license( $new ) {
	$old = get_option( 'ultimate_dashboard_license_key' );
	if( $old && $old != $new ) {
		delete_option( 'ultimate_dashboard_license_status' );
	}
	return $new;
}

// License Activation
function udb_activate_license() {

	// listen for our activate button to be clicked
	if( isset( $_POST['udb_license_activate'] ) ) {

		// run a quick security check
	 	if( ! check_admin_referer( 'udb_nonce', 'udb_nonce' ) )
			return; // get out if we didn't click the Activate button

		// retrieve the license from the database
		$license = trim( get_option( 'ultimate_dashboard_license_key' ) );


		// data to send in our API request
		$api_params = array(
			'edd_action'=> 'activate_license',
			'license' 	=> $license,
			'item_name' => urlencode( ULTIMATE_DASHBOARD_PRO_PRODUCT_NAME ), // the name of our product in EDD
			'url'       => home_url()
		);

		// Call the custom API.
		$response = wp_remote_post( ULTIMATE_DASHBOARD_PRO_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = __( 'An error occurred, please try again.' );
			}

		} else {

			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			if ( false === $license_data->success ) {

				switch( $license_data->error ) {

					case 'expired' :

						$message = sprintf(
							__( 'Your license key expired on %s.' ),
							date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
						);
						break;

					case 'revoked' :

						$message = __( 'Your license key has been disabled.' );
						break;

					case 'missing' :

						$message = __( 'Invalid license.' );
						break;

					case 'invalid' :
					case 'site_inactive' :

						$message = __( 'Your license is not active for this URL.' );
						break;

					case 'item_name_mismatch' :

						$message = sprintf( __( 'This appears to be an invalid license key for %s.' ), ULTIMATE_DASHBOARD_PRO_PRODUCT_NAME );
						break;

					case 'no_activations_left':

						$message = __( 'Your license key has reached its activation limit.' );
						break;

					default :

						$message = __( 'An error occurred, please try again.' );
						break;
				}

			}

		}

		// Check if anything passed on a message constituting a failure
		if ( ! empty( $message ) ) {
			$base_url = admin_url( 'edit.php?post_type=' . ULTIMATE_DASHBOARD_PRO_LICENSE_PAGE );
			$redirect = add_query_arg( array( 'sl_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );

			wp_redirect( $redirect );
			exit();
		}

		// $license_data->license will be either "valid" or "invalid"

		update_option( 'ultimate_dashboard_license_status', $license_data->license );
		wp_redirect( admin_url( 'edit.php?post_type=' . ULTIMATE_DASHBOARD_PRO_LICENSE_PAGE ) );
		exit();
	}
}
add_action('admin_init', 'udb_activate_license');


// License Deactivation
function udb_deactivate_license() {

	// listen for our activate button to be clicked
	if( isset( $_POST['udb_license_deactivate'] ) ) {

		// run a quick security check
	 	if( ! check_admin_referer( 'udb_nonce', 'udb_nonce' ) )
			return; // get out if we didn't click the Activate button

		// retrieve the license from the database
		$license = trim( get_option( 'ultimate_dashboard_license_key' ) );


		// data to send in our API request
		$api_params = array(
			'edd_action'=> 'deactivate_license',
			'license' 	=> $license,
			'item_name' => urlencode( ULTIMATE_DASHBOARD_PRO_PRODUCT_NAME ),
			'url'       => home_url()
		);

		// Call the custom API.
		$response = wp_remote_post( ULTIMATE_DASHBOARD_PRO_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = __( 'An error occurred, please try again.' );
			}

			$base_url = admin_url( 'edit.php?post_type=' . ULTIMATE_DASHBOARD_PRO_LICENSE_PAGE );
			$redirect = add_query_arg( array( 'sl_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );

			wp_redirect( $redirect );
			exit();
		}

		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		// $license_data->license will be either "deactivated" or "failed"
		if( $license_data->license == 'deactivated' ) {
			delete_option( 'ultimate_dashboard_license_status' );
		}

		wp_redirect( admin_url( 'edit.php?post_type=' . ULTIMATE_DASHBOARD_PRO_LICENSE_PAGE ) );
		exit();

	}
}
add_action('admin_init', 'udb_deactivate_license');

// display messages to the customer
function udb_license_admin_notices() {
	
	$current_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

	if ( isset( $_GET['sl_activation'] ) && ! empty( $_GET['message'] ) && strpos( $current_url, ULTIMATE_DASHBOARD_PRO_LICENSE_PAGE ) !== false ) {

		switch( $_GET['sl_activation'] ) {

			case 'false':
				$message = urldecode( $_GET['message'] );
				?>
				<div class="error">
					<p><?php echo $message; ?></p>
				</div>
				<?php
			break;

		}
	}
}
add_action( 'admin_notices', 'udb_license_admin_notices' );