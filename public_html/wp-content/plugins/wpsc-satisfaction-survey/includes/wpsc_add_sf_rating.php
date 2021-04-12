<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $wpscfunction;

$ticket_id = isset($_POST['ticket_id']) ? intval($_POST['ticket_id']) : 0;
if(!$ticket_id) return;

$rating_id = isset($_POST['rating_id']) ? intval($_POST['rating_id']) : 0;
if(!$rating_id) return;

$ticket_auth_code = $wpscfunction->get_ticket_fields($ticket_id,'ticket_auth_code');

$sf_rating = $wpscfunction->get_ticket_meta($ticket_id,'sf_rating',true);

if(!$sf_rating){
  
  $wpscfunction->add_ticket_meta($ticket_id,'sf_rating',$rating_id);

} else {
  
  $wpscfunction->update_ticket_meta($ticket_id,'sf_rating',array('meta_value' => $rating_id));
}

$wpsc_ticket_rating_status = $wpscfunction->get_ticket_meta($ticket_id,'wpsc_ticket_rating_status',true);

if(isset($wpsc_ticket_rating_status)){
  
  $wpscfunction->delete_ticket_meta($ticket_id,'wpsc_ticket_rating_status');

}

$wpscfunction->add_ticket_meta($ticket_id,'wpsc_ticket_rating_status',1);

?>