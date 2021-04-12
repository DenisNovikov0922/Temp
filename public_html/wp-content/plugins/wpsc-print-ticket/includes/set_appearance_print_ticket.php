<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user,$wpscfunction;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
}

$wpsc_appearance_print_ticket = isset($_POST) && isset($_POST['print_ticket_appearance']) ? $wpscfunction->sanitize_array($_POST['print_ticket_appearance']) : array();

update_option('wpsc_appearance_print_ticket',$wpsc_appearance_print_ticket);

do_action('wpsc_set_appearance_print_ticket');

echo '{ "sucess_status":"1","messege":"'.__('Settings saved.','supportcandy').'" }';
