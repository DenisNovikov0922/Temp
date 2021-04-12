<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user;
$wpsc_allow_to_create_ticket = get_option('wpsc_allow_to_create_ticket');

if ( $current_user->ID || in_array('guest', $wpsc_allow_to_create_ticket)) {
  include WPSC_ABSPATH . 'includes/admin/tickets/create_ticket/load_create_ticket.php';
	
} else {
  include WPSC_ABSPATH . 'includes/admin/tickets/sign_in/sign_in.php';
}
