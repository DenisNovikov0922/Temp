<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user, $wpscfunction;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
}

$term_id = isset($_POST) && isset($_POST['term_id']) ? intval($_POST['term_id']) : 0;
if(!$term_id) die();

	$title = isset($_POST) && isset($_POST['wpsc_ep_rule_title']) ? sanitize_text_field($_POST['wpsc_ep_rule_title']) : '';
	if (!$title) {exit;}
	wp_update_term($term_id, 'wpsc_ep_rules', array('name' => $title));	
  	
	$wpsc_ep_to_address = isset($_POST) && isset($_POST['wpsc_ep_to_address']) ? explode("\n",  $_POST['wpsc_ep_to_address']) : array();
	$wpsc_ep_to_address = $wpscfunction->sanitize_array($wpsc_ep_to_address);
	update_term_meta ($term_id, 'wpsc_ep_to_address', $wpsc_ep_to_address);
	
	$wpsc_ep_has_words = isset($_POST) && isset($_POST['wpsc_ep_has_words']) ? explode("\n",  $_POST['wpsc_ep_has_words']) : array();
	$wpsc_ep_has_words = $wpscfunction->sanitize_array($wpsc_ep_has_words);
	update_term_meta ($term_id, 'wpsc_ep_has_words', $wpsc_ep_has_words);
	
	$status = isset($_POST) && isset($_POST['ticket_status']) ? intval($_POST['ticket_status']) : 0;
	if($status) update_term_meta ($term_id, 'ticket_status', $status);
	
	$category = isset($_POST) && isset($_POST['ticket_category']) ? intval($_POST['ticket_category']) : 0;
	if($category) update_term_meta ($term_id, 'ticket_category', $category);
	
	$priority = isset($_POST) && isset($_POST['ticket_priority']) ? intval($_POST['ticket_priority']) : 0;
	if($priority) update_term_meta ($term_id, 'ticket_priority', $priority);
	
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
				$field_data = isset($_POST[$field->slug]) ? trim(sanitize_text_field($_POST[$field->slug])) : '';
				update_term_meta( $term_id, $field->slug, $field_data );
				break;
				
			case '3':																
				$arrVal = isset($_POST[$field->slug]) && is_array($_POST[$field->slug]) ? $wpscfunction->sanitize_array($_POST[$field->slug]) : array();								
				delete_term_meta($term_id, $field->slug);				
				if($arrVal){	        
	        foreach ($arrVal as $value) {
	          add_term_meta($term_id, $field->slug, $value);
	        }
	      }	       	    
				break;						
				
			case '6':
				$date = isset($_POST[$field->slug]) && $_POST[$field->slug] ? trim(sanitize_text_field($_POST[$field->slug])) : '';
				if($date) $date = $wpscfunction->calenderDateFormatToDateTime($date);
				update_term_meta( $term_id, $field->slug, $date );
				break;
			
			case '21':
				$text = isset($_POST[$field->slug]) ? sanitize_text_field($_POST[$field->slug]) : '';
				if($text) $args[$field->slug] = date("H:i:s " ,strtotime($text));;
				update_term_meta ($term_id, $field->slug, $text);
				break;	

			default:
			do_action('wpsc_edit_ep_rule_meta_custom_field',$term_id, $field,$tf_type);
				break;
		}																			
	}
	
	$extra_emails = trim(sanitize_textarea_field($_POST['wpsc_ticket_et_user']));
	$wpsc_ticket_et_user = isset($_POST) && strlen($extra_emails) ? explode("\n", $extra_emails ) : array();
	$wpsc_ticket_et_user = $wpscfunction->sanitize_array($wpsc_ticket_et_user);
	update_term_meta ($term_id, 'wpsc_ticket_et_user', $wpsc_ticket_et_user);

echo '{ "sucess_status":"1","messege":"'.__('Email Piping Rule added successfully.','wpsc-ep').'" }';