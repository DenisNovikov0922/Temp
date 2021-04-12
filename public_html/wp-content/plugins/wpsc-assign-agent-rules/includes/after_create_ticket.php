<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user, $wpscfunction;

$agent_role = get_terms([
	  'taxonomy'   => 'wpsc_caa',
	  'hide_empty' => false,
	  'orderby'    => 'meta_value_num',
	  'order'    	 => 'ASC',
	  'meta_query' => array('order_clause' => array('key' => 'load_order')),
	]);

$agents = array();
foreach ($agent_role as $agent) {
  $conditions     = get_term_meta($agent->term_id,'conditions',true);
  $agent_term_ids = get_term_meta($agent->term_id, 'agent_ids', true);
  if( $wpscfunction->check_ticket_conditions($conditions,$ticket_id) && $agent_term_ids ){
		foreach ($agent_term_ids as $agent_id) {
			$agents[] = $agent_id;
		}
		$agents = array_unique($agents);
	}
}

if($agents){
	$wpscfunction->assign_agent($ticket_id, $agents);	
}
