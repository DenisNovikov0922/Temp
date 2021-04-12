<?php 
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

global $post,$wpdb,$wpscfunc,$wpscfunction;

$first = date("Y-m-d", strtotime("today"));
$last =   date('Y-m-d', strtotime("today -3 months"));

$tickets = $wpdb->get_results("SELECT id FROM {$wpdb->prefix}wpsc_ticket WHERE DATE(date_created) BETWEEN '".$last."' AND '".$first."'");

$ratings_array = array();

$pie_widgets     = get_option('wpsc_report_dash_widgets',array());

foreach($pie_widgets as $widget_id){
  $term = get_term_by('id',$widget_id,'wpsc_ticket_custom_fields');
  if($tickets){
    foreach ($tickets as $ticket) {
      if($term->slug == 'sf_rating'){
        $ratings_array[] = $wpscfunction->get_ticket_meta($ticket->id,'sf_rating',true);
      }
    }
  }
}

//Ratings bar graph 
$gratings_data_name  = array();
$gratings_data_color = array();
$gratings_data_count = array();

$ratings = get_terms([
	'taxonomy'   => 'wpsc_sf_rating',
	'hide_empty' => false,
	'orderby'    => 'meta_value_num',
	'order'    	 => 'ASC',
	'meta_query' => array('order_clause' => array('key' => 'load_order')),
]);

foreach ($ratings as $rating) {
 if( !empty($ratings_array)){
   $gratings_data_count[] = count(array_keys($ratings_array, $rating->term_id));
   $gratings_data_name[] = "'".$rating->name."'";
   $gratings_data_color[] = "'".get_term_meta( $rating->term_id, 'color', true)."'";
 }  
   
}
