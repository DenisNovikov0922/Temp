<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

global $current_user,$wpscfunction;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
}

$wpsc_ct_warn_email_subject = isset($_POST) && isset($_POST['wpsc_ct_warn_email_subject']) ? sanitize_text_field($_POST['wpsc_ct_warn_email_subject']) : '';
update_option('wpsc_ct_warn_email_subject',$wpsc_ct_warn_email_subject);

$wpsc_ct_warn_email_body = isset($_POST) && isset($_POST['wpsc_ct_warn_email_body']) ? wp_kses_post($_POST['wpsc_ct_warn_email_body']) : '';
update_option('wpsc_ct_warn_email_body',$wpsc_ct_warn_email_body);

$wpsc_close_user_warn_email_subject = isset($_POST) && isset($_POST['wpsc_close_user_warn_email_subject']) ? sanitize_text_field($_POST['wpsc_close_user_warn_email_subject']) : '';
update_option('wpsc_close_user_warn_email_subject',$wpsc_close_user_warn_email_subject);

$wpsc_close_user_warn_email_body = isset($_POST) && isset($_POST['wpsc_close_user_warn_email_body']) ? wp_kses_post($_POST['wpsc_close_user_warn_email_body']) : '';
update_option('wpsc_close_user_warn_email_body',$wpsc_close_user_warn_email_body);

$response = array(
    'sucess_status' => 1,
    'messege' => __('Setting saved!','wpsc-ep'),
);
  
echo json_encode( $response );