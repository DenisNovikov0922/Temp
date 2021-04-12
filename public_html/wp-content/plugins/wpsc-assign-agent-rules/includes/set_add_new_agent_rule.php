<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user, $wpdb,$wpscfunction;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {exit;}

$title = isset($_POST) && isset($_POST['wpsc_caa_title']) ? sanitize_text_field($_POST['wpsc_caa_title']) : '';
if (!$title) {exit;}

$agent_name  = isset($_POST['assigned_agent']) && is_array($_POST['assigned_agent']) ? $_POST['assigned_agent'] : array() ;
if (!$agent_name) {exit;}
$assigned_agents_name = array();
foreach( $agent_name as $agent ){
  $agent = intval($agent) ? intval($agent) : 0;
  if ($agent){
    $assigned_agents_name[] = $agent;
  }
}
if (!$assigned_agents_name) {exit;}

$conditions = isset($_POST) && isset($_POST['conditions']) && $_POST['conditions'] != '[]' ? sanitize_text_field($_POST['conditions']) : '';

$term = wp_insert_term( $title, 'wpsc_caa' );
if (!is_wp_error($term) && isset($term['term_id'])) {
  $load_order = $wpdb->get_var("select max(meta_value) as load_order from {$wpdb->prefix}termmeta WHERE meta_key='wpsc_caa_load_order'");
  $load_order = $load_order ? $load_order : 0;
  add_term_meta ($term['term_id'], 'load_order', ++$load_order);
  add_term_meta ($term['term_id'], 'agent_ids', $assigned_agents_name);
  add_term_meta ($term['term_id'], 'conditions', $conditions);
	do_action('wpsc_set_add_new_agent_rule',$term['term_id']);
	echo '{ "sucess_status":"1","messege":"'.__('Agent added successfully.','wpsc-caa').'" }';
} else {
	echo '{ "sucess_status":"0","messege":"'.__('An error occurred while creating agent.','wpsc-caa').'" }';
}
?>
