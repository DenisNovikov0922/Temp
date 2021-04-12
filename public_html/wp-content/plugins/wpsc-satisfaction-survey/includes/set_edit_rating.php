<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user, $wpdb;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {exit;}

$rating_id = isset($_POST['rating_id']) ? intval($_POST['rating_id']) : 0;
if(!$rating_id) exit;

$rating_name = isset($_POST) && isset($_POST['rating_name']) ? sanitize_text_field($_POST['rating_name']) : '';
if (!$rating_name) {exit;}

$rating_color = isset($_POST) && isset($_POST['rating_color']) ? sanitize_text_field($_POST['rating_color']) : '';
if (!$rating_color) {exit;}

wp_update_term($rating_id, 'wpsc_sf_rating', array(
  'name' => $rating_name
));
update_term_meta($rating_id, 'color', $rating_color);

do_action('wpsc_set_edit_rating',$rating_id);

echo '{ "sucess_status":"1","messege":"Success" }';