<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
}

$wpsc_appearance_print_ticket = get_option('wpsc_appearance_print_ticket');
?>
<form id="wpsc_frm_appearance_print_ticket" method="post" action="javascript:wpsc_set_appearance_print_ticket();">
   
  <div class="form-group">
		<label for="wpsc_print_ticket_color"><?php _e('Print Ticket Button (Thank you page)','supportcandy');?></label></br>
		<div class="row">
			 <div class="col-sm-6" id="wpsc_print_ticket_btn_bg_color">
				 <p class="help-block"><?php _e('Background color','supportcandy');?></p>
				 <input id="wpsc_print_ticket_btn_bg_color" class="wpsc_color_picker" name="print_ticket_appearance[wpsc_print_ticket_btn_bg_color]" value="<?php echo $wpsc_appearance_print_ticket['wpsc_print_ticket_btn_bg_color']?>" />
			 </div>
			 
			 <div class="col-sm-6" id="wpsc_print_ticket_btn_text_color">
				 <p class="help-block"><?php _e('Color','supportcandy');?></p>
				 <input id="wpsc_print_ticket_btn_text_color" class="wpsc_color_picker" name="print_ticket_appearance[wpsc_print_ticket_btn_text_color]" value="<?php echo $wpsc_appearance_print_ticket['wpsc_print_ticket_btn_text_color']?>" />
			 </div>
	 </div>
  </div> 

  <button type="submit" id="wpsc_submit_app_gen_btn" class="btn btn-success" style="margin-bottom:10px;"><?php _e('Save Changes','supportcandy');?></button>
  <button type="button" id="wpsc_reset_defult_app_pt_btn" onclick="wpsc_reset_default_print_ticket()" class="btn btn-sm btn-default" style="margin-bottom:10px; font-size:15px;"><?php _e('Reset Default','supportcandy');?></button>
  <img class="wpsc_submit_wait" style="display:none;" src="<?php echo WPSC_PLUGIN_URL.'asset/images/ajax-loader@2x.gif';?>">
  <input type="hidden" name="action" value="wpsc_set_appearance_print_ticket" />
  
</form>

<script>
  jQuery(document).ready(function(){
      jQuery('.wpsc_color_picker').wpColorPicker();
  });
  
  function wpsc_set_appearance_print_ticket(){
    jQuery('.wpsc_submit_wait').show();
    var dataform = new FormData(jQuery('#wpsc_frm_appearance_print_ticket')[0]);
    jQuery.ajax({
      url: wpsc_admin.ajax_url,
      type: 'POST',
      data: dataform,
      processData: false,
      contentType: false
    })
    .done(function (response_str) {
      var response = JSON.parse(response_str);
      jQuery('.wpsc_submit_wait').hide();
      if (response.sucess_status=='1') {
        jQuery('#wpsc_alert_success .wpsc_alert_text').text(response.messege);
      }
      jQuery('#wpsc_alert_success').slideDown('fast',function(){});
      setTimeout(function(){ jQuery('#wpsc_alert_success').slideUp('fast',function(){}); }, 3000);
    });
  }
  
  /*
   * Appearance Print Ticket reset Settings 
   */
  function wpsc_reset_default_print_ticket() {
    
    var data = {
      action: 'wpsc_reset_print_ticket_settings',    
    };

    jQuery.post(wpsc_admin.ajax_url, data, function(response_str) {
      var response = JSON.parse(response_str);
      if (response.sucess_status=='1') {
        jQuery('#wpsc_alert_success .wpsc_alert_text').text(response.messege);
      }
      jQuery('#wpsc_alert_success').slideDown('fast',function(){});
      setTimeout(function(){ jQuery('#wpsc_alert_success').slideUp('fast',function(){}); }, 3000);
      wpsc_get_appearance_pt();
    });
  }
</script>

