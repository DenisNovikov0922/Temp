<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

if ( ! class_exists( 'WPSC_EP_Functions' ) ) :
  
  final class WPSC_EP_Functions {
    
    function create_ticket_email_piping($args){
      include WPSC_EP_ABSPATH . 'includes/create_ticket_email_piping.php';
      return $ticket_id;
    }
    
    // Submit ticket
    function create_ticket_reply($args){
      include WPSC_EP_ABSPATH . 'includes/create_ticket_reply.php';
      return $thread_id;
    }
    
    function update_data($ticket_id,$meta_value){
      global $wpdb;
      $wpdb->update($wpdb->prefix.'wpsc_ticket', $meta_value, array('id'=>$ticket_id));

    }
    
    function response_mail_close_ticket($ticket_id, $customer_email){
      global $wpscfunction,$wpdb;
      $subject    = $wpscfunction->replace_macro(get_option('wpsc_ct_warn_email_subject'),$ticket_id);
      $subject    = '['.get_option('wpsc_ticket_alice','').$ticket_id.'] '.$subject;
      $body       = $wpscfunction->replace_macro(get_option('wpsc_ct_warn_email_body'),$ticket_id);
      $to         = isset($customer_email)? $customer_email : '';
      $from_name  = get_option('wpsc_en_from_name','');
      $from_email = get_option('wpsc_en_from_email','');
      $reply_to   = get_option('wpsc_en_reply_to','');
      
      $wpsc_email_sending_method = get_option('wpsc_email_sending_method');

      $args  = array(
        'ticket_id'     => $ticket_id,
        'from_email'    => $from_email,
        'reply_to'      => $reply_to,
        'email_subject' => $subject,
        'email_body'    => $body,
        'to_email'      => $to,
        'bcc_email'     => '',
        'date_created'  => date("Y-m-d H:i:s"),
        'mail_status'   => 0,
        'email_type'    => 'response_close_ticket'
 
      ); 
      
      if($wpsc_email_sending_method){
      
        $headers  = "From: {$from_name} <{$from_email}>\r\n";
        $headers .= "Reply-To: {$reply_to}\r\n";
        $headers .= "Content-Type: text/html; charset=utf-8\r\n";
    
         wp_mail($to, $subject, $body, $headers);
         
      }else{

        $wpdb->insert( $wpdb->prefix . 'wpsc_email_notification',$args);

      }

      do_action('wpsc_after_ep_close_notification_mail', $ticket_id, $args);

      
    }

    function response_mail_close_user($customer_email){
      global $wpscfunction,$wpdb;
      $subject    = get_option('wpsc_close_user_warn_email_subject');
      $body       = get_option('wpsc_close_user_warn_email_body');
      $to         = isset($customer_email)? $customer_email : '';
      
      $from_email = get_option('wpsc_en_from_email','');
      $reply_to   = get_option('wpsc_en_reply_to','');
      $reply_to   = $reply_to ? $reply_to : $from_email;
      
      $from_email = apply_filters('wpsc_reply_from_email_headers',$from_email,0);
      $reply_to   = apply_filters('wpsc_reply_replyto_headers',$reply_to,0);
      
      $wpsc_email_sending_method = get_option('wpsc_email_sending_method');
      
      $args  = array(
        'ticket_id'     => 0,
        'from_email'    => $from_email,
        'reply_to'      => $reply_to,
        'email_subject' => $subject,
        'email_body'    => $body,
        'to_email'      => $to,
        'bcc_email'     => '',
        'date_created'  => date("Y-m-d H:i:s"),
        'mail_status'   => 0,
        'email_type'    => 'response_close_user'
 
      ); 
      
      if($wpsc_email_sending_method){
      
        $headers  = "From: {$from_name} <{$from_email}>\r\n";
        $headers .= "Reply-To: {$reply_to}\r\n";
        foreach ($email_addresses as $email_address) {
          $headers .= "BCC: {$email_address}\r\n";
        }
    
        $headers .= "Content-Type: text/html; charset=utf-8\r\n";
    
         wp_mail($to, $subject, $body, $headers);
         
      }else{

        $wpdb->insert( $wpdb->prefix . 'wpsc_email_notification',$args);

      }

      

      do_action('wpsc_mail_response_close_user',$args);


    }
    
    function check_to_email_is_piping_email( $email_address ){
      
      $ep_gmail_address = get_option('wpsc_ep_email_address');
      if( strlen($ep_gmail_address) && $email_address == $ep_gmail_address){
        return true;
      }

      $ep_imap_address = get_option('wpsc_ep_imap_email_address');
      if( strlen($ep_imap_address) && $email_address == $ep_imap_address){
        return true;
      }

      $email_piping_rules = get_terms([
        'taxonomy'   => 'wpsc_ep_rules',
        'hide_empty' => false,
        'orderby'    => 'meta_value_num',
        'order'    	 => 'ASC',
        'meta_query' => array('order_clause' => array('key' => 'wpsc_en_rule_load_order')),
      ]);
      
      foreach ($email_piping_rules as $rule) {
          $wpsc_ep_to_address = get_term_meta($rule->term_id,'wpsc_ep_to_address',true);
          if( is_array($wpsc_ep_to_address) && in_array( $email_address, $wpsc_ep_to_address)){
            return true;
          }
      }

      return false;

    }
 
    
  }
    
endif;
$GLOBALS['wpscepfunction'] =  new WPSC_EP_Functions();