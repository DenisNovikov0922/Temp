<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $wpdb, $current_user, $wpscfunction;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {exit;}

$term_id = isset($_POST) && isset($_POST['term_id']) ? intval($_POST['term_id']) : 0;
if (!$term_id) {exit;}

$title = isset($_POST) && isset($_POST['wpsc_caa_title']) ? sanitize_text_field($_POST['wpsc_caa_title']) : '';
if (!$title) {exit;}

$agent_name  = isset($_POST['assigned_agent']) && is_array($_POST['assigned_agent']) ? $_POST['assigned_agent'] : array() ;
if (!$agent_name) {exit;}

$conditions = isset($_POST) && isset($_POST['conditions']) && $_POST['conditions'] != '[]' ? sanitize_text_field($_POST['conditions']) : '';

wp_update_term($term_id, 'wpsc_caa', array(
  'name' => $title
));
update_term_meta ($term_id, 'agent_ids', $agent_name);
update_term_meta ($term_id, 'conditions', $conditions);

do_action('wpsc_set_edit_condition',$term_id);
echo '{ "sucess_status":"1","messege":"'.__('Condition updated successfully.','wpsc-caa').'" }';
