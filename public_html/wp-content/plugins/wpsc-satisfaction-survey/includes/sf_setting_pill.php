<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<li id="wpsc_settings_sf" role="presentation"><a href="javascript:wpsc_get_sf_settings();"><?php _e('Satisfaction Survey','wpsc-sf');?></a></li>

<script>
  function wpsc_get_sf_settings(){
    jQuery('.wpsc_setting_pills li').removeClass('active');
    jQuery('#wpsc_settings_sf').addClass('active');
    jQuery('.wpsc_setting_col2').html(wpsc_admin.loading_html);
    var data = {
      action: 'wpsc_get_sf_settings',
    };
    jQuery.post(wpsc_admin.ajax_url, data, function(response) {
      jQuery('.wpsc_setting_col2').html(response);
    });
  }
</script>