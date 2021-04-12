<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$rating = get_term_by( 'slug', 'rating', 'wpsc_ticket_widget' );

if($rating){
	wp_delete_term( $rating->term_id, 'wpsc_ticket_widget' );
	$wpsc_custom_widget_localize = get_option('wpsc_custom_widget_localize');
	unset($wpsc_custom_widget_localize['custom_widget_' .$rating->term_id]);
  update_option('wpsc_custom_widget_localize', $wpsc_custom_widget_localize);
}

$rating_field = get_term_by( 'slug', 'sf_rating', 'wpsc_ticket_custom_fields' );
$wpsc_export_ticket_list = get_option('wpsc_export_ticket_list');

if($rating_field){
	update_term_meta ($rating_field->term_id, 'wpsc_allow_ticket_list', '0');
	update_term_meta ($rating_field->term_id, 'wpsc_allow_ticket_filter', '0');
}

if (($key = array_search('sf_rating', $wpsc_export_ticket_list)) !== false) {
	unset($wpsc_export_ticket_list[$key]);
	update_option('wpsc_export_ticket_list',$wpsc_export_ticket_list);
}
