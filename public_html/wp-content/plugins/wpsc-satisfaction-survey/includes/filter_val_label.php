<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if($field_slug=='sf_rating'){
  
  $rating_term = get_term_by('id',$val,'wpsc_sf_rating');
	$val = $rating_term->name;
  
}
