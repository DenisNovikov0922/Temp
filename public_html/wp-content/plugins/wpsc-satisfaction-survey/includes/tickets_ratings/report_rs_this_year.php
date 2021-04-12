<?php 
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
global $post,$wpdb,$wpscfunc,$wpscfunction;

$year   = date("Y");
$first = date("Y-m-d", strtotime($year."-01-01"));
$last  = date("Y-m-d", strtotime($year."-12-31"));

$firstdate = date('Y-m-d', strtotime('first day of january this year'));
$lastdate  = date('Y-m-d', strtotime('last day of december this year'));

$tickets = $wpdb->get_results("SELECT id FROM {$wpdb->prefix}wpsc_ticket WHERE DATE(date_created) BETWEEN '".$firstdate."' AND '".$lastdate."'");

if ($tickets) {
  foreach ($tickets as $ticket) {
    $ratings_array[] = $wpscfunction->get_ticket_meta($ticket->id,'sf_rating',true);
  }
}

//Ratings bar graph
$gratings_data_name  = array();
$gratings_data_color = array();
$gratings_data_count = array();

if( !isset($_POST['custom_filter']['sf_rating'])){
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
}

?>

<input type="hidden" id="start_date" value= "<?php echo $firstdate ?>"/>
<input type="hidden" id="end_date" value= "<?php echo $lastdate ?>"/>
