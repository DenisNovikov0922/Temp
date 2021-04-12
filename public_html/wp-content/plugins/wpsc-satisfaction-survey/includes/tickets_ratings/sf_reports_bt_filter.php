<?php 
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

global $wpscfunction, $post, $current_user;

$date_filter = isset($_POST['date_filter']) ? sanitize_text_field($_POST['date_filter']) : '';
update_user_meta($current_user->ID, 'wpsc_report_filter', $date_filter);

if($date_filter == 'last7days'){

  include_once( WPSC_SF_ABSPATH . 'includes/tickets_ratings/report_rs_last_7.php' );

}else if($date_filter == 'last30days'){
  
  include_once( WPSC_SF_ABSPATH . 'includes/tickets_ratings/report_rs_last_30.php' );

}else if($date_filter == 'lastmonth'){
  
  include_once( WPSC_SF_ABSPATH . 'includes/tickets_ratings/report_rs_last_month.php' );
  
}else if($date_filter == 'lastquarter'){
  
  include_once( WPSC_SF_ABSPATH . 'includes/tickets_ratings/report_rs_last_quarter.php' );
  
}else if($date_filter == 'thisyear') {
  
  include_once( WPSC_SF_ABSPATH . 'includes/tickets_ratings/report_rs_this_year.php' );
  
}else if($date_filter == 'customdate') {
  
  include_once( WPSC_SF_ABSPATH . 'includes/tickets_ratings/report_rs_custom_date.php' );
  
}   
?>
<?php
  if(array_keys(array_filter($gratings_data_count))){
 ?>
 <div class="col-sm-12">
   <canvas id="ratings"></canvas>
 </div>
  <script>
  //ticket ratings pie chart js
    var config_ratings = {
      type: 'horizontalBar',
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
          text: ''
        },
        legend: {
          display: false
        },
        tooltips: {
          enabled: true
        },
        scales: {
          xAxes: [{
              ticks: {
                  min:0
              }
          }]
        }
      }
    };
    jQuery(document).ready(function() {
      var ratings = document.getElementById('ratings').getContext('2d');
      window.myHorizontalBar = new Chart(ratings, config_ratings);
    });
  </script>
  <?php
 }else{
  ?>
  <div style="padding:20px; text-align:center;font-size: 20px; " > <?php _e('No data found!', 'wpsc-sf')?> </div>
  <?php
 }
?>
