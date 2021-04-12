<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user, $wpscfunction, $wpdb;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
}
// Name
$title = isset($_POST) && isset($_POST['wpsc_ep_rule_title']) ? sanitize_text_field($_POST['wpsc_ep_rule_title']) : '';
if (!$title) {exit;}

$term = wp_insert_term( $title, 'wpsc_ep_rules' );
if (!is_wp_error($term) && isset($term['term_id'])) { 
	$load_order = $wpdb->get_var("select max(meta_value) as load_order from {$wpdb->prefix}termmeta WHERE meta_key='wpsc_en_rule_load_order'");
	add_term_meta ($term['term_id'], 'wpsc_en_rule_load_order', ++$load_order); 
	
	$wpsc_ep_to_address = isset($_POST) && isset($_POST['wpsc_ep_to_address']) ? explode("\n",  $_POST['wpsc_ep_to_address']) : array();
	$wpsc_ep_to_address = $wpscfunction->sanitize_array($wpsc_ep_to_address);
	add_term_meta ($term['term_id'], 'wpsc_ep_to_address', $wpsc_ep_to_address);
	
	$wpsc_ep_has_words = isset($_POST) && isset($_POST['wpsc_ep_has_words']) ? explode("\n",  $_POST['wpsc_ep_has_words']) : array();
	$wpsc_ep_has_words = $wpscfunction->sanitize_array($wpsc_ep_has_words);
	add_term_meta ($term['term_id'], 'wpsc_ep_has_words', $wpsc_ep_has_words);
	
	$status = isset($_POST) && isset($_POST['ticket_status']) ? intval($_POST['ticket_status']) : 0;
	if($status) add_term_meta ($term['term_id'], 'ticket_status', $status);
	
	$category = isset($_POST) && isset($_POST['ticket_category']) ? intval($_POST['ticket_category']) : 0;
	if($category) add_term_meta ($term['term_id'], 'ticket_category', $category);
	
	$priority = isset($_POST) && isset($_POST['ticket_priority']) ? intval($_POST['ticket_priority']) : 0;
	if($priority) add_term_meta ($term['term_id'], 'ticket_priority', $priority);
	
	$fields = get_terms([
			'taxonomy'   => 'wpsc_ticket_custom_fields',
			'hide_empty' => false,
			'orderby'    => 'meta_value_num',
			'meta_key'	 => 'wpsc_tf_load_order',
			'order'    	 => 'ASC',
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key'       => 'agentonly',
					'value'     => array(0,1),
					'compare'   => 'IN'
				),
				array(
					'key'       => 'wpsc_tf_type',
					'value'     => '0',
					'compare'   => '>'
				),
			),
			]);
				
		foreach ($fields as $field) {						  															
			$tf_type = get_term_meta( $field->term_id, 'wpsc_tf_type', true);
			switch ($tf_type) {
				case '1':	
				case '2':
				case '4':
		    case '5':
				case '7':
				case '8':
			case '9':
			case '18':
			case '21':							
					$field_data = isset($_POST[$field->slug]) ? sanitize_text_field($_POST[$field->slug]) : '';
					add_term_meta( $term['term_id'], $field->slug, $field_data );
					break;
					
				case '3':								
					$arrVal = isset($_POST[$field->slug]) && is_array($_POST[$field->slug]) ? $wpscfunction->sanitize_array($_POST[$field->slug]) : array();
					if($arrVal){
		        foreach ($arrVal as $key => $value) {
		  				add_term_meta( $term['term_id'], $field->slug, $value );
		  			}
		      }
					
					break;					
					
				case '6':
					$date = isset($_POST[$field->slug]) && $_POST[$field->slug] ? sanitize_text_field($_POST[$field->slug]) : '';								
					if($date) $date = $wpscfunction->calenderDateFormatToDateTime($date);
					add_term_meta( $term['term_id'], $field->slug, $date );
					break;
				
				
				case '21':
					$text = isset($_POST[$field->slug]) ? sanitize_text_field($_POST[$field->slug]) : '';
					if($text) $args[$field->slug]  = date("H:i:s " ,strtotime($text));;
					add_term_meta ($term['term_id'], $field->slug, $text);
					break;	
						
				default:
						do_action('wpsc_add_ep_rule_meta_custom_field',$term['term_id'],$field,$tf_type);
					break;
			}							
		}											
	
		$extra_emails = trim(sanitize_textarea_field($_POST['wpsc_ticket_et_user']));
		$wpsc_ticket_et_user = isset($_POST) && strlen($extra_emails) ? explode("\n", $extra_emails ) : array();
		$wpsc_ticket_et_user = $wpscfunction->sanitize_array($wpsc_ticket_et_user);
		add_term_meta ($term['term_id'], 'wpsc_ticket_et_user', $wpsc_ticket_et_user);

	do_action('wpsc_set_add_ep_rules',$term['term_id']);


	echo '{ "sucess_status":"1","messege":"'.__('Email Piping Rule added successfully.','wpsc-ep').'" }';
} else {
	echo '{ "sucess_status":"0","messege":"'.__('An error occured while creating ep rule.','wpsc-ep').'" }';
}
