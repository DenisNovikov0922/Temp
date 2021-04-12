<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $wpdb,$wpscfunction;

if($value == 'sf_rating'){
  $custom_field_val = $wpscfunction->get_ticket_meta($ticket_id,$value,true);
	if($custom_field_val){
		$rating_obj = get_term_by('id',$custom_field_val,'wpsc_sf_rating');
	  $export_colomn_value[]=$rating_obj->name;
	}
  else {
		$arr=__('None','wpsc-sf');
		$export_colomn_value[]=$arr;
  }
}

