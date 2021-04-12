<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user, $wpscfunction,$wpdb;

$wpsc_ticket_id_type = get_option('wpsc_ticket_id_type');
// Ticket Status
$default_status = get_option('wpsc_default_ticket_status');

// Customer email
$customer_email = isset($args['customer_email']) ? sanitize_text_field($args['customer_email']) : '';

// Customer name
$user_info     = get_user_by('email',$customer_email);
$customer_name = isset($args['customer_name']) ? $args['customer_name'] : '';
if($user_info){
	$customer_name = $user_info->display_name;
	$user_type = "user";
}else{
	$user_type = "guest";
}

// Subject
$subject          = get_term_by('slug', 'ticket_subject', 'wpsc_ticket_custom_fields' );
$wpsc_tf_limit    = get_term_meta( $subject->term_id,'wpsc_tf_limit',true);
$ticket_subject = isset($args['ticket_subject']) ? sanitize_text_field($args['ticket_subject']) : apply_filters( 'wpsc_default_subject_text', __('NA','supportcandy') );
if($wpsc_tf_limit){
	$ticket_subject   = substr($ticket_subject,0,$wpsc_tf_limit);
}

// Category
$default_category = get_option('wpsc_default_ticket_category');
$ticket_category = isset($args['ticket_category']) ? intval($args['ticket_category']) : $default_category;
$ticket_category = apply_filters('wpsc_create_ticket_category', $ticket_category, $args);


// Priority
$default_priority = get_option('wpsc_default_ticket_priority');
$ticket_priority = isset($args['ticket_priority']) ? intval($args['ticket_priority']) : $default_priority;
$ticket_priority = apply_filters('wpsc_create_ticket_priority', $ticket_priority, $args);

$values = array(
	'ticket_status'    => $default_status,
	'customer_name'    => $customer_name,
	'customer_email'   => $customer_email,
	'ticket_subject'   => $ticket_subject,
	'user_type'        => $user_type,
	'ticket_category'  => $ticket_category,
	'ticket_priority'  => $ticket_priority,
	'date_created'     => date("Y-m-d H:i:s"),
	'date_updated'     => date("Y-m-d H:i:s"),
	'ip_address'       => ' ',
	'agent_created'    => 0,
	'ticket_auth_code' => $wpscfunction->getRandomString(10),
	'active'           => '1'
);

if(!$wpsc_ticket_id_type){
	$id = 0;
	do {
		$id = rand(11111111, 99999999);
		$sql = "select id from {$wpdb->prefix}wpsc_ticket where id=" . $id;
		$result = $wpdb->get_var($sql);
	} while ($result);
	$values['id'] = $id;
}

$ticket_id = $wpscfunction->create_new_ticket($values);

$wpscfunction->add_ticket_meta($ticket_id,'assigned_agent',0);

// Insert to email
$to_email = isset($args['to_email']) ? sanitize_text_field($args['to_email']) : '';
if($to_email){
	$wpscfunction->add_ticket_meta( $ticket_id, 'to_email', $to_email );
}
$cc_mail = isset($args['cc_mail']) ? $wpscfunction->sanitize_array($args['cc_mail']) : array();
$wpsc_add_additional_recepients = get_option('wpsc_add_additional_recepients');

if ($cc_mail && $wpsc_add_additional_recepients) {
	$wpscfunction->add_extra_users( $ticket_id, $cc_mail);
}

// Custom fields
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
      'value'     => '0',
      'compare'   => '='
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
	$tf_type = get_term_meta( $field->term_id, 'wpsc_tf_type', true);
	switch ($tf_type) {
		case '1':
		case '2':
		case '4':
	    case '5':
		case '7':
		case '8':
		case '9':
		case '21':
			//text,drop,radio,textarea,url,email,number,time
			$text = isset($args[$field->slug]) ? $args[$field->slug] : '';
			$text = apply_filters( 'wpsc_ep_ct_default_single_value', $text, $field, $ticket_id );
			if($text){
				$wpscfunction->add_ticket_meta($ticket_id, $field->slug,$text);
			}
		break;

		case '3':
			//checkbox
			$arrVal = isset($args[$field->slug]) && is_array($args[$field->slug]) ? $args[$field->slug] : array();
			$arrVal = apply_filters( 'wpsc_ep_ct_default_checkbox_value', $arrVal, $field, $ticket_id );
      		if($arrVal){
				foreach ($arrVal as $key => $value) {
					$wpscfunction->add_ticket_meta($ticket_id, $field->slug,$value);
				}
			}
		break;

		case '10':
			//files
			$arrVal = isset($args[$field->slug]) && is_array($args[$field->slug]) ? $args[$field->slug] : array();
			$arrVal = apply_filters( 'wpsc_ep_ct_default_file_value', $arrVal, $field, $ticket_id ) ;
      		if($arrVal){
				foreach ($arrVal as $key => $value) {
					$wpscfunction->add_ticket_meta($ticket_id, $field->slug,$value);
					update_term_meta ($value, 'active', '1');
				}
			}
		break;

		case '6':
			//datetime
			$date = isset($args[$field->slug]) && $args[$field->slug] ? $args[$field->slug] : '';
			$date = apply_filters( 'wpsc_ep_ct_default_datetime_value', $date, $field, $ticket_id );
			if($date){
				$date = $wpscfunction->calenderDateFormatToDateTime($date);
				$wpscfunction->add_ticket_meta($ticket_id, $field->slug,$date);
			}
			break;

		default:
			do_action('wpsc_add_ticket_meta_custom_field',$ticket_id,$tf_type,$args,$field);
			break;
	}
}
}
// Description
$description            = get_term_by('slug', 'ticket_description', 'wpsc_ticket_custom_fields' );
$wpsc_default_desc      = get_term_meta( $description->term_id,'wpsc_tf_default_description',true);
$ticket_description = isset($args['ticket_description']) ? $args['ticket_description'] : apply_filters( 'wpsc_default_description_text', $wpsc_default_desc );
$description_attachment = isset($args['desc_attachment']) ? $args['desc_attachment'] : array();
$attachments = array();

$wpsc_allow_attach_create_ticket = get_option('wpsc_allow_attach_create_ticket');

$desc_flag = false;
if( $user_info ){
	if($user_info->has_cap('wpsc_agent') && in_array('agents',$wpsc_allow_attach_create_ticket) ){
		$desc_flag = true;	
	}elseif( !$user_info->has_cap('wpsc_agent') && in_array('customers',$wpsc_allow_attach_create_ticket) ){
		$desc_flag = true;
	}
}elseif(in_array('guests',$wpsc_allow_attach_create_ticket)){
	$desc_flag = true;
}

if($desc_flag){
	foreach ($description_attachment as $key => $value) {
		$attachment_id = intval($value);
		$attachments[] = $attachment_id;
		update_term_meta ($attachment_id, 'active', '1');
	}
}
if( $user_info ){
	$signature = get_user_meta($user_info->ID,'wpsc_agent_signature',true);
	if($signature){
	 	$signature= stripcslashes(htmlspecialchars_decode($signature, ENT_QUOTES));
	 	$ticket_description.= $signature;
	}
}

$ticket_description = preg_replace('/(<(script|style)\b[^>]*>).*?(<\/\2>)/s', "", $ticket_description);

// Save thread description
$thread_args = array(
  'ticket_id'      => $ticket_id,
  'reply_body'     => $wpscfunction->replace_macro($ticket_description,$ticket_id),
  'customer_name'  => $customer_name,
  'customer_email' => $customer_email,
  'attachments'    => $attachments,
  'thread_type'    => 'report',
	'reply_source'   => $args['reply_source'],
	'user_seen'      => date("Y-m-d H:i:s")
);
$thread_args = apply_filters( 'wpsc_thread_args', $thread_args );
$thread_id = $wpscfunction->submit_ticket_thread($thread_args);
include( WPSC_EP_ABSPATH . 'includes/apply_ep_rules.php' );

$wpsc_reg_guest_user_after_create_ticket = get_option('wpsc_reg_guest_user_after_create_ticket');

if($wpsc_reg_guest_user_after_create_ticket && !email_exists($customer_email) ) {
	$random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
	$user_id = wp_create_user( $customer_name, $random_password, $customer_email );
	$creds = array(
		'user_login'    => $customer_name,
		'user_password' => $random_password,
	);
	wp_new_user_notification($user_id,null,'both');
	wp_signon( $creds, false );
}
do_action( 'wpsc_after_ticket_created', $ticket_id,$args);
do_action( 'wpsc_ticket_created', $ticket_id);
