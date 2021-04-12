<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


global $wpscfunction,$current_user,$wpdb;

$wpsc_dashboard_report_filters = get_option('wpsc_dashboard_report_filters ' );

if($wpsc_dashboard_report_filters == 'last7days'){
  
  include_once( WPSC_SF_ABSPATH . 'includes/rating_pie_chart/report_dash_last_7.php' );
  
}elseif ($wpsc_dashboard_report_filters == 'last30days') {
  
  include_once( WPSC_SF_ABSPATH . 'includes/rating_pie_chart/report_dash_last_30.php' );
  
}elseif ($wpsc_dashboard_report_filters == 'lastmonth') {

include_once( WPSC_SF_ABSPATH . 'includes/rating_pie_chart/report_dash_last_month.php' );

}elseif ($wpsc_dashboard_report_filters == 'lastquarter') {
  
  include_once( WPSC_SF_ABSPATH . 'includes/rating_pie_chart/report_dash_last_quarter.php' );
}

?>


<?php
  if(array_keys(array_filter($gratings_data_count))){
 ?>
 <div  class="col-md-4" style="margin-top:10px">
    <div class="wpsc_report_dash_wid wpsc_pie_chart_widgets">
      <canvas id="ratings" width="300px" height="300px" ></canvas>
   </div>
 </div>
  <script>
  //ticket ratings pie chart js
    var config_ratings = {
      type: 'pie',
      data: {
        datasets: [{
          data: [<?php echo implode(',', $gratings_data_count)?>],
          backgroundColor: [<?php echo implode(',', $gratings_data_color)?>],
          label:''
        }],
        labels: [<?php echo implode(',', $gratings_data_name)?>]
      },
      options: {
        responsive: true,
        title: {
          display: true,
          text: 'Tickets Ratings'
        },
        legend: {
          display: false
        },
        tooltips: {
          enabled: true
        }
      }
    };
    jQuery(document).ready(function() {
      var ratings = document.getElementById('ratings').getContext('2d');
      window.myPie = new Chart(ratings, config_ratings);
    });
  </script>
  <?php
 }
?>
