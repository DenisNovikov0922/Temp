<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $wpscfunction;

$ticket_history_all_with_notes = '';

$threads = get_posts(array(
  'post_type'      => 'wpsc_ticket_thread',
  'post_status'    => 'publish',
  'posts_per_page' => -1,
  'orderby'        => 'date',
  'order'          => 'DESC',
  'meta_query'     => array(
    'relation' => 'AND',
    array(
      'key'     => 'ticket_id',
      'value'   => $ticket_id,
      'compare' => '='
    ),
    array(
      'key'     => 'thread_type',
      'value'   => array('reply','report','note'),
      'compare' => 'IN'
    ),
  ),
));


$auth_id           = $wpscfunction->get_ticket_fields($ticket_id, 'ticket_auth_code');

if($threads){
  
  foreach ($threads as $thread) {
    $user_name 	 = get_post_meta($thread->ID,'customer_name',true);
		$thread_type = get_post_meta($thread->ID, 'thread_type',true);
		if($thread_type == 'note'){
			$thread_type  = '(private note)';
    }else{
      $thread_type  = '(' . $thread_type .')';
    }
    
    $attachments  = get_post_meta($thread->ID, 'attachments', true);

    $ticket_history_all_with_notes .= '<hr><strong>'.$user_name.'</strong> <small><i>'.$wpscfunction->time_elapsed_timestamp($thread->post_date).'</i> ' . $thread_type  . ' </small><br>'.$thread->post_content;
    
    if($attachments){
      $ticket_history_all_with_notes .= 'Attachments :';
       foreach( $attachments as $attachment ):
        $attach      = array();
        $attach_meta = get_term_meta($attachment);
        foreach ($attach_meta as $key => $value) {
          $attach[$key] = $value[0];
        }
        $download_url = home_url('/').'?wpsc_attachment='.$attachment.'&tid='.$ticket_id.'&tac='.$auth_id;
        $ticket_history_all_with_notes.= '<br><a  style="text-decoration:none;" href="'.$download_url.'" target="_blank">'.$attach['filename'].'</a>';
      endforeach;
    }
  }
}
