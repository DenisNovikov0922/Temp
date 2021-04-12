<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $current_user;

if( !$current_user->ID || !$current_user->has_cap('wpsc_agent') ) return;

?>
<span onclick="wpsc_get_canned_reply()" ><?php _e('Canned Reply','wpsc-canned-reply')?></span>
<script>
function wpsc_get_canned_reply(){  
  wpsc_modal_open(wpsc_admin.canned_reply);
  var data = {
    action: 'get_canned_reply'  
  };
  jQuery.post(wpsc_admin.ajax_url, data, function(response_str) {
    var response = JSON.parse(response_str);
    jQuery('#wpsc_popup_body').html(response.body);
    jQuery('#wpsc_popup_footer').html(response.footer);
    jQuery('#wpsc_cat_name').focus();
  });  
}
</script>


