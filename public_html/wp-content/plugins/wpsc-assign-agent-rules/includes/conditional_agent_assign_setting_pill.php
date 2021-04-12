<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<li id="wpsc_settings_caa" role="presentation"><a href="javascript:wpsc_get_caa_settings();"><?php _e('Assign Agent Rules','wpsc-caa');?></a></li>

<script>
	function wpsc_get_caa_settings(){
    jQuery('.wpsc_setting_pills li').removeClass('active');
		jQuery('#wpsc_settings_caa').addClass('active');
		jQuery('.wpsc_setting_col2').html(wpsc_admin.loading_html);
		var data = {
			action : 'wpsc_get_caa_settings'
		}
		jQuery.post(wpsc_admin.ajax_url, data, function(response) {
			jQuery('.wpsc_setting_col2').html(response);
		});
	}
</script>
