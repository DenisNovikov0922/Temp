<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user, $wpdb;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {exit;}

$rating_name = isset($_POST) && isset($_POST['rating_name']) ? sanitize_text_field($_POST['rating_name']) : '';
if (!$rating_name) {exit;}

$rating_color = isset($_POST) && isset($_POST['rating_color']) ? sanitize_text_field($_POST['rating_color']) : '';
if (!$rating_color) {exit;}

$term = wp_insert_term( $rating_name, 'wpsc_sf_rating' );
if (!is_wp_error($term) && isset($term['term_id'])) {
  $load_order = $wpdb->get_var("select max(meta_value) as load_order from {$wpdb->prefix}termmeta WHERE meta_key='load_order'");
  add_term_meta ($term['term_id'], 'load_order', ++$load_order);
  add_term_meta ($term['term_id'], 'color', $rating_color);
	do_action('wpsc_set_add_rating',$term['term_id']);
	echo '{ "sucess_status":"1","messege":"'.__('Rating added successfully.','wpsc-sf').'" }';
} else {
	echo '{ "sucess_status":"0","messege":"'.__('An error occured while creating rating.','wpsc-sf').'" }';
}
