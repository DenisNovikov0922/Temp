<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( $field->slug == 'sf_rating' ){
  
  $terms = get_terms([
    'taxonomy'   => 'wpsc_sf_rating',
    'hide_empty' => false,
    'name__like' => $arr['value'],
  ]);
  $results = array();
  foreach($terms as $term){
    $results[]=$term->term_id;
  }
  if($results){
    $arr = array(
      'key'     => $field->slug,
      'value'   => $results,
      'compare' => 'IN'
    );
  }
  
}