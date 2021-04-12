<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

global $current_user;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
}

delete_option( 'wpsc_ep_refresh_token' );
delete_option( 'wpsc_ep_imap_uid' );

// Piping Type
$wpsc_ep_piping_type = isset($_POST) && isset($_POST['wpsc_ep_piping_type']) ? sanitize_text_field($_POST['wpsc_ep_piping_type']) : 'imap';
update_option('wpsc_ep_piping_type',$wpsc_ep_piping_type);

// Client ID
$wpsc_ep_client_id = isset($_POST) && isset($_POST['wpsc_ep_client_id']) ? sanitize_text_field($_POST['wpsc_ep_client_id']) : '';
update_option('wpsc_ep_client_id',$wpsc_ep_client_id);

// Client Secret
$wpsc_ep_client_secret = isset($_POST) && isset($_POST['wpsc_ep_client_secret']) ? sanitize_text_field($_POST['wpsc_ep_client_secret']) : '';
update_option('wpsc_ep_client_secret',$wpsc_ep_client_secret);

// Email Address
$wpsc_ep_email_address = isset($_POST) && isset($_POST['wpsc_ep_email_address']) ? sanitize_text_field($_POST['wpsc_ep_email_address']) : '';
update_option('wpsc_ep_email_address',$wpsc_ep_email_address);

// IMAP email address
$wpsc_ep_imap_email_address = isset($_POST) && isset($_POST['wpsc_ep_imap_email_address']) ? sanitize_text_field($_POST['wpsc_ep_imap_email_address']) : '';
update_option('wpsc_ep_imap_email_address',$wpsc_ep_imap_email_address);

// IMAP email password
$wpsc_ep_imap_email_password = isset($_POST) && isset($_POST['wpsc_ep_imap_email_password']) ? $_POST['wpsc_ep_imap_email_password'] : '';
update_option('wpsc_ep_imap_email_password',$wpsc_ep_imap_email_password);

// IMAP Encription
$wpsc_ep_imap_encryption = isset($_POST) && isset($_POST['wpsc_ep_imap_encryption']) ? sanitize_text_field($_POST['wpsc_ep_imap_encryption']) : '';
update_option('wpsc_ep_imap_encryption',$wpsc_ep_imap_encryption);

// IMAP Incoming mail server
$wpsc_ep_imap_incoming_mail_server = isset($_POST) && isset($_POST['wpsc_ep_imap_incoming_mail_server']) ? sanitize_text_field($_POST['wpsc_ep_imap_incoming_mail_server']) : '';
update_option('wpsc_ep_imap_incoming_mail_server',$wpsc_ep_imap_incoming_mail_server);

// IMAP Port
$wpsc_ep_imap_port = isset($_POST) && isset($_POST['wpsc_ep_imap_port']) ? sanitize_text_field($_POST['wpsc_ep_imap_port']) : '';
update_option('wpsc_ep_imap_port',$wpsc_ep_imap_port);

$response = array(
  'url' => '',
  'messege' => __('Setting saved!','wpsc-ep'),
);

if ( $wpsc_ep_piping_type =='imap' && $wpsc_ep_imap_email_address && $wpsc_ep_imap_email_password && $wpsc_ep_imap_incoming_mail_server && $wpsc_ep_imap_port ){
  $url = admin_url('admin.php').'?state=wpsc_ep_imap_connect';
  $response['url'] = $url;
}

if ( $wpsc_ep_piping_type =='gmail' && $wpsc_ep_client_id && $wpsc_ep_client_secret && $wpsc_ep_email_address ) {
  
  $url  = 'https://accounts.google.com/o/oauth2/v2/auth';
  $url .= '?scope='.urlencode('https://www.googleapis.com/auth/gmail.readonly');
  $url .= '&access_type=offline';
  $url .= '&redirect_uri='.urlencode(admin_url('admin.php'));
  $url .= '&response_type=code';
  $url .= '&state=wpsc_ep';
  $url .= '&client_id='.$wpsc_ep_client_id;
  
  $response['url'] = $url;
  
}

echo json_encode( $response );