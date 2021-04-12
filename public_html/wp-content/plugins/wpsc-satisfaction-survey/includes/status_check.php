<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$wpsc_close_ticket_status = get_option('wpsc_close_ticket_status');

if($status_id != $wpsc_close_ticket_status){
  return;
}

$term = wp_insert_term( 'sf_'.uniqid(), 'wpsc_sf_email' );
if (!is_wp_error($term) && isset($term['term_id'])) {
  add_term_meta ($term['term_id'], 'ticket_id', $ticket_id);
  add_term_meta ($term['term_id'], 'time', date("Y-m-d H:i:s"));
}
