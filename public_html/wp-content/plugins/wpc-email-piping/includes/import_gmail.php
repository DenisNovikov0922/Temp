<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$wpsc_ep_imap_email_address  = get_option('wpsc_ep_imap_email_address');

if( email_exists( $wpsc_ep_imap_email_address ) ){
  return;
}

$token_url     = 'https://www.googleapis.com/oauth2/v4/token';
$client_id     = get_option('wpsc_ep_client_id','');
$client_secret = get_option('wpsc_ep_client_secret','');
$refresh_token = get_option('wpsc_ep_refresh_token','');
$user          = get_option('wpsc_ep_email_address','');
$historyId     = get_option('wpsc_ep_historyId','');

if($client_id && $client_secret && $refresh_token && $user){
  
  $response = wp_remote_post( $token_url, array(
    'method'      => 'POST',
    'timeout'     => 45,
    'redirection' => 5,
    'httpversion' => '1.0',
    'blocking'    => true,
    'headers'     => array(),
    'body'        => array(
        'client_id'     => $client_id,
        'client_secret' => $client_secret,
        'refresh_token' => $refresh_token,
        'grant_type'    => 'refresh_token',
    ),
    'cookies'     => array()
    )
  );
  
  if ( !is_wp_error( $response ) ) {
    
		if($debug_mode){
			echo '==> Access token successful. Below is response:<br>';
			echo '<pre>';
			print_r($response['body']);
			echo '<pre>';
		}
		
		$access = json_decode( $response['body'], true );
    $access_token = $access['access_token'];
    include_once( WPSC_EP_ABSPATH . 'includes/class-process-emails.php' );
    new WPSC_EP_Process_Emails( $access_token, $user, $historyId );
		
  } else {
		
		if($debug_mode){
			echo '==> Access token failed. Below is error messege:<br>';
			echo '<pre>';
			print_r($response);
			echo '<pre>';
			echo '==> Aorting email piping!<br>';
		}
		
	}
  
} else {
	
	if($debug_mode){
		echo '==> Please check Google App Settings. Aborting email piping!<br>';
	}
	
}
