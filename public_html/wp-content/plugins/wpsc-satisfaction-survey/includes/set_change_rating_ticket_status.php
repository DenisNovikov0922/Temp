<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $wpscfunction;

$wpsc_ticket_rating_status = $wpscfunction->get_ticket_meta($ticket_id,'wpsc_ticket_rating_status',true);
$wpsc_close_ticket_status = get_option('wpsc_close_ticket_status');
$flag = 0;
$wpsc_ticket_rating_status = $wpscfunction->get_ticket_meta($ticket_id,'wpsc_ticket_rating_status',true);

if(isset($wpsc_ticket_rating_status)){
  $wpscfunction->delete_ticket_meta($ticket_id,'wpsc_ticket_rating_status');
}

$wpscfunction->add_ticket_meta($ticket_id,'wpsc_ticket_rating_status',$flag);	

 ?>