<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $wpscfunction;
$sf_page_id       = get_option('wpsc_sf_page','0');
$sf_page_url      = get_permalink( $sf_page_id );

if (strpos($str, '{satisfaction_survey_links}') !== false) {

	$ticket_auth_code = $wpscfunction->get_ticket_fields($ticket_id,'ticket_auth_code');

	$ratings = get_terms([
		'taxonomy'   => 'wpsc_sf_rating',
		'hide_empty' => false,
		'orderby'    => 'meta_value_num',
		'order'    	 => 'ASC',
		'meta_query' => array('order_clause' => array('key' => 'load_order')),
	]);

    $replace_data = '<div class="container" style="width:100%;float:left;clear:both">';
    foreach ($ratings as $rating) {
        $color = get_term_meta($rating->term_id, 'color', true);
        $link = $sf_page_url.'?rating_id='.$rating->term_id.'&ticket_id='.$ticket_id.'&auth_code='.$ticket_auth_code;
        $replace_data .= '<a href="'.$link.'" target="_blank" class="rating_element" style="color: #ffffff;padding:5px 5px;margin-right: 5px;border-radius: 5px;float:left;margin-bottom:2px;background-color:'.$color.'">'.$rating->name.'</a>';
    }
    $replace_data .= '</div>';
	$str = preg_replace('/{satisfaction_survey_links}/', $replace_data, $str);	
}

if (strpos($str, '{sf_rating}') !== false) {
    $replace_data = '';
    $ticket_rating = $wpscfunction->get_ticket_meta($ticket_id, 'sf_rating', true);
    $ticket_rating = is_numeric($ticket_rating) ? intval($ticket_rating) : 0;
    if ($ticket_rating) {
        $rating_term = get_term_by('id', $ticket_rating, 'wpsc_sf_rating');
        $replace_data = $rating_term->name;
    } else {
        $replace_data = __('No rating available', 'wpsc-sf');
    }
    $str = preg_replace('/{sf_rating}/', $replace_data, $str);
}

if (strpos($str, '{sf_last_feedback}') !== false) {
	
	$threads = get_posts(array(
    	'post_type'      => 'wpsc_ticket_thread',
    	'post_status'    => 'publish',
    	'posts_per_page' => '1',
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
            	'value'   => 'feedback',
            	'compare' => '='
        	),
    	),
	));

    $thread_id = $threads ? $threads[0]->ID : 0;
    if ($thread_id) {
        $last_feedback = $threads[0]->post_content;
    } else {
        $last_feedback = __('No feedback available', 'wpsc-sf');
	}
	
    $str = preg_replace('/{sf_last_feedback}/', $last_feedback, $str);
}