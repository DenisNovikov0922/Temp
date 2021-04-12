<?php

/*****************************************************************/
/* LICENSE CHECK (APL) */
/*****************************************************************/

require_once( wphave_admin_dir( 'inc/init/setup-config.php' ) );
require_once( wphave_admin_dir( 'inc/init/setup-functions.php' ) );


/*****************************************************************/
/* INCLUDE LICENSE DE/ACTIVATION JS */
/*****************************************************************/

function wphave_admin_activation_js( $hook ) {

	// method to get the page hook
	//wp_die($hook);

	// Load the following JS files only on plugin page
	if( $hook != 'tools_page_wphave-admin-purchase-code' ) {
		return;
	}

	wp_enqueue_script( 
		'wphave-admin-setup',  wphave_admin_path( 'assets/js/setup.js' ), array( 'jquery' ), null, true 
	);

	wp_localize_script( 
		'wphave-admin-setup', 'wp_ajax_data', array( 'ajax_url' => admin_url( 'admin-ajax.php' ),
	));

	// Localize the script with new data
	$activation_js_array = array(
		// --> !!! For security check and verify wp_nonce() before sending AJAX request
		'verify_security_nonce' => wp_create_nonce( 'verify_sec_request' ),
		'label_try' => __( 'Try again', 'wphave-admin' ) . '!',
		'label_done' => __( 'Done', 'wphave-admin' ) . '!',
		'label_unlocked' => __( 'Unlocked', 'wphave-admin' ) . '!',
		'label_check' => __( 'Checking', 'wphave-admin' ) . ' ...',
		'label_unlock' => __( 'Unlocking', 'wphave-admin' ) . ' ...',
		'label_reset' => __( 'Will be reset', 'wphave-admin' ) . ' ...',
	);
	wp_localize_script( 'wphave-admin-setup', 'WP_JS_Plugin_Activation', $activation_js_array );

}

add_action( 'admin_enqueue_scripts', 'wphave_admin_activation_js', 30 );


/*****************************************************************/
/* ENVATO API CONNECTION */
/*****************************************************************/

function wphave_admin_envato_purchase_validation( $get_code ) {

	$code = trim( $get_code );

	// Make sure the code is valid before sending it to Envato
	if( ! preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $code) ) {
		return __( 'Invalid Purchase Code', 'wphave-admin' );
	}
	
	// Envato API url
	$url = 'https://api.envato.com/v3/market/author/sale?code=' . $code;	
	$curl = curl_init( $url );
	
	// Base64 encode key
	$str = 'Z0hZS25tMkJyZnJIQWJuVzNOcGtIWGUzNGNGSEQ0TGI=';
	$token = base64_decode( $str );
	
	$header = array();
	$header[] = 'Authorization: Bearer ' . $token;
	//$header[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10.11; rv:41.0) Gecko/20100101 Firefox/41.0';
	$header[] = 'User-Agent: Plugin purchase code verification';
	$header[] = 'timeout: 20';
	
	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt( $curl, CURLOPT_HTTPHEADER, $header );	
	$envatoData = curl_exec( $curl );
	curl_close( $curl );
	$envatoData = json_decode( $envatoData );
	
	return $envatoData;
	
}


/*****************************************************************/
/* ENVATO API - PURCHASE CODE VERIFICATION */
/*****************************************************************/

function wphave_admin_purchase_code_verify( $code ) {
	
	// Connect to Envato API
	$envatoData = wphave_admin_envato_purchase_validation( $code );
	
	// Check if Envato API data is accessible
	if( isset( $envatoData->item->name ) ) {
		
		// Check if the purchase code is valid for a different theme
		if( $envatoData->item->id != WPHAVE_ADMIN_ENVATO_ID ) {
			
			// Purchase code is valid, but for a different theme
			return 'different';
			
		}		
			
		// Purchase Code is valid
		return 'valid';		
	}
		
	// Purchase Code is invalid
	return 'invalid';
	
}


/*****************************************************************/
/* ENVATO PURCHASE CODE SYNTAX */
/*****************************************************************/

function wphave_admin_envato_purchase_code() {
			
	return 'envato_purchase_code_' . WPHAVE_ADMIN_ENVATO_ID;
	
}


/*****************************************************************/
/* ENVATO PURCHASE CODE TRANSIENT SYNTAX */
/*****************************************************************/

function wphave_admin_envato_purchase_code_transient() {
			
	return 'envato_purchase_code_transient_' . WPHAVE_ADMIN_ENVATO_ID;
	
}



/*****************************************************************/
/* GET AND SAVE PURCHASE PLUGIN DETAILS */
/*****************************************************************/

function wphave_admin_purchase_details( $code ) {

	// Define plugin purchase transient
	$transient_id = wphave_admin_envato_purchase_code_transient();
	
	// Get the saved purchase code
	$saved_purchase_code = get_option( wphave_admin_envato_purchase_code() );	
	
	// Check if the old transient exist and the saved purchase code is the same as the incoming purchase code
	if( get_transient( $transient_id ) && $code == $saved_purchase_code ) {
		// Stop here, resaving is not necessary
		return;
	}
		
	// Delete the old transient
	delete_transient( $transient_id );	
	
	// Connect to Envato API
	$envatoData = wphave_admin_envato_purchase_validation( $code );	
	
	// Create transient if plugin purchase transient not exists
	if( false === ( $get_data = get_transient( $transient_id ) ) ) {

		// Get data from Envato API
		$data = $envatoData;
		
		if( isset( $data ) && wphave_admin_purchase_code_verify( $code ) == 'valid' ) {
			
			// Define theme purchase user data
			$get_data = array();

			$get_data['theme_id'] = $data->item->id;
			$get_data['theme_name'] = $data->item->name;
			$get_data['amount'] = $data->amount;
			$get_data['license'] = $data->license;
			$get_data['purchase_count'] = $data->purchase_count;
			$get_data['sold_at'] = $data->sold_at;
			$get_data['support_amount'] = $data->support_amount;
			$get_data['supported_until'] = $data->supported_until;
			$get_data['buyer'] = $data->buyer;
			$get_data['purchase_code'] = $code;

			// Set plugin purchase transient, only if purchase code is valid
			// Will automatically resaved each 14 days by aplVerifyLicense() function
			set_transient( $transient_id, $get_data, 14 * DAY_IN_SECONDS );

		} else {
			$get_data = 'Data Error';
		}				

	}
	
}


/*****************************************************************/
/*  GET PURCHASE DETAILS DATA */
/*****************************************************************/

function wphave_admin_get_purchase_theme_details() {
	
	// Get purchase data from the users purchase code
	$purchase_code = get_option( wphave_admin_envato_purchase_code() );						
	$transient_id = wphave_admin_envato_purchase_code_transient();
	$purchase_data = get_transient( $transient_id );
	
	$wp_date_format = get_option('date_format');
	$current_date = current_time( 'mysql' );
	$supported = '';
	if( isset( $purchase_data['supported_until'] ) ) {
		$supported = date( $wp_date_format, strtotime( $purchase_data['supported_until'] ) );
	}
	$purchased = '';
	if( isset( $purchase_data['sold_at'] ) ) {
		$purchased = date( $wp_date_format, strtotime( $purchase_data['sold_at'] ) );
	}
	
	$data = array();
	
	// Check Envato purchase details are available
	if( wphave_admin_purchase_code_verify( $purchase_code ) == 'valid' ) {
	
		// Theme license details from Envato
		$data['theme_name'] = isset( $purchase_data['theme_name'] ) ? $purchase_data['theme_name'] : 'n/a';
		$data['buyer'] = isset( $purchase_data['buyer'] ) ? $purchase_data['buyer'] : 'n/a'; 
		$data['license'] = isset( $purchase_data['license'] ) ? $purchase_data['license'] : 'n/a';
		$data['purchase_count'] = isset( $purchase_data['purchase_count'] ) ? $purchase_data['purchase_count'] : 'n/a';
		$data['sold_at'] = isset( $purchase_data['sold_at'] ) ? $purchased : 'n/a';
		$data['supported_until'] = isset( $purchase_data['supported_until'] ) ? $supported : 'n/a';	
		$data['purchase_code'] = isset( $purchase_data['purchase_code'] ) ? $purchase_data['purchase_code'] : 'n/a';
	
	// Otherwise use purchase details from manually created license
	} else {
		
		// Theme license details from personal issued license
		$data['theme_name'] = WPHAVE_ADMIN_PLUGIN_NAME;
		$data['license'] = esc_html__( 'You are using a personally issued license.', 'wphave-admin' );
		$data['purchase_code'] = isset( $purchase_code ) ? $purchase_code : 'n/a';
		$data['supported_until'] = 'n/a';
		
	}

	return $data;
	
}

		
/*****************************************************************/
/* PURCHASE CODE AJAX CHECK */
/*****************************************************************/

function wphave_admin_license_install_process() {

	// --> !!! For security check and verify wp_nonce() before saving new values from AJAX request
	check_ajax_referer( 'verify_sec_request', 'security' );

	if( $_POST ) {		

		// Get user purchase code from theme validation check
		$request = json_decode( stripslashes( $_POST['fieldData'] ) );

		if( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			$code = $request[0]->purchase_code;
			$root_url = $request[0]->purchase_root_url;
			$client_mail = $request[0]->purchase_client_mail;
		}
		
		$license_action = esc_html__( 'Something went wrong', 'wphave-admin' );
		
		// 1. Check if there is a connection to the Envato API
		if( wphave_admin_cd_license_action( 'envato', $code )['status'] == 'success' ) {	

			// 2. Check if there is a connection to the license server
			if( wphave_admin_cd_license_action( 'license_server' )['status'] == 'success' ) {	

				// 3. Send install license notice (if process is successfully set theme license to active)
				$license_action = wphave_admin_cd_license_action( 'install', $code, $root_url, $client_mail );
				
			} else {

				// 3. License Server connection failed
				// Cancel license installation and send server connection notice
				$license_action = wphave_admin_cd_license_action( 'license_server' );

			}

		} else {

			// 2. Evato API connection failed
			// Cancel license installation and send Evato connection notice
			$license_action = wphave_admin_cd_license_action( 'envato' );

		}
		
		wp_send_json( array(
			//'success' => 'SUCCESS',
			//'fieldData' => $request,
			//'verify_status' => wphave_admin_purchase_code_verify( $code, $root_url, $client_mail ),
			'license_action' => $license_action['notice'],
			'license_status' => $license_action['status']
		) );			

	}

	die();

}

add_action( 'wp_ajax_wphave_admin_license_install_process', 'wphave_admin_license_install_process' );
add_action( 'wp_ajax_nopriv_wphave_admin_license_install_process', 'wphave_admin_license_install_process' );


/*****************************************************************/
/* UNLOCK PURCHASE CODE AJAX */
/*****************************************************************/

function wphave_admin_license_uninstall_process() {

	// --> !!! For security check and verify wp_nonce() before saving new values from AJAX request
	check_ajax_referer( 'verify_sec_request', 'security' );

	if( $_POST ) {		

		// Get client action to uninstall license
		$request = json_decode( stripslashes( $_POST['fieldData'] ) );

		if( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			$action = $request[0]->command;
		}
		
		$license_action = esc_html__( 'Something went wrong', 'wphave-admin' );
		
		// 1. Check for client unlocking license request
		if( $action == 'unlock_purchase_code' ) {

			// 2. Check if there is a connection to the license server
			if( wphave_admin_cd_license_action( 'license_server' )['status'] == 'success' ) {	
				
				// 3. Send uninstall license notice (if process is successfully set theme license to inactive)
				$license_action = wphave_admin_cd_license_action( 'uninstall' );				
			
			} else {			
			
				// 3. License Server connection failed
				// Cancel license uninstallation and send server connection notice
				$license_action = wphave_admin_cd_license_action( 'license_server' );
				
			}
		}

		wp_send_json( array(
			//'success' => 'SUCCESS',
			'license_action' => $license_action['notice'],
			'license_status' => $license_action['status'],
		) );			

	}

	die();

}

add_action( 'wp_ajax_wphave_admin_license_uninstall_process', 'wphave_admin_license_uninstall_process' );
add_action( 'wp_ajax_nopriv_wphave_admin_license_uninstall_process', 'wphave_admin_license_uninstall_process' );


/*****************************************************************/
/* UNLOCK PURCHASE CODE AFTER PLUGIN DEACTIVATION */
/*****************************************************************/

function wphave_admin_license_uninstall_after_plugin_deactivation() {
	
	// Check current user can manage plugins
    if( ! current_user_can( 'activate_plugins' ) ) {
		// Stop here, if that's not the case
		return;
	}
        
	// Check for WP plugin request
    $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
	
	// Check for WP admin referer
	check_admin_referer( "deactivate-plugin_{$plugin}" );
	
	// Uninstall license automatically after deactivating the plugin
	wphave_admin_cd_license_action( 'uninstall' );	
	
}

register_deactivation_hook( WPHAVE_ADMIN_PLUGIN, 'wphave_admin_license_uninstall_after_plugin_deactivation' );


/*****************************************************************/
/* UNLOCK PURCHASE CODE AFTER PLUGIN UNINSTALLATION */
/*****************************************************************/

function wphave_admin_license_uninstall_after_plugin_uninstallation() {
	
	// Check current user can manage plugins
    if( ! current_user_can( 'activate_plugins' ) ) {
		// Stop here, if that's not the case
		return;
	}

	// Uninstall license automatically after uninstalling the plugin
	wphave_admin_cd_license_action( 'uninstall' );	
	
}

register_uninstall_hook( WPHAVE_ADMIN_PLUGIN, 'wphave_admin_license_uninstall_after_plugin_uninstallation' );


/*****************************************************************/
/* RESET LICENSE / PURCHASE CODE AJAX */
/*****************************************************************/

function wphave_admin_license_reset_process() {

	// --> !!! For security check and verify wp_nonce() before saving new values from AJAX request
	check_ajax_referer( 'verify_sec_request', 'security' );

	if( $_POST ) {		

		// Get client action to reset license
		$request = json_decode( stripslashes( $_POST['fieldData'] ) );

		if( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			$action = $request[0]->command;
		}
		
		$license_action = esc_html__( 'Something went wrong', 'wphave-admin' );		
		
		// 1. Check for client reset license request
		if( $action == 'reset_purchase_code' ) {		
			
			// Reset the license and send reset notice
			$license_action = wphave_admin_cd_license_action( 'reset' );
		
		}

		wp_send_json( array(
			//'success' => 'SUCCESS',
			'license_action' => $license_action['notice'],
			'license_status' => $license_action['status'],
		) );			

	}

	die();

}

add_action( 'wp_ajax_wphave_admin_license_reset_process', 'wphave_admin_license_reset_process' );
add_action( 'wp_ajax_nopriv_wphave_admin_license_reset_process', 'wphave_admin_license_reset_process' );


/*****************************************************************/
/* PLUGIN LICENSE UNINSTALLATION (TODO) */
/*****************************************************************/

function wphave_admin_theme_license_uninstall_todo() {
	
	// Set theme license status to inactive
	update_option( 'wp_admin_theme_cd_license', 'inactive', 'yes' );

	// Send activation message to theme author
	//wphave_admin_activation_message('unlock');

	// Delete theme purchase details transient
	delete_transient( wphave_admin_envato_purchase_code_transient() );

	// Delete theme purchase code
	delete_option( wphave_admin_envato_purchase_code() );	
	
}


/*****************************************************************/
/* LICENSE INSTALLATION / LOCKING (APL) */
/*****************************************************************/

function wphave_admin_license_add( $code, $root_url, $client_mail = '' ) {
	
	// MySQL connect
	$mysqli = wphave_admin_mysqli_connect();
	
	// Connect to Envato server and get purchase code
	$envato_verify = wphave_admin_aplVerifyEnvatoPurchase( $code );
	
	// Check Envato purchase code is valid
	if( empty( $envato_verify ) ) {		
			
		// Install license on license server
		return wphave_admin_aplInstallLicense( $root_url, $client_mail, $code, $mysqli );
					
	}
	
	// License installation aborted
	return false;	
	
}


/*****************************************************************/
/* PLUGIN LICENSE INSTALLATION (TODO) */
/*****************************************************************/

function wphave_admin_license_add_todo( $code ) {
	
	// Save theme license status active
	update_option( 'wp_admin_theme_cd_license', 'active', 'yes' );

	// Save theme purchase code
	update_option( wphave_admin_envato_purchase_code(), $code, 'yes' );

	// Save theme purchase details
	wphave_admin_purchase_details( $code );

	// Send activation message to theme author
	//wphave_admin_activation_message();	
	
}


/*****************************************************************/
/* LICENSE SERVER ACTIONS + NOTICES (APL) */
/*****************************************************************/

function wphave_admin_cd_license_action( $action, $code = '', $root_url = '', $client_mail = '' ) {
	
	// MySQL connect
	$mysqli = wphave_admin_mysqli_connect();
	
	$request = array();
	
	// Envato connection
	if( $action === 'envato' ) {
		
		// Action to check connection
		$envato_verify = wphave_admin_aplVerifyEnvatoPurchase( $code );

		// Connection successful
		$request['status'] = 'success';
		$request['notice'] = esc_html__( 'Connection to Envato server successfull', 'wphave-admin' );
		
		// Connection failed
		if( ! empty( $envato_verify ) ) {
			$request['status'] = 'error';
			$request['notice'] = esc_html__( 'Connection to Envato server failed', 'wphave-admin' );
		}
		
	}
	
	// License server connection
	if( $action === 'license_server' ) {
		
		// Action to check license server connection
		$license_server = wphave_admin_aplCheckConnection();

		// Connection successful
		$request['status'] = 'success';
		$request['notice'] = esc_html__( 'Connection to license server successfull', 'wphave-admin' );
		
		// Connection failed
		if( ! empty( $license_server ) ) {
			$request['status'] = 'error';
			$request['notice'] = esc_html__( 'Connection to license server failed', 'wphave-admin' ) . ': ' . $license_server['notification_text'];
		}
		
	}
	
	// License verification
	if( $action === 'verify' ) {
		
		// Action to verify license
		$license_verify = wphave_admin_aplVerifyLicense( $mysqli );
		
		// Verification failed
		$request['status'] = 'error';
		$request['notice'] = $license_verify['notification_text'];

		// Verification successful
		if( $license_verify['notification_case'] == "notification_license_ok" ) {
			$request['status'] = 'success';
			$request['notice'] = esc_html__( 'License successfully verified', 'hannah-cd' );
		}
		
	}
	
	// License update
	if( $action === 'update' ) {
		
		// Action to update license ip address
		$license_update = wphave_admin_aplUpdateLicense( $mysqli );

		// Update failed
		$request['status'] = 'error';
		$request['notice'] = $license_update['notification_text'];

		// Update successful
		if( $license_update['notification_case'] == "notification_license_ok" ) {
			$request['status'] = 'success';
			$request['notice'] = esc_html__( 'License has been successfully updated', 'hannah-cd' );
		}
		
	}
	
	// License installation
	if( $action === 'install' ) {
		
		// Action to install license (if process is successfully)
		$license_add = wphave_admin_license_add( $code, $root_url, $client_mail );

		// Installation failed
		$request['status'] = 'error';
		$request['notice'] = esc_html__( 'License installing failed', 'wphave-admin' ) . ': ' . $license_add['notification_text'];
		
		// Installation successful
		if( $license_add['notification_case'] == "notification_license_ok" ) {
			$request['status'] = 'success';
			$request['notice'] = esc_html__( 'License has been successfully installed', 'wphave-admin' );			
			
			// ToDo after installing license
			wphave_admin_license_add_todo( $code );			
		}
		
	}
	
	// License uninstallation
	if( $action === 'uninstall' ) {
		
		// Action to uninstall license (if process is successfully)
		$license_unlock = wphave_admin_aplUninstallLicense( $mysqli );

		// Uninstallation failed
		$request['status'] = 'error';
		$request['notice'] = esc_html__( 'License unlocking failed', 'wphave-admin' ) . ': ' . $license_unlock['notification_text'];
		
		// Uninstallation successful
		if( $license_unlock['notification_case'] == "notification_license_ok" ) {
			$request['status'] = 'success';
			$request['notice'] = esc_html__( 'License has been successfully unlocked', 'wphave-admin' );
			
			// ToDo after uninstalling license
			wphave_admin_theme_license_uninstall_todo();
		}
		
	}
	
	// License reset
	if( $action === 'reset' ) {
		
		// Check for license table
		global $wpdb;
		$license_table = WPHAVE_ADMIN_APL_DATABASE_TABLE;
		$query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $license_table ) );

		// Check if license table exist
		if( $wpdb->get_var( $query ) == $license_table ) {

			$request['notice'] = esc_html__( 'The license table was found and the license was reset.', 'wphave-admin' );
			$request['status'] = 'success';

			// Action to delete license table manually
			mysqli_query( $mysqli, "DELETE FROM " . $license_table );
			mysqli_query( $mysqli, "DROP TABLE " . $license_table );

		} else {

			$request['notice'] = esc_html__( 'License table not found. Please contact the author of the plugin and ask him to unlock the license manually.', 'wphave-admin' );
			$request['status'] = 'error';
		}

		// ToDo after reset the license
		wphave_admin_theme_license_uninstall_todo();
		
	}
	
	return $request;

}


/*
 *******************
 * LOCAL INSTALLATION
 *******************
 *
 *  Function to check if the current WordPress Installation is a local development environment.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	2.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

function wphave_admin_wp_is_local_installation() {

	// Get the full WordPress installation path (included subfolders)
	$site_url = get_site_url();
	if( is_multisite() ) {
		$site_url = get_site_url( get_current_blog_id() );
	}
	
	// Check if the site url contains "localhost" or ports like ":80", :8888" or ":3306"
	if( preg_match("/(localhost|:80|:8080|:8888|:3306|:4433)/i", $site_url ) ) {
		return true;
	}
	
	// Check if the site url contains a valid ip address
	$ip_domain = parse_url( $site_url );
	if( filter_var( $ip_domain['host'], FILTER_VALIDATE_IP ) ) {
		return true;
	}
	
	return false;
	
}


/*****************************************************************/
/* ACTIVATION STATUS */
/*****************************************************************/

function wphave_admin_activation_status() {
	
	if( wphave_admin_plugin_with_theme() ) {
		// Unlock
		return true;
	}
	
	if( wphave_admin_wp_is_local_installation() ) {
		// Unlock
		return true;
	}
			
	$activation_status = get_option( 'wp_admin_theme_cd_license' );
	if( is_multisite() ) {
		$blog_id = 1; // <-- Option from main site
		$activation_status = get_blog_option( $blog_id, 'wp_admin_theme_cd_license', array() );
	}
	

	
	// Unlock
	return true;
	
}


/*****************************************************************/
/* ACTIVATION MESSAGE */
/*****************************************************************/

function wphave_admin_activation_message( $status = '' ) {
	
	// Get the users domain name
	$domain = wphave_admin_root_url();
	
	// Get purchase data from the users purchase code
	$purchase_data = wphave_admin_purchase_details();
	
	// Get license status
	$activation_status = get_option( 'wp_admin_theme_cd_license' );
	$license_status = '<strong style="color:green">' . esc_html__( 'Activation succesfull', 'wphave-admin' ) . '</strong>';
	if( ! $activation_status || $activation_status == 'inactive' ) {
		$license_status = '<strong style="color:red">' . esc_html__( 'Activation denied', 'wphave-admin' ) . '</strong>';
	}
	
	if( $status == 'unlock' ) {
		$license_status = '<strong style="color:orange">' . esc_html__( 'License has been unlocked', 'wphave-admin' ) . '</strong>';
	}
	
	// Message subject
	$subject = esc_html__( 'Plugin Activation for', 'wphave-admin' ) . ' ' . WPHAVE_ADMIN_PLUGIN_NAME . ' - ' . $purchase_data['purchase_code'];
		
	// Message body
	$message = '<strong>' . $subject . '</strong><br><br>';
	$message .= esc_html__( 'Domain', 'wphave-admin' ) . ': <a href="' . $domain . '">' . $domain . '</a><br>';
	$message .= esc_html__( 'Status', 'wphave-admin' ) . ': ' . $license_status . '<br><br>';
	$message .= '<small>';
	$message .= esc_html__( 'Plugin', 'wphave-admin' ) . ': ' . $purchase_data['theme_name'] . '<br>';
	$message .= esc_html__( 'Buyer', 'wphave-admin' ) . ': ' . $purchase_data['buyer'] . '<br>';
	$message .= esc_html__( 'License', 'wphave-admin' ) . ': ' . $purchase_data['license'] . '<br>';
	$message .= esc_html__( 'Purchase Count', 'wphave-admin' ) . ': ' . $purchase_data['purchase_count'] . '<br>';
	$message .= esc_html__( 'Sold at', 'wphave-admin' ) . ': ' . $purchase_data['sold_at'] . '<br>';
	$message .= esc_html__( 'Support until', 'wphave-admin' ) . ': ' . $purchase_data['supported_until'];
	$message .= '</small>';
	
	// Message headers
	$headers = array('Content-Type: text/html; charset=UTF-8');

	// Create message
	wp_mail( WPHAVE_ADMIN_AUTHOR_MAIL, $subject, $message, $headers );

}