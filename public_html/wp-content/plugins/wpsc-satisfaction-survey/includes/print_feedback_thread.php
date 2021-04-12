<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user, $wpscfunction;

if ($thread_type == 'feedback'):
  
  $customer_name  = get_post_meta( $thread->ID, 'customer_name', true);
  $customer_email = get_post_meta( $thread->ID, 'customer_email', true);
  $attachments    = get_post_meta( $thread->ID, 'attachments', true);
  $ticket_id      = get_post_meta( $thread->ID,'ticket_id',true);
  
  ?>
  <div class="wpsc_thread">
    <div class="thread_avatar">
      <?php echo get_avatar( $customer_email, 40 )?>
    </div>
    <div class="thread_body">
      <div class="thread_user_name">
        <strong><?php echo $customer_name?></strong><small><i><?php echo sprintf( __('added feedback %1$s','wpsc'), $wpscfunction->time_elapsed_string($thread->post_date_gmt) )?></i></small><br>
        <?php if ( apply_filters('wpsc_thread_email_visibility',$current_user->has_cap('wpsc_agent')) ) {?>
          <small><?php echo $customer_email?></small>
        <?php }?>
        <?php if ($wpscfunction->has_permission('delete_ticket',$ticket_id)):?>
          <i onclick="wpsc_get_delete_thread(<?php echo $ticket_id ?>,<?php echo $thread->ID ?>);" class="fa fa-trash thread_action_btn" title="<?php _e('Delete this thread','wpsc');?>"></i>
          <i onclick="wpsc_get_edit_thread(<?php echo $ticket_id ?>,<?php echo $thread->ID ?>);"   class="fa fa-edit thread_action_btn"  title="<?php _e('Edit this thread','wpsc');?>"></i>
        <?php endif;?>
      </div>
      <div class="thread_messege"><?php echo $thread->post_content?></div>
      <div onclick="wpsc_ticket_thread_expander_toggle(this);" class="col-md-12 wpsc_ticket_thread_expander" style="padding: 0px; display: none;">
         View More ...
      </div>
    </div>
  </div>
  <?php
endif;
