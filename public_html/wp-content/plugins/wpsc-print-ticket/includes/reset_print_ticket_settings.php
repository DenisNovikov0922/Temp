<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
}

$wpsc_appearance_print_ticket = array (
	
	'wpsc_print_ticket_btn_bg_color'              => '#FF5733',
	'wpsc_print_ticket_btn_text_color'            => '#000000',
);

update_option('wpsc_appearance_print_ticket',$wpsc_appearance_print_ticket);

do_action('wpsc_reset_default_print_ticket');

echo '{ "sucess_status":"1","messege":"'.__('Settings saved.','supportcandy').'" }';