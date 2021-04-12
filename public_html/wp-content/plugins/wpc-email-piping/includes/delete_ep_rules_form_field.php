<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {exit;}

$term_id = isset($_POST) && isset($_POST['term_id']) ? intval($_POST['term_id']) : 0;
if (!$term_id) {exit;}


wp_delete_term($term_id, 'wpsc_ep_rules');

echo '{ "sucess_status":"1","messege":"'.__('Rule deleted successfully.','wpsc-ep').'" }';