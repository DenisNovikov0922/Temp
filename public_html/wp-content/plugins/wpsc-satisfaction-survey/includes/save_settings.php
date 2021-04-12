<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
}

$page_id = isset($_POST['wpsc_sf_page']) ? intval($_POST['wpsc_sf_page']) : 0;
update_option('wpsc_sf_page',$page_id);

$thankyou_text = isset($_POST['wpsc_sf_thankyou_text']) ? sanitize_text_field($_POST['wpsc_sf_thankyou_text']) : '';
update_option('wpsc_sf_thankyou_text',$thankyou_text);

echo '{ "sucess_status":"1","messege":"'.__('Settings saved.','wpsc').'" }';
