<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

global $wpscfunction,$wpdb;

$from_name     = get_option('wpsc_en_from_name','');
$from_email    = get_option('wpsc_en_from_email','');
$reply_to      = get_option('wpsc_en_reply_to','');
$ignore_emails = get_option('wpsc_en_ignore_emails','');

if ( !$from_name || !$from_email ) {
  return;
}


$email_templates = get_terms([
  'taxonomy'   => 'wpsc_en',
  'hide_empty' => false,
  'orderby'    => 'ID',
  'order'      => 'ASC',
  'meta_query' => array(
    'relation' => 'AND',
    array(
      'key'     => 'type',
      'value'   => 'ticket_rating',
      'compare' => '='
    ),
  ),
]);

foreach ($email_templates as $email) :
  
  $conditions = get_term_meta($email->term_id,'conditions',true);
  if( $wpscfunction->check_ticket_conditions($conditions,$ticket_id) ) :
    
    $subject          = $wpscfunction->replace_macro(get_term_meta($email->term_id,'subject',true),$ticket_id);
    $subject          = '['.get_option('wpsc_ticket_alice','').$ticket_id.'] '.$subject;
    $body             = $wpscfunction->replace_macro(get_term_meta($email->term_id,'body',true),$ticket_id);
    $recipients       = get_term_meta($email->term_id,'recipients',true);
    $extra_recipients = get_term_meta($email->term_id,'extra_recipients',true);
    
    $email_addresses = array();
    foreach ($recipients as $recipient) {
      if(is_numeric($recipient)){
        $agents = get_terms([
          'taxonomy'   => 'wpsc_agents',
          'hide_empty' => false,
          'meta_query' => array(
            'relation' => 'AND',
            array(
              'key'     => 'role',
              'value'   => $recipient,
              'compare' => '='
            ),
          ),
        ]);
        foreach ($agents as $agent) {
          $user_id = get_term_meta($agent->term_id,'user_id',true);
          if($user_id){
            $user = get_user_by('id',$user_id);
            $email_addresses[] = $user->user_email;
          }
        }
      } else {
        switch ($recipient) {
          case 'customer':
            $email_addresses[] = $wpscfunction->get_ticket_fields($ticket_id,'customer_email');
            break;
          case 'assigned_agent':
            $email_addresses = array_merge($email_addresses,$wpscfunction->get_assigned_agent_emails($ticket_id));
            break;
        }
      }
    }
    if($extra_recipients) $email_addresses = array_merge($email_addresses,$extra_recipients);
    $email_addresses = array_unique($email_addresses);
    $email_addresses = array_diff($email_addresses,$ignore_emails);
    $email_addresses = apply_filters('wpsc_en_ticket_rating_email_addresses',$email_addresses,$email,$ticket_id);
    $email_addresses = array_values($email_addresses);
    
    $to =  isset($email_addresses[0])? $email_addresses[0] : '';
    if($to){
      unset($email_addresses[0]);
    } else {
      continue; // no email address found to send. So go to next foreach iteration.
    }
    
    $bcc = implode(',',$email_addresses);

    $args = array(
      'ticket_id'     => $ticket_id,
      'from_email'    => $from_email,
      'reply_to'      => $reply_to,
      'email_subject' => $subject,
      'email_body'    => $body,
      'to_email'      => $to,
      'bcc_email'     => $bcc,
      'date_created'  => date("Y-m-d H:i:s"),
      'mail_status'   => 0,
      'email_type'    => 'rating_notification',

    ); 
    
    $wpdb->insert( $wpdb->prefix . 'wpsc_email_notification',$args);
    
    do_action('wpsc_after_rating_notification_mail',$ticket_id,$args);
      
  endif;
  
endforeach;
