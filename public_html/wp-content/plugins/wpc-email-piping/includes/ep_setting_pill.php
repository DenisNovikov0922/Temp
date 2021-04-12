<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<li id="wpsc_settings_ep" role="presentation"><a href="javascript:wpsc_get_ep_settings();"><?php _e('Email Piping','wpsc-ep');?></a></li>
<li id="wpsc_settings_ep_rules" role="presentation"><a href="javascript:wpsc_get_ep_rules_settings();"><?php _e('Email Piping Rules','wpsc-ep');?></a></li>

<script>
  function wpsc_get_ep_settings(){
    jQuery('.wpsc_setting_pills li').removeClass('active');
    jQuery('#wpsc_settings_ep').addClass('active');
    jQuery('.wpsc_setting_col2').html(wpsc_admin.loading_html);
    var data = {
      action: 'wpsc_get_ep_settings',
    };
    jQuery.post(wpsc_admin.ajax_url, data, function(response) {
      jQuery('.wpsc_setting_col2').html(response);
    });
  }
	
	function wpsc_get_ep_rules_settings(){
    jQuery('.wpsc_setting_pills li').removeClass('active');
    jQuery('#wpsc_settings_ep_rules').addClass('active');
    jQuery('.wpsc_setting_col2').html(wpsc_admin.loading_html);
    var data = {
      action: 'wpsc_get_ep_rules_settings',
    };
    jQuery.post(wpsc_admin.ajax_url, data, function(response) {
      jQuery('.wpsc_setting_col2').html(response);
    });
  }
</script>
