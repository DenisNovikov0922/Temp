<?php
use EmailReplyParser\Parser\EmailParser;

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

include_once( WPSC_EP_ABSPATH . 'includes/class-google-connection-pipe.php' );


if ( ! class_exists( 'WPSC_EP_Process_Emails' ) ) :
  
  final class WPSC_EP_Process_Emails {
    
    var $access_token;
    var $user;
    var $historyId;
    var $messege_count = 0;
    var $debug_mode = 0;
    
    public function __construct( $access_token, $user, $historyId ) {
  		
      $this->access_token = $access_token;
      $this->user         = $user;
      $this->historyId    = $historyId;
      $this->debug_mode   = get_option('wpsc_ep_debug_mode','0');
      
      if ($this->debug_mode) {
        echo '==> Started checking new emails. Below is object data:<br>';
        echo '<pre>';
    		print_r($this);
    		echo '<pre>';
      }
      
      $this->process_new_emails();
      
  	}
    
    function process_new_emails(){
      
      $response = wp_remote_post( 'https://www.googleapis.com/gmail/v1/users/'.$this->user.'/history', array(
        'method'      => 'GET',
        'timeout'     => 45,
        'redirection' => 5,
        'httpversion' => '1.0',
        'blocking'    => true,
        'headers'     => array(),
        'body'        => array(
            'access_token'   => $this->access_token,
            'startHistoryId' => $this->historyId,
            'historyTypes' => 'messageAdded',
            'labelId' => 'INBOX',
        ),
        'cookies'     => array()
        )
      );
      
      if (!is_wp_error( $response )){
        
        if ($this->debug_mode){
          echo '==> History checking successful. Below is response:<br>';
      		echo '<pre>';
      		print_r($response['body']);
      		echo '<pre>';
        }
        
        $history = json_decode( $response['body'], true );
        if ( isset($history['history'])) {
          $count = 1;
          foreach ($history['history'] as $history_item) {
            if($count > 5){
        			break;
            }

            update_option('wpsc_ep_historyId',intval($history_item['id']));
            $count++;

            $this->process_messeges_added($history_item['messagesAdded']);
          }
        }
        
        if ($this->debug_mode){
          echo '==> '.$this->messege_count.' Emails imported successfully!<br>';
        }
        
      } else {
        
        if ($this->debug_mode){
          echo '==> History check failed. Below is error messege:<br>';
      		echo '<pre>';
      		print_r($response);
      		echo '<pre>';
      		echo 'Aorting email piping!<br>';
        }
        
      }
      
      update_option('wpsc_ep_last_check', date("Y-m-d H:i:s"));
    }
    
    function process_messeges_added($messeges){
      
      global $wpscepfunction;

      foreach ($messeges as $messege) {
        if(!isset($messege['message'])) return;
        $messege_id = $messege['message']['id'];
        $response = wp_remote_post( 'https://www.googleapis.com/gmail/v1/users/'.$this->user.'/messages/'.$messege_id, array(
          'method'      => 'GET',
          'timeout'     => 45,
          'redirection' => 5,
          'httpversion' => '1.0',
          'blocking'    => true,
          'headers'     => array(),
          'body'        => array(
              'access_token' => $this->access_token,
          ),
          'cookies'     => array()
          )
        );
        
        if (is_wp_error( $response )) {
          
          if ($this->debug_mode){
            echo '==> Messege id '.$messege_id.' failed. Below is error messege:<br>';
        		echo '<pre>';
        		print_r($response);
        		echo '<pre>';
          }
          
          return;
          
        } else {
          
          if ($this->debug_mode){
            echo '==> Messege id '.$messege_id.' successful. Below is response:<br>';
        		echo '<pre>';
        		print_r($response['body']);
        		echo '<pre>';
          }
          
        }
        
        $messege = json_decode( $response['body'], true );
        $payload = $messege['payload'];

        $headers = $payload['headers'];
        
        $google_pipe = new WPSC_Google_Connection_Pipe();
        
        $to_email       = $google_pipe->get_to_email($headers);
        $from_email     = $google_pipe->get_from_email($headers);
        $cc_email       = $google_pipe->get_cc_email($headers);
        $reply_to_email = $google_pipe->get_reply_to_email($headers);
        $from_name      = $google_pipe->get_from_name($headers);
        $subject        = $google_pipe->getHeader($headers, 'Subject');
        $body           = $google_pipe->get_body($payload);
        $attachment_ids = $google_pipe->get_attachments($this->access_token,$this->user,$messege_id,$payload);
        
        $ticket_id = $this->get_ticket_id($subject);
        
        $user = get_user_by( 'email', $from_email);
        $user_id=0;
        if ( ! empty( $user ) ) {
          $user_id=$user->ID;
        }
        
        $args = array(
          'customer_name'      => $from_name,
          'customer_email'     => $reply_to_email ? $reply_to_email : $from_email,
          'to_email'           => $to_email,
          'ticket_subject'     => $subject,
          'ticket_description' => $body,
          'desc_attachment'    => $attachment_ids,
          'user_id'            => $user_id,
          'ticket_id'          => $ticket_id,
          'reply_source'       => 'gmail'
        );
        
        if(!$ticket_id){
          $args['is_reply']=0;
        }else{
          $args['is_reply']=1;
        }

        if ($cc_email) {
          $args['cc_mail'] = $cc_email;
        }

        //if to address is not piping address and user added piping address in cc
        if( get_option('wpsc_add_additional_recepients')=="1" && !$wpscepfunction->check_to_email_is_piping_email( $to_email ) ){
          if( isset($args['cc_mail']) ){
            $args['cc_mail'][] = $to_email;
          }else{
            $args['cc_mail'] = array($to_email);
          }
        }

        if ($this->debug_mode){
          echo '==> Parsing successful. Below is import args:<br>';
          echo '<pre>';
          print_r($args);
          echo '<pre>';
        }
        
        if(!$this->is_allowed($args)) {
          
          if ($this->debug_mode){
            echo '==> Importing this messege not allowed<br>';
          }
          
          continue;
          
        }
        
        $this->process_mail($args);
        
        $this->messege_count ++;
        
      }
    }
    
    function is_allowed($args){
      $is_allowed = true;
      
      //check for block emails
      $block_emails = get_option('wpsc_ep_block_emails');
      if($block_emails){
        foreach ( $block_emails as $block_email ){
  				$block_email = trim($block_email);
  				if($block_email && fnmatch($block_email, $args['customer_email'])){
  						$is_allowed = false;
  						break;
  				}
  		}
      }
      
      
      //check for block subject
      $be_subject            = get_option('wpsc_ep_block_subject');
      $ignore_email_subjects = explode(PHP_EOL, $be_subject);
      foreach ( $ignore_email_subjects as $ignore_email_subject ){

          $ignore_email_subject = trim($ignore_email_subject);
          if($ignore_email_subject && fnmatch($ignore_email_subject, $args['ticket_subject'])){
              $is_allowed = false;
              break;
          }

      }
      
      //check allowed user email setting
      global $wpscfunction,$wpscepfunction;
      if( get_option('wpsc_ep_allowed_user') == 0 && $args['user_id'] == 0 ){
          $is_allowed = false;
          $wpscepfunction->response_mail_close_user($args['customer_email']);
      }
      
      //check for returned mail
      if( $args['is_reply'] == 0 && $this->isReturnedEmail($args) ){
          $is_allowed = false;
      }
      
      //reply to close ticket is not allowed
  		global $wpscfunction,$wpscepfunction;
      $reply_to_close_ticket 			= get_option('wpsc_allow_reply_to_close_ticket');
      $wpsc_reply_to_close_ticket = get_option('wpsc_reply_to_close_ticket'); 
  		$wpsc_close_ticket_status   = get_option('wpsc_close_ticket_status');
      $ticket_id   = $args['ticket_id'];
      if($ticket_id){
        $ticket_data = $wpscfunction->get_ticket($ticket_id);
        $status_id   = $ticket_data['ticket_status'];
        $user      = get_user_by( 'email', $args['customer_email']);
    		if( !in_array('customer', $reply_to_close_ticket) && ($args['user_id']== 0 || !user_can( $user, 'wpsc_agent' )) && ($status_id == $wpsc_close_ticket_status)){
    			$is_allowed = false;
          $wpscepfunction->response_mail_close_ticket($ticket_id, $args['customer_email']);
    		}else if( !in_array('agents', $reply_to_close_ticket) && (user_can( $user, 'wpsc_agent' )) && ($status_id == $wpsc_close_ticket_status)){
          $is_allowed = false;
          $wpscepfunction->response_mail_close_ticket($ticket_id, $args['customer_email']);
        }
        
      }
  		
      //Allowed email types eg new emails,reply emails or all
  		$wpsc_ep_accept_emails = get_option('wpsc_ep_accept_emails');
  		if($ticket_id && !($wpsc_ep_accept_emails=='reply' || $wpsc_ep_accept_emails=='all')){
  			$is_allowed = false;
  		}else if( !$ticket_id && !($wpsc_ep_accept_emails=='new' || $wpsc_ep_accept_emails=='all')){
  			$is_allowed = false;
  		}
      
      return $is_allowed;
    }
    
    public function isReturnedEmail($args){
      
      $flag = false;
        // Check noreply email addresses
        if ( preg_match('/not?[\-_]reply@/i', $args['customer_email']) ){
          $flag = true;
        }

        // Check mailer daemon email addresses
        if ( preg_match('/mail(er)?[\-_]daemon@/i', $args['customer_email']) ){
          $flag = true;
        }

        // Check autoreply subjects
        if ( preg_match('/^[\[\(]?Auto(mat(ic|ed))?[ \-]?reply/i', $args['ticket_subject']) ){
            $flag = true;
        }

        // Check out of office subjects
        if ( preg_match('/^Out of Office/i', $args['ticket_subject']) ){
            $flag = true;
        }

        // Check delivery failed email subjects
        if (
            preg_match('/DELIVERY FAILURE/i', $args['ticket_subject']) ||
            preg_match('/Undelivered Mail Returned to Sender/i', $args['ticket_subject']) ||
            preg_match('/Delivery Status Notification \(Failure\)/i', $args['ticket_subject']) ||
            preg_match('/Returned mail\: see transcript for details/i', $args['ticket_subject'])
        )
        {
            $flag = true;
        }

        // Check Delivery failed message
        if ( preg_match('/postmaster@/i', $args['customer_email']) && preg_match('/Delivery has failed to these recipients/i', $args['ticket_description']) ){
            $flag = true;
        }
        
        $flag = apply_filters('wpsc_ep_whitelist_email',$flag,$args);            

        return $flag;

    }
    
    function get_ticket_id($subject){
      
      $ticket_alice = get_option('wpsc_ticket_alice');
      
      if(strpos($ticket_alice,'$')){
        $ticket_alice = str_replace("$",'\$',$ticket_alice);
        preg_match_all("/".$ticket_alice."[0-9]+/i", $subject, $matches);
      } else{
        preg_match_all("/".$ticket_alice."[0-9]+/i", $subject, $matches);
      }

      $ticket_id = isset($matches[0][0]) ? $matches[0][0] : '';

      if($ticket_id){
        if(strpos($ticket_alice,'\$')){
          $ticket_alice = str_replace('\$',"$",$ticket_alice);
          $ticket_id  = substr($ticket_id, strlen($ticket_alice));
        }else{
          $ticket_id  = substr($ticket_id, strlen($ticket_alice));
        }
      } else {
        $ticket_id = 0;
      }
      
      return $ticket_id;
    }
    
    function process_mail($args){
      
      global $wpscfunction, $wpscepfunction;
      
      $body = $args['ticket_description'];
      
      $accept_mail_type = get_option('wpsc_ep_email_type');
      
      $ticket_description = '';
			
			if( !$ticket_description && $accept_mail_type == 'html' && $body['html'] ){
				$ticket_description = $body['html'];
			} 
			
			if( !$ticket_description && $accept_mail_type == 'html' && !$body['html'] && $body['text'] ){
				if($args['is_reply']==0){
					$ticket_description = nl2br($body['text']);
				} else {
					$email = (new EmailParser())->parse($body['text']);
					$ticket_description = nl2br($email->getVisibleText());
				}
			}
			
			if( !$ticket_description && $accept_mail_type == 'text' && $body['text'] ){
				if($args['is_reply']==0){
					$ticket_description = nl2br($body['text']);
				} else {
					$email = (new EmailParser())->parse($body['text']);
					$ticket_description = nl2br($email->getVisibleText());
				}
			} 
			
			if( !$ticket_description && $accept_mail_type == 'text' && !$body['text'] && $body['html'] ){
				$ticket_description = $body['html'];
			}
			
			if( !$ticket_description ){
				$ticket_description = 'No email body found!';
			}
      
      $args['ticket_description'] = $ticket_description;
      
      $args = apply_filters('wpsc_ep_before_gmail_pipe', $args);

      if($args['is_reply']==0){
        
        $ticket_id = $wpscepfunction->create_ticket_email_piping($args);
        if ($this->debug_mode){
          echo '==> Ticket created successfully. $ticket_id: '.$ticket_id.'<br>';
        }
        
      } else {
        
        $thread_id = $wpscepfunction->create_ticket_reply($args);
        
        if ($this->debug_mode){
          echo '==> Ticket #'.$args['ticket_id'].' replied successfully. $thread_id: '.$thread_id.'<br>';
        }
        
      }
      
    }
    
  }
  
endif;