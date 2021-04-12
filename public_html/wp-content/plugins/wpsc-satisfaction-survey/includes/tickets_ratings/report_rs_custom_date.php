<?php 
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

global $post,$wpscfunc,$wpdb,$wpscfunction;

if(isset($_POST['custom_date_start']) && $_POST['custom_date_start']!=''){
    $startdate=$_POST['custom_date_start'];
}else{
     $startdate=date('Y-m-d',strtotime( "monday this week" ));
}
if(isset($_POST['custom_date_end']) && $_POST['custom_date_end']!=''){
    $enddate=$_POST['custom_date_end'];
}else{
    $enddate=date('Y-m-d', strtotime($startdate. ' +6 days'));
}
$tickets = $wpdb->get_results("SELECT id FROM {$wpdb->prefix}wpsc_ticket WHERE DATE(date_created) BETWEEN '".$startdate."' AND '".$enddate."'");

$ratings_array=array();
if($tickets){
  foreach ($tickets as $ticket) {
    $ratings_array[] = $wpscfunction->get_ticket_meta($ticket->id,'sf_rating',true);
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

?>

<input type="hidden" id="start_date" value= "<?php echo $startdate ?>"/>
<input type="hidden" id="end_date" value= "<?php echo $enddate ?>"/>
