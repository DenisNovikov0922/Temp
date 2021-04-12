<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $wpscfunction,$current_user;

$ticket_status = $wpscfunction->get_ticket_fields($ticket_id,'ticket_status');
$wpsc_close_ticket_status = get_option('wpsc_close_ticket_status');

$rating = $wpscfunction->get_ticket_meta($ticket_id,'sf_rating',true);
$wpsc_ticket_rating_status = $wpscfunction->get_ticket_meta($ticket_id,'wpsc_ticket_rating_status',true);
$customer_email = $wpscfunction->get_ticket_fields($ticket_id,'customer_email');

$is_active = $wpscfunction->get_ticket_status($ticket_id);

if ($is_active && ($ticket_status == $wpsc_close_ticket_status ) && (!$rating || !$wpsc_ticket_rating_status) && ($current_user->user_email == $customer_email) ) {
  ?>
  
  <script>
  jQuery(document).ready(function(){
    
    var ticket_id = <?php  echo $ticket_id; ?>;
    wpsc_modal_open(wpsc_admin.sf_rating);
    
    var data={
      action : 'wpsc_sf_get_ratings',
      ticket_id : ticket_id
    }
    
    jQuery.post(wpsc_admin.ajax_url, data, function(response_str) {
    
      var response = JSON.parse(response_str);
      jQuery('#wpsc_popup_body').html(response.body);
      jQuery('#wpsc_popup_footer').html(response.footer);
    });
  
  });
  </script>
  
  <?php
}
 ?>