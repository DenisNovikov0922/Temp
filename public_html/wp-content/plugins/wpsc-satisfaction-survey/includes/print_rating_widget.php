<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user, $wpscfunction;

$wpsc_appearance_individual_ticket_page = get_option('wpsc_individual_ticket_page');

$rating = get_term_by( 'slug', 'rating', 'wpsc_ticket_widget' );
$wpsc_custom_widget_localize = get_option('wpsc_custom_widget_localize');
$ticket_widget_rating_name = $wpsc_custom_widget_localize['custom_widget_'.$rating->term_id];
// Do not print if ticket status is not closed
$close_ticket_status = get_option('wpsc_close_ticket_status');
$ticket_status       = $wpscfunction->get_ticket_fields($ticket_id,'ticket_status');

if($close_ticket_status != $ticket_status ) return;

$customer_email = $wpscfunction->get_ticket_fields($ticket_id,'customer_email');
$rating = $wpscfunction->get_ticket_meta($ticket_id,'sf_rating',true);

$rating_term = get_term_by( 'slug', 'sf_rating', 'wpsc_ticket_custom_fields' );
$title = get_term_meta( $rating_term->term_id, 'wpsc_tf_label', true );
?>

<div class="row" style="padding-bottom:10px; background-color:<?php echo $wpsc_appearance_individual_ticket_page['wpsc_ticket_widgets_bg_color']?> !important;color:<?php echo $wpsc_appearance_individual_ticket_page['wpsc_ticket_widgets_text_color']?> !important;border-color:<?php echo $wpsc_appearance_individual_ticket_page['wpsc_ticket_widgets_border_color']?> !important;">
  <h4 class="widget_header" style="margin-bottom:10px !important;"><i class="fas fa-star-half-alt"></i> <?php echo $ticket_widget_rating_name?></h4>
  <hr class="widget_divider">
  
  <?php
  
  // Current user is not ticket reporter & rating not available
  if( $customer_email != $current_user->user_email && !$rating ){
    _e('Rating not available!','wpsc-sf');
  }
  
  // Current user is not ticket reporter & rating available
  if( $customer_email != $current_user->user_email && $rating ){
    $rating_term = get_term_by('id',$rating,'wpsc_sf_rating');
    $color       = get_term_meta($rating,'color',true);
    ?>
    <span class="wpsp_admin_label" style="background-color:<?php echo $color?>;color:#ffffff;"><?php echo $rating_term->name?></span>
    <?php
  }
  
  // Current user is ticket reporter & rating not available
  if( $customer_email == $current_user->user_email && !$rating ){
    _e('Rating not available!','wpsc-sf');
  }
  
  // Current user is ticket reporter & rating available
  if( $customer_email == $current_user->user_email && $rating ){
    $rating_term = get_term_by('id',$rating,'wpsc_sf_rating');
    $color       = get_term_meta($rating,'color',true);
    ?>
    <span class="wpsp_admin_label" style="background-color:<?php echo $color?>;color:#ffffff;"><?php echo $rating_term->name?></span>
    <?php
  }
  
  ?>
  
</div>