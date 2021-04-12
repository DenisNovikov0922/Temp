<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

global $current_user,$wpscfunction;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
}

$block_emails = isset($_POST) && isset($_POST['wpsc_block_email']) ? explode("\n",  $_POST['wpsc_block_email']) : array();
$block_emails = $wpscfunction->sanitize_array($block_emails);
update_option('wpsc_ep_block_emails',$block_emails);

$block_subject = isset($_POST) && isset($_POST['wpsc_block_subject']) ? sanitize_textarea_field($_POST['wpsc_block_subject']) : '';
update_option('wpsc_ep_block_subject',$block_subject);

$allowed_user = isset($_POST) && isset($_POST['wpsc_allow_user']) ? sanitize_text_field($_POST['wpsc_allow_user']) : '';
update_option('wpsc_ep_allowed_user',$allowed_user);

$cron_execution_time = isset($_POST) && isset($_POST['wpsc_ep_cron_execution_time']) ? sanitize_text_field($_POST['wpsc_ep_cron_execution_time']) : '';
update_option('wpsc_ep_cron_execution_time',$cron_execution_time);

$wpsc_ep_debug_mode = isset($_POST) && isset($_POST['wpsc_ep_debug_mode']) ? intval($_POST['wpsc_ep_debug_mode']) : 0;
update_option('wpsc_ep_debug_mode',$wpsc_ep_debug_mode);

$wpsc_ep_email_type = isset($_POST) &&  isset($_POST['wpsc_ep_email_type']) ? sanitize_text_field($_POST['wpsc_ep_email_type']) : '';
update_option('wpsc_ep_email_type',$wpsc_ep_email_type);

$wpsc_ep_from_email = isset($_POST) &&  isset($_POST['wpsc_ep_from_email']) ? sanitize_text_field($_POST['wpsc_ep_from_email']) : '';
update_option('wpsc_ep_from_email',$wpsc_ep_from_email);

$wpsc_ep_accept_emails = isset($_POST) && isset($_POST['wpsc_ep_accept_emails']) ? sanitize_text_field($_POST['wpsc_ep_accept_emails']) : 'all';
update_option('wpsc_ep_accept_emails',$wpsc_ep_accept_emails);

$wpsc_add_additional_recepients = isset($_POST) && isset($_POST['wpsc_add_additional_recepients']) ? sanitize_text_field($_POST['wpsc_add_additional_recepients']) : '0';
update_option('wpsc_add_additional_recepients',$wpsc_add_additional_recepients);

$response = array(
  'messege' => __('Setting saved!','wpsc-ep'),
);

echo json_encode( $response );