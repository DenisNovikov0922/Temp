<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user, $wpscfunction;

$ticket_id   = $args['ticket_id'];
$ticket_data = $wpscfunction->get_ticket($ticket_id);
$old_status_id = $ticket_data['ticket_status'];
$reply_body    = $args['ticket_description'];
$reply_body 	 = preg_replace('/(<(script|style)\b[^>]*>).*?(<\/\2>)/s', "", $reply_body);
$user      = get_user_by('email',$args['customer_email']);

if($user){
	$signature = get_user_meta($user->ID,'wpsc_agent_signature',true);
	if($signature){
		$signature='<br />' . stripcslashes(htmlspecialchars_decode($signature, ENT_QUOTES));
		$reply_body.= $signature;
	}
}

$customer_name = isset($args['customer_name']) ? $args['customer_name'] : '';
if($user){
	$customer_name = $user->display_name;
}

$ticket_raised_by = $wpscfunction->get_ticket_fields($ticket_id,'customer_email');
$user_seen = 'null';
if($args['customer_email'] == $ticket_raised_by){
	$user_seen = date("Y-m-d H:i:s");
}

$cc_mails = $wpscfunction->get_ticket_meta($ticket_id,'extra_ticket_users');
$cc_mail = isset($args['cc_mail']) ? $wpscfunction->sanitize_array($args['cc_mail']) : array();
$wpsc_add_additional_recepients = get_option('wpsc_add_additional_recepients');

if ($cc_mail && $wpsc_add_additional_recepients) {
	foreach ($cc_mail as $ccmail) {
		if (!(in_array($ccmail,$cc_mails))) {
			$wpscfunction->add_ticket_meta($ticket_id,'extra_ticket_users',$ccmail);
		}
	}
}

$reply_args = array(
  'ticket_id'          => $args['ticket_id'],
  'customer_name'      => $customer_name,
  'customer_email'     => $args['customer_email'],
  'thread_type'        => 'reply',
  'reply_body'         => $wpscfunction->replace_macro($reply_body,$ticket_id),
  'attachments'        => $args['desc_attachment'],
	'reply_source'       => $args['reply_source'],
	'user_seen'  				 => $user_seen
);

$reply_attachment = isset($args['desc_attachment']) ? $args['desc_attachment'] : array();
$attachments = array();
foreach ($reply_attachment as $key => $value) {
	$attachment_id = intval($value);
	$attachments[] = $attachment_id;
	update_term_meta ($attachment_id, 'active', '1');
}

$thread_id=$wpscfunction->submit_ticket_thread($reply_args);

do_action( 'wpsc_after_submit_reply', $thread_id, $ticket_id );
