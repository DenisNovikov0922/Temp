<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
}

$wpsc_print_th_btn_setting   = isset($_POST) && isset($_POST['wpsc_print_th_btn_setting']) ? intval($_POST['wpsc_print_th_btn_setting']) : '0';
update_option('wpsc_print_th_btn_setting',$wpsc_print_th_btn_setting);

$wpsc_print_btn_lbl = isset($_POST) && isset($_POST['wpsc_print_btn_lbl']) ? sanitize_text_field($_POST['wpsc_print_btn_lbl']) : '';
update_option('wpsc_print_btn_lbl',$wpsc_print_btn_lbl);

$wpsc_print_cust_btn_setting   = isset($_POST) && isset($_POST['wpsc_print_cust_btn_setting']) ? intval($_POST['wpsc_print_cust_btn_setting']) : '0';
update_option('wpsc_print_cust_btn_setting',$wpsc_print_cust_btn_setting);

$wpsc_print_page_header_height = isset($_POST) && isset($_POST['wpsc_print_page_header_height']) ? sanitize_text_field($_POST['wpsc_print_page_header_height']) : '';
update_option('wpsc_print_page_header_height',$wpsc_print_page_header_height);

$wpsc_print_page_footer_height = isset($_POST) && isset($_POST['wpsc_print_page_footer_height']) ? sanitize_text_field($_POST['wpsc_print_page_footer_height']) : '';
update_option('wpsc_print_page_footer_height',$wpsc_print_page_footer_height);

$wpsc_print_ticket_header = isset($_POST) && isset($_POST['wpsc_print_ticket_header']) ? wp_kses_post(htmlentities(stripslashes($_POST['wpsc_print_ticket_header']))) : '';
update_option('wpsc_print_ticket_header',$wpsc_print_ticket_header);

$wpsc_print_ticket_body = isset($_POST) && isset($_POST['wpsc_print_ticket_body']) ? wp_kses_post(htmlentities(stripslashes($_POST['wpsc_print_ticket_body']))) : '';
update_option('wpsc_print_ticket_body', $wpsc_print_ticket_body);

$wpsc_print_ticket_footer = isset($_POST) && isset($_POST['wpsc_print_ticket_footer']) ? wp_kses_post(htmlentities(stripslashes($_POST['wpsc_print_ticket_footer']))) : '';
update_option('wpsc_print_ticket_footer', $wpsc_print_ticket_footer);

do_action('wpsc_set_print_ticket_settings');

echo '{ "sucess_status":"1","messege":"'.__('Settings saved.','wpsc-pt').'" }';