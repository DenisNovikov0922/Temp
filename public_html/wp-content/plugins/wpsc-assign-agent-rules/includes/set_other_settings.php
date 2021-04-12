<?php 
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
global $current_user,$wpscfunction;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
    exit;
}

$wpsc_set_assign_auto_responder = isset($_POST) && isset($_POST['wpsc_set_assign_auto_responder']) ? sanitize_text_field($_POST['wpsc_set_assign_auto_responder']) : '0';
update_option('wpsc_assign_auto_responder',$wpsc_set_assign_auto_responder);

$response = array(
    'messege' => __('Setting saved!','wpsc-caa')
);

echo json_encode($response);