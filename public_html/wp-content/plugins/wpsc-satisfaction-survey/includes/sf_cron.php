<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

global $wpscfunction,$wpdb;

$wpsc_sf_age = get_option('wpsc_sf_age','0');
if(!$wpsc_sf_age){
  return;
}

$from_name     = get_option('wpsc_en_from_name','');
$from_email    = get_option('wpsc_en_from_email','');
$reply_to      = get_option('wpsc_en_reply_to','');

$check_flag = false;
$last_check = get_option('wpsc_sf_cron_last_check');
if($last_check){
  $now = time();
  $ago = strtotime($last_check);
  $diff = $now - $ago;
  $diff_minutes = round( $diff / 60 );
	if( $diff_minutes >= 60 ){
		$check_flag = true;
	}
}

if( !(!$last_check || $check_flag) ){
	return;
}

$wpsc_sf_age_unit = get_option('wpsc_sf_age_unit');

$emails = get_terms([
	'taxonomy'   => 'wpsc_sf_email',
	'hide_empty' => false,
]);

foreach ($emails as $email) {

  $ticket_id = get_term_meta($email->term_id,'ticket_id',true);
  $time    = get_term_meta($email->term_id,'time',true);
  $subject = get_option('wpsc_sf_subject');
  $body = stripslashes(get_option('wpsc_sf_email_body'));

  $now = time();
  $ago = strtotime($time);
	$diff = $now - $ago;

  $check_flag = false;

  $diff_hours = round( $diff / (60 * 60) );
  if( $wpsc_sf_age_unit == 'h' && $diff_hours >= $wpsc_sf_age ){
    $check_flag = true;
  }

  $diff_days = round( $diff / (60 * 60 * 24) );
  if( $wpsc_sf_age_unit == 'd' && $diff_days >= $wpsc_sf_age ){
    $check_flag = true;
  }

	if( !$check_flag ){
		continue;
	}

  $subject = $wpscfunction->replace_macro($subject,$ticket_id);
  $body = $wpscfunction->replace_macro($body,$ticket_id);

  $customer_email = $wpscfunction->get_ticket_fields($ticket_id,'customer_email');
  
  $wpsc_email_sending_method = get_option('wpsc_email_sending_method');     

  $args  = array(
    'ticket_id'     => $ticket_id,
    'from_email'    => $from_email,
    'reply_to'      => $reply_to,
    'email_subject' => $subject,
    'email_body'    => $body,
    'to_email'      => $customer_email,
    'bcc_email'     => '',
    'date_created'  => date("Y-m-d H:i:s"),
    'mail_status'   => 0,
    'email_type'    => 'sf',
  ); 

  if($wpsc_email_sending_method){
      
    $wpdb->insert( $wpdb->prefix . 'wpsc_email_notification',$args);
  }else{
    
    $headers  = "From: {$from_name} <{$from_email}>\r\n";
    $headers .= "Reply-To: {$reply_to}\r\n";
    foreach ($email_addresses as $email_address) {
      $headers .= "BCC: {$email_address}\r\n";
    }

    $headers .= "Content-Type: text/html; charset=utf-8\r\n";

     wp_mail($customer_email, $subject, $body, $headers);

  }
    
  do_action('wpsc_after_sf_cron_mail',$ticket_id,$args);
  
  wp_delete_term($email->term_id, 'wpsc_sf_email');

}

update_option('wpsc_sf_cron_last_check',date("Y-m-d H:i:s"));
