<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user, $wpscfunction;

$ticket_id = isset($_POST['ticket_id']) ? intval($_POST['ticket_id']) : 0;
if(!$ticket_id) exit;

$auth_code = isset($_POST) && isset($_POST['auth_code']) ? sanitize_text_field($_POST['auth_code']) : '';
if (!$auth_code) {exit;}

$feedback = isset($_POST) && isset($_POST['wpsc_more_feedback']) ? wp_kses_post($_POST['wpsc_more_feedback']) : '';
if (!$feedback) {exit;}

$ticket_auth_code = $wpscfunction->get_ticket_fields($ticket_id,'ticket_auth_code');
if( $ticket_auth_code != $auth_code ) {exit;}

$customer_name  = $wpscfunction->get_ticket_fields($ticket_id,'customer_name');
$customer_email = $wpscfunction->get_ticket_fields($ticket_id,'customer_email');

// Prepare arguments
$args = array(
  'ticket_id'      => $ticket_id,
  'reply_body'     => $feedback,
  'customer_name'  => $customer_name,
  'customer_email' => $customer_email,
  'thread_type'    => 'feedback',
);

$args = apply_filters( 'wpsc_thread_args', $args );
$thread_id = $wpscfunction->submit_ticket_thread($args);

do_action( 'wpsc_sf_submit_feedback', $thread_id, $ticket_id );

_e('Feedback submitted successfully!','wpsc-sf');