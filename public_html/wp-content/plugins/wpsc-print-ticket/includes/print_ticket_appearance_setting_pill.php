<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<li id="wpsc_appearance_print_ticket" role="presentation"><a href="javascript:wpsc_get_appearance_pt();"><?php _e('Print Ticket','wpsc-pt');?></a></li>

<script>
function wpsc_get_appearance_pt() {
  jQuery('.wpsc_setting_pills li').removeClass('active');
  jQuery('#wpsc_appearance_print_ticket').addClass('active');
  jQuery('.wpsc_setting_col2').html(wpsc_admin.loading_html);
  var data = {
    action : 'wpsc_get_pt_appearance_settings'
  }
  jQuery.post(wpsc_admin.ajax_url, data, function(response) {
    jQuery('.wpsc_setting_col2').html(response);
  });  
}
</script>