<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $wpscfunction,$wpdb;

if ($field->list_item->slug == 'sf_rating') {
	
	$ticket_rating =	$wpscfunction->get_ticket_meta($field->ticket['id'],'sf_rating',true);	
	$ticket_rating = is_numeric($ticket_rating) ? intval($ticket_rating) : 0;
	if($ticket_rating){
		$rating_term = get_term_by('id',$ticket_rating,'wpsc_sf_rating');
		$color = get_term_meta($ticket_rating,'color',true);
		$replace_data = '<span class="wpsp_admin_label" style="background-color:'.$color.';color:#ffffff;">'.$rating_term->name.'</span>';
  } else {
  	$replace_data = '';
  }
  
  echo $replace_data;
  
}
