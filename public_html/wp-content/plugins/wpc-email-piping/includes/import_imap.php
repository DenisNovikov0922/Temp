<?php
use EmailReplyParser\Parser\EmailParser;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$wpsc_ep_imap_email_address  = get_option('wpsc_ep_imap_email_address');

if( email_exists( $wpsc_ep_imap_email_address ) ){
  return;
}
global $wpscfunction, $wpscepfunction;
include_once( WPSC_EP_ABSPATH . 'includes/class-process-imap-emails.php' );

$wpsc_ep_imap_email_address        = get_option('wpsc_ep_imap_email_address');
$wpsc_ep_imap_email_password       = get_option('wpsc_ep_imap_email_password');
$wpsc_ep_imap_encryption           = get_option('wpsc_ep_imap_encryption');
$wpsc_ep_imap_incoming_mail_server = get_option('wpsc_ep_imap_incoming_mail_server');
$wpsc_ep_imap_port                 = get_option('wpsc_ep_imap_port');

$wpsc_ep_imap_encryption = $wpsc_ep_imap_encryption=='none' ? 'novalidate-cert':'imap/ssl/novalidate-cert';
$conn ='';
if(!empty($wpsc_ep_imap_incoming_mail_server) && !empty($wpsc_ep_imap_port) && !empty($wpsc_ep_imap_encryption) && !empty($wpsc_ep_imap_email_address) && !empty($wpsc_ep_imap_email_password )){
  $conn = @imap_open('{'.$wpsc_ep_imap_incoming_mail_server.':'.$wpsc_ep_imap_port.'/'.$wpsc_ep_imap_encryption.'}INBOX', $wpsc_ep_imap_email_address, $wpsc_ep_imap_email_password);
}

if ($conn) {
  
  if($debug_mode){
    echo '==> IMAP Connection successful!<br>';
  }
  
  $last_uid = get_option('wpsc_ep_imap_uid','0');

  $history = imap_fetch_overview($conn, ($last_uid+1).":*", FT_UID);
  $uids    = array();
  if ($history) {
    foreach ($history as $overview) {
      $uids[] = $overview->uid;
    }
  }
  
  $uids = isset( $uids[0] ) && $uids[0] != $last_uid ? $uids : array();
  $counter = 1;
  foreach ($uids as $uid) {
		if($counter > 5){
			break;
    }	
    update_option( 'wpsc_ep_imap_uid', $uid );
    $counter++;

    $mail = new WPSC_EP_Imap_Mail_Process($conn,$uid);		
  
    $user = get_user_by( 'email', $mail->from_email);
    $user_id=0;
    if ( ! empty( $user ) ) {
      $user_id=$user->ID;
    }
		
    $args = array(
      'customer_name'      => $mail->from_name,
      'customer_email'     => isset($mail->reply_to_email) ? $mail->reply_to_email : $mail->from_email,
      'to_email'           => $mail->to_email,
      'ticket_subject'     => $mail->subject,
      'desc_attachment'    => $mail->attachment_ids,
      'user_id'            => $user_id,
      'ticket_id'          => $mail->ticket_id,
			'reply_source'       => 'imap'
    );
    if (isset($mail->cc_mail)) {
			$args['cc_mail'] = $mail->cc_mail;
    }

    //if to address is not piping address and user added piping address in cc
    if( get_option('wpsc_add_additional_recepients')=="1" && !$wpscepfunction->check_to_email_is_piping_email( $mail->to_email ) ){
      if( isset($args['cc_mail']) ){
        $args['cc_mail'][] = $mail->to_email;
      }else{
        $args['cc_mail'] = array($mail->to_email);
      }
    }
    
    if(!$args['ticket_id']){
      $args['is_reply']=0;
    }else{
      $args['is_reply']=1;
    }
    
    if($debug_mode){
      echo '==> Parsing successful. Below is import args:<br>';
      echo '<pre>';
      print_r($args);
      echo '<pre>';
    }
    
    if(!$mail->is_allowed($args)) {
      
			if ($debug_mode){
        echo '==> Importing this messege not allowed<br>';
      }
			
    } else {
			
			$accept_mail_type = get_option('wpsc_ep_email_type');
			
			$ticket_description = '';
			
			if( !$ticket_description && $accept_mail_type == 'html' && $mail->html_body ){
				$ticket_description = $mail->html_body;
			} 
			
			if( !$ticket_description && $accept_mail_type == 'html' && !$mail->html_body && $mail->text_body ){
				if($args['is_reply']==0){
					$ticket_description = nl2br($mail->text_body);
				} else {
					$email = (new EmailParser())->parse($mail->text_body);
					$ticket_description = nl2br($email->getVisibleText());
				}
			}
			
			if( !$ticket_description && $accept_mail_type == 'text' && $mail->text_body ){
				if($args['is_reply']==0){
					$ticket_description = nl2br($mail->text_body);
				} else {
					$email = (new EmailParser())->parse($mail->text_body);
					$ticket_description = nl2br($email->getVisibleText());
				}
			} 
			
			if( !$ticket_description && $accept_mail_type == 'text' && !$mail->text_body && $mail->html_body ){
				$ticket_description = $mail->html_body;
			}
			
			if( !$ticket_description ){
				$ticket_description = 'No email body found!';
			}
			
      $args['ticket_description'] = $ticket_description;
      
      $args = apply_filters('wpsc_ep_before_pipe', $args);
			
	    if($args['is_reply']==0) {
	      
				$ticket_id = $wpscepfunction->create_ticket_email_piping($args);
				
	    } else {
	      
				$thread_id = $wpscepfunction->create_ticket_reply($args);  
				
	    }
			
		}

  }
  
} else {
  
  if($debug_mode){
    echo '==> IMAP Connection Failed! Below is error messege:<br>';
		echo imap_last_error().'<br>';
		echo 'Aborting Email Piping!<br>';
  }
  
}

update_option('wpsc_ep_last_check', date("Y-m-d H:i:s"));
