<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
 
global $wpscfunction,$current_user,$wpdb;

$usermeta = get_user_meta($current_user->ID, 'wpsc_report_filter', true);

?>

<form id="frm_sf_reports" class="" action="" method="post">
	<div id="wpsc_report_date_filter" style="border:1px solid #ccc;padding:5px;border-radius:2px;">
    <div class="row">
      <div class="col-md-3">
  	       <div class="form-group">
  	          <label for="wpsc_sf_month_filters"><strong><?php _e('Report Duration','wpsc-sf');?></strong></label></br>
  	          
  	          <select id="wpsc_sf_month_filters" class="form-control" name="wpsc_sf_month_filters" >
                 <?php  
                  $selected = $usermeta == 'last7days' ? 'selected="selected"' : '';
                  echo '<option ' . $selected . ' value="last7days">' . __('Last 7 Days', 'supportcandy') . '</option>';
                  
                  $selected = $usermeta == 'last30days' ? 'selected="selected"' : '';
                  echo '<option ' . $selected . ' value="last30days">' . __('Last 30 Days', 'supportcandy') . '</option>';
                  
                  $selected = $usermeta == 'lastmonth' ? 'selected="selected"' : '';
                  echo '<option ' . $selected . ' value="lastmonth">' . __('Last Month', 'supportcandy') . '</option>';

                  $selected = $usermeta == 'lastquarter' ? 'selected="selected"' : '';
                  echo '<option ' . $selected . ' value="lastquarter">' . __('Last Quarter', 'supportcandy') . '</option>';
                  
                  $selected = $usermeta == 'thisyear' ? 'selected="selected"' : '';
                  echo '<option ' . $selected . ' value="thisyear">' . __('This Year', 'supportcandy') . '</option>';

                  $selected = $usermeta == 'customdate' ? 'selected="selected"' : '';
                  echo '<option ' . $selected . ' value="customdate">' . __('Custom Date', 'supportcandy') . '</option>';
              ?>
  	          </select>
  	       </div>
  	 	 </div>
  	   <div class="col-md-3 wpsc_sf_date_filter" style="display:none;">
  	     <div class="form-group">
  	       <label><strong><?php _e('From Date','wpsc-sf');?></strong></label><br>
  	       <input type="text" class="form-control" style="padding:0px;" autocomplete="false" value="" readonly="true" id="wpsc_sf_custom_date_start" name="wpsc_sf_custom_date_start"/> 
  	     </div>
  	   </div>
  	   <div class="col-md-3 wpsc_sf_date_filter" style="display:none;">
  	     <div class="form-group">
  	       <label><strong><?php _e('To Date','wpsc-sf');?></strong></label><br>
  	       <input type="text" class="form-control" style="padding:0px;" autocomplete="false" readonly="true" value="" id="wpsc_sf_custom_date_end" name="wpsc_sf_custom_date_end"/>
  	     </div>
  	   </div>
  	   <div class="col-md-3">
          <button onclick="wpsc_sf_report_graph();return false;" style="margin-top:23px;" class="btn btn-sm btn-default"><?php echo _e('Apply','wpsc-sf');?></button>
          <button id="printChart" onclick="wpsc_print_sf_reports();return false;" style="margin-top:23px;" class="btn btn-sm btn-default"><?php echo _e('Print','wpsc-rp');?></button>
        </div>  
    </div>
  </div>

	<input type="hidden" name="action" value="sf_reports_bt_filter">
  

</form>

<div id="wpsc_sf_graph"></div>
	
<script type="text/javascript">
  jQuery(document).ready(function(){
    
    wpsc_sf_report_graph();
    
    jQuery( "#wpsc_sf_custom_date_start" ).datepicker({
        dateFormat : 'yy-mm-dd',
        showAnim : 'slideDown',
        changeMonth: true,
        changeYear: true,
        yearRange: "-50:+50",
    });
    
    jQuery("#wpsc_sf_custom_date_end").datepicker({
          dateFormat: 'yy-mm-dd',
          showAnim : 'slideDown',
          changeMonth: true,
          changeYear: true,
          yearRange: "-50:+50"
    });
      
    jQuery('#wpsc_sf_month_filters').change(function (){
      var seloption=jQuery(this).val();
      if(seloption == 'customdate'){
        jQuery(".wpsc_sf_date_filter").show();
      }else{
        jQuery(".wpsc_sf_date_filter").hide();
      }
    })

    <?php 
    if($usermeta == 'customdate'){
      ?>
         jQuery(".wpsc_sf_date_filter").show();
      <?php
    }else{
      ?>
        jQuery(".wpsc_sf_date_filter").hide();
      <?php
    }
   ?>
    
  });
  
  function wpsc_print_sf_reports(){
    var canvas   = document.getElementById("ratings");
    var myWindow = window.open('','','width=500,height=400');
    var duration = document.getElementById("wpsc_sf_month_filters").value;
    
    var start_date = document.getElementById("start_date").value;
    var end_date   = document.getElementById("end_date").value;
    
    var message =  'Report duration : ' + start_date + '  to  ' + end_date;
    setTimeout(function(){ myWindow.document.write("<div style='text-align:center; color:#808080;'>" + "<h4> Ticket Rating </h4>" +"</div>" ); }, 1000);  
    setTimeout(function(){ myWindow.document.write("<div style='text-align:center; color:#808080;'> <small>" + message +"</small>" + "\n\n" + "</div>" ); }, 1000);  
    setTimeout(function(){ myWindow.document.write("<br><img src='" + canvas.toDataURL() + "'/>"); }, 1000);  
    setTimeout(function(){ myWindow.print(); }, 1000);
    setTimeout(function(){ myWindow.close(); }, 1000);
  }
</script>

