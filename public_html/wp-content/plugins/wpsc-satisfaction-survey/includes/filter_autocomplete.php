<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if($field_slug=='sf_rating'){
  
  $ratings = get_terms([
    'taxonomy'   => 'wpsc_sf_rating',
    'hide_empty' => false,
    'search'     => $term,
  ]);
  foreach($ratings as $rating){
    $output[] = array(
      'label'    => $rating->name,
      'value'    => '',
      'flag_val' => $rating->term_id,
      'slug'     => $field_slug,
    );
  }
  
  if (!$output) {
    $output[] = array(
  		'label' => __('No matching data','wpsc-sf'),
  		'value' => '',
  		'slug'  => '',
  	);
  }
  
}
