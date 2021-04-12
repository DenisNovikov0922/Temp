<?php 
 if ( ! defined( 'ABSPATH' ) ) {
 		exit; // Exit if accessed directly
 }
 global $wpscfunction,$wpdb;
 
 $responder_email  = get_post_meta($thread_id,'customer_email',true);
 $user             = get_user_by('email',$responder_email);

 $assigned_agents  = $wpscfunction->get_ticket_meta($ticket_id,'assigned_agent',true);

 $wpsc_assign_auto = get_option('wpsc_assign_auto_responder'); 
 
 if($wpsc_assign_auto && !$assigned_agents && !empty($user) && $user->has_cap('wpsc_agent')){
  $agents = get_terms([
    'taxonomy'   => 'wpsc_agents',
  	'hide_empty' => false,
  	'meta_query' => array(
    'relation' => 'AND',
    array(
      'key'       => 'user_id',
      'value'     => $user->ID,
      'compare'   => '='
  		)
    ),
  ]);
  $new_agents = array();
  $new_agents[] = $agents[0]->term_id;
  $wpscfunction->assign_agent($ticket_id, $new_agents);
}