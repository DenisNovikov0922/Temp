<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {exit;}

$rating_id = isset($_POST['rating_id']) ? intval($_POST['rating_id']) : 0;
if(!$rating_id) exit;

wp_delete_term($rating_id, 'wpsc_sf_rating');

do_action('wpsc_delete_rating',$rating_id);

echo '{ "sucess_status":"1","messege":"'.__('Rating deleted successfully.','wpsc-sf').'" }';
