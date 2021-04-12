<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $wpscfunction,$wpdb,$wpscepfunction;

$email_piping_rules = get_terms([
	'taxonomy'   => 'wpsc_ep_rules',
	'hide_empty' => false,
	'orderby'    => 'meta_value_num',
	'order'    	 => 'ASC',
	'meta_query' => array('order_clause' => array('key' => 'wpsc_en_rule_load_order')),
	]);

foreach ($email_piping_rules as $rule) {
  
    $wpsc_ep_to_address = get_term_meta($rule->term_id,'wpsc_ep_to_address',true);
    $wpsc_ep_to_address = is_array($wpsc_ep_to_address) ? $wpsc_ep_to_address : array();
    
    $wpsc_ep_has_words = get_term_meta($rule->term_id,'wpsc_ep_has_words',true);
    $wpsc_ep_has_words = is_array($wpsc_ep_has_words) ? $wpsc_ep_has_words : array();
	
    $flag = false;
    
		foreach ( $wpsc_ep_to_address as $to_address ){
			$to_address = trim($to_address);
			
			if($to_address &&  (fnmatch($to_address, $args['to_email'])  || ( isset($args['cc_mail']) && in_array($to_address,$args['cc_mail']) ) )){
					$flag = true;
			}
		}
    
    if(!$flag){
			foreach ( $wpsc_ep_has_words as $wpsc_ep_has_word ){
				$wpsc_ep_has_word = trim($wpsc_ep_has_word);
				if($wpsc_ep_has_word && fnmatch($wpsc_ep_has_word, $args['ticket_subject'])){
					$flag = true;
					break;
				}
				
				if($wpsc_ep_has_word && (preg_match($wpsc_ep_has_word , $args['ticket_description']))){
					$flag = true;
					break;
				}
			}
    }
    
    if(!$flag) continue;
    
    $ticket_category = get_term_meta($rule->term_id,'ticket_category',true);
	$ticket_status = get_term_meta($rule->term_id,'ticket_status',true);
    $ticket_priority = get_term_meta($rule->term_id,'ticket_priority',true);
		
		$meta_value = array(
			'ticket_category'=> $ticket_category,
			'ticket_status'=> $ticket_status,
			'ticket_priority'=> $ticket_priority
		);
		$wpscepfunction->update_data($ticket_id,$meta_value);
	
	$wpsc_ticket_et_user = get_term_meta($rule->term_id,'wpsc_ticket_et_user',true);

	$prev_users = $wpscfunction->get_ticket_meta($ticket_id,'extra_ticket_users');
	if($prev_users){
		$wpsc_ticket_et_user = array_merge( $wpsc_ticket_et_user, $prev_users );
		$wpsc_ticket_et_user =  array_unique($wpsc_ticket_et_user);
	}
	if($wpsc_ticket_et_user){
		$wpscfunction->add_extra_users($ticket_id, $wpsc_ticket_et_user); 
	}
    
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
  if($fields){
    foreach ($fields as $field) {
      $type = get_term_meta( $field->term_id, 'wpsc_tf_type', true);
      switch($type){
        case '1':
        case '2':
        case '4':
        case '5':
        case '6':
        case '7':
        case '8':
		case '9':
		case '21':
		case '18':
          $field_val = get_term_meta($rule->term_id, $field->slug, true );
          $field_val = $field_val ? $field_val : '';
					$wpscfunction->add_ticket_meta($ticket_id,$field->slug,$field_val);
          break;
          
        case '3':
          $field_val = get_term_meta($rule->term_id, $field->slug );
          $field_val = is_array($field_val) ? $field_val : array();
          foreach($field_val as $val){
						$wpscfunction->add_ticket_meta($ticket_id,$field->slug,$val);
          }
          break;
          
        default:
					do_action('wpsc_apply_ep_rule_custom_form_field', $field, $this, $rule, $ticket_id);
          break;
      }
    }
    
    break;
  }
}