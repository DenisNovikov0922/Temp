<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
}

$age = isset($_POST['wpsc_sf_age']) ? intval($_POST['wpsc_sf_age']) : 0;
update_option('wpsc_sf_age',$age);

$age_unit = isset($_POST['wpsc_sf_age_unit']) ? sanitize_text_field($_POST['wpsc_sf_age_unit']) : 'h';
update_option('wpsc_sf_age_unit',$age_unit);

$subject = isset($_POST['wpsc_sf_subject']) ? sanitize_text_field($_POST['wpsc_sf_subject']) : '';
update_option('wpsc_sf_subject',$subject);

$body = isset($_POST['wpsc_sf_email_body']) ? wp_kses_post($_POST['wpsc_sf_email_body']) : '';
update_option('wpsc_sf_email_body',$body);

echo '{ "sucess_status":"1","messege":"'.__('Settings saved.','wpsc-sf').'" }';
