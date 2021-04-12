<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

final class WPSC_EP_Imap_Mail_Process {

  var $conn;
  var $uid;
  var $header;
  var $from_name;
  var $from_email;
	var $reply_to_email;
  var $to_email;
  var $subject;
  var $text_body      = '';
  var $html_body      = '';
  var $attachment_ids = array();
  var $attachments    = '';
  var $ticket_id      = 0;

  function __construct( $conn, $uid ) {

    $this->conn   = $conn;
    $this->uid    = $uid;
    $this->header = imap_rfc822_parse_headers(imap_fetchheader($this->conn, $this->uid, FT_UID));
    
    $this->get_from_email();
    $this->get_to_email();
		$this->get_reply_to_email();
    $this->get_from_name();
    $this->get_subject();
    $this->process_mail_structure();
    $this->get_ticket_id();
		$this->get_cc_mails();
    $this->attachments = implode(',', $this->attachment_ids);
    
  }

  function get_from_email(){
    $this->from_email = strtolower($this->header->from[0]->mailbox . '@' . $this->header->from[0]->host);
	}
	
	function get_reply_to_email(){
		$this->reply_to_email = strtolower($this->header->reply_to[0]->mailbox . '@' . $this->header->reply_to[0]->host);
	}
  
  function get_to_email(){
    $this->to_email = strtolower($this->header->to[0]->mailbox . '@' . $this->header->to[0]->host);
  }

  function get_from_name(){
    $this->from_name = isset($this->header->from[0]->personal) ? $this->decodeMimeStr($this->header->from[0]->personal) : $this->from_email;
  }

  function get_subject(){
    $this->subject = isset($this->header->subject) ? $this->decodeMimeStr($this->header->subject) : '';
  }

  function process_mail_structure(){
    $mailStructure = imap_fetchstructure($this->conn, $this->uid, FT_UID);
    if(empty($mailStructure->parts)) {
			$this->initMailPart($mailStructure, 0);
		}
		else {
			foreach($mailStructure->parts as $partNum => $partStructure) {
				$this->initMailPart($partStructure, $partNum + 1);
			}
		}
  }
  
  protected function initMailPart( $partStructure, $partNum, $markAsSeen = true) {

    global $wpdb, $wpsupportplus, $current_user;

    $options = FT_UID;
		if(!$markAsSeen) {
			$options |= FT_PEEK;
		}
		if($partNum) {
			$data = $this->imap('fetchbody', [$this->uid, $partNum, $options]);
		}
		else {
			$data = $this->imap('body', [$this->uid, $options]);
		}
		if($partStructure->encoding == 1) {
			$data = imap_utf8($data);
		}
		elseif($partStructure->encoding == 2) {
			$data = imap_binary($data);
		}
		elseif($partStructure->encoding == 3) {
			$data = preg_replace('~[^a-zA-Z0-9+=/]+~s', '', $data); // https://github.com/barbushin/php-imap/issues/88
			$data = imap_base64($data);
		}
		elseif($partStructure->encoding == 4) {
			$data = quoted_printable_decode($data);
		}
		$params = [];
		if(!empty($partStructure->parameters)) {
			foreach($partStructure->parameters as $param) {
				$params[strtolower($param->attribute)] = $this->decodeMimeStr($param->value);
			}
		}
		if(!empty($partStructure->dparameters)) {
			foreach($partStructure->dparameters as $param) {
				$paramName = strtolower(preg_match('~^(.*?)\*~', $param->attribute, $matches) ? $matches[1] : $param->attribute);
				if(isset($params[$paramName])) {
					$params[$paramName] .= $param->value;
				}
				else {
					$params[$paramName] = $param->value;
				}
			}
		}
		$isAttachment = $partStructure->ifid || isset($params['filename']) || isset($params['name']);
		// ignore contentId on body when mail isn't multipart (https://github.com/barbushin/php-imap/issues/71)
		if(!$partNum && TYPETEXT === $partStructure->type) {
			$isAttachment = false;
		}
		if($isAttachment) {

			if($partStructure->type == 0){ //if there is attachment and text in body 

				if(!empty($params['charset'])) {
					$data = $this->convertStringEncoding($data, $params['charset'], 'utf-8');
				}
				if($partStructure->type == 0 && $data) {
					if(strtolower($partStructure->subtype) == 'plain') {
						$this->text_body .= $data;
					}
					else {
						$this->html_body .= $data;
					}
				}
				elseif($partStructure->type == 2 && $data) {
					$this->text_body .= trim($data);
				}
			}

      		$attachmentId = mt_rand() . mt_rand();
			if(empty($params['filename']) && empty($params['name'])) {
				$fileName = $attachmentId . '.' . strtolower($partStructure->subtype);
			}
			else {
				$fileName = !empty($params['filename']) ? $params['filename'] : $params['name'];
				$fileName = $this->decodeMimeStr($fileName);
				$fileName = $this->decodeRFC2231($fileName);
			}
      
      $replace = [
        '/\s/' => '_',
        '/[^0-9a-zа-яіїє_\.]/iu' => '',
        '/_+/' => '_',
        '/(^_)|(_$)/' => '',
      ];
      $file_name = preg_replace('~[\\\\/]~', '', time() . '_' . preg_replace(array_keys($replace), $replace, $fileName));
      $isError = false;
      $tempExtension = explode('.', $file_name);
      $extension     = strtolower($tempExtension[count($tempExtension)-1]);
			
			$wpsc_allow_attachment_type = get_option('wpsc_allow_attachment_type');
			$wpsc_attachment_type       = explode(',',$wpsc_allow_attachment_type);
			$wpsc_attachment_type       = array_map('trim', $wpsc_attachment_type);
			$wpsc_attachment_type       = array_map('strtolower', $wpsc_attachment_type);
			
			if (!(in_array($extension,$wpsc_attachment_type))) {
				$isError = true;
			}
      switch ($extension){
        case 'exe':
        case 'php':
        case 'js':
          $isError = true;
          break;
      }
      if( !$isError ){
        $attachment_count = get_option('wpsc_attachment_count');
        if(!$attachment_count) $attachment_count = 1;
        $term = wp_insert_term( 'attachment_'.$attachment_count, 'wpsc_attachment' );
        if(!$term || is_wp_error($term)) die();
		if (!is_wp_error($term) && isset($term['term_id'])) {
			update_option('wpsc_attachment_count',++$attachment_count);
	        
			$now   = date("Y-m-d H:i:s");
			$time  = strtotime($now);
			$month = date("m",$time);
			$year  = date("Y",$time);
		
			$upload_dir = wp_upload_dir();
			if (!file_exists($upload_dir['basedir'] . '/wpsc/'.$year)) {
				mkdir($upload_dir['basedir'] . '/wpsc/'.$year, 0755, true);
			}
			if (!file_exists($upload_dir['basedir'] . '/wpsc/'.$year.'/'.$month)) {
				mkdir($upload_dir['basedir'] . '/wpsc/'.$year.'/'.$month, 0755, true);
			}
	        
	        add_term_meta ($term['term_id'], 'filename', $fileName);

	        $save_file_name = str_replace(' ','_',$file_name);
	        $save_file_name = str_replace(',','_',$file_name);
	        $save_file_name = explode('.', $save_file_name);
	        
	        $img_extensions = array('png','jpeg','jpg','bmp','PNG','JPEG','JPG','BMP');
	        $extension      = $save_file_name[count($save_file_name)-1];
	        if(!in_array($extension, $img_extensions)){
	          $extension = $extension.'.txt';
	          add_term_meta ($term['term_id'], 'is_image', '0');
	        } else {
	          add_term_meta ($term['term_id'], 'is_image', '1');
	        }
	        
	        unset( $save_file_name[count($save_file_name)-1] );

	        $save_file_name = implode('-', $save_file_name);

	        $save_file_name = time().'_'.preg_replace('/[^A-Za-z0-9\-]/', '', $save_file_name).'.'.$extension;
			
			$save_directory = $upload_dir['basedir'] . '/wpsc/'.$year.'/'.$month.'/'.$save_file_name;

	        $myfile = fopen($save_directory, "w+");
	        fwrite($myfile, $data);
	        fclose($myfile);
	        
	        add_term_meta ($term['term_id'], 'save_file_name', $save_file_name);
	        add_term_meta ($term['term_id'], 'active', '0');
			add_term_meta ($term['term_id'], 'time_uploaded', $now);
			add_term_meta ($term['term_id'], 'is_restructured', 1);

	        $this->attachment_ids[] = $term['term_id'];
		}
        
      }

		} else {

			if(!empty($params['charset'])) {
				$data = $this->convertStringEncoding($data, $params['charset'], 'utf-8');
			}
			if($partStructure->type == 0 && $data) {
				if(strtolower($partStructure->subtype) == 'plain') {
					$this->text_body .= $data;
				}
				else {
					$this->html_body .= $data;
				}
			}
			elseif($partStructure->type == 2 && $data) {
				$this->text_body .= trim($data);
			}

		}

    if(!empty($partStructure->parts)) {
			foreach($partStructure->parts as $subPartNum => $subPartStructure) {
				if($partStructure->type == 2 && $partStructure->subtype == 'RFC822' && (!isset($partStructure->disposition) || $partStructure->disposition !== "attachment")) {
					$this->initMailPart($subPartStructure, $partNum, $markAsSeen);
				}
				else {
					$this->initMailPart($subPartStructure, $partNum . '.' . ($subPartNum + 1), $markAsSeen);
				}
			}
		}

	}

  protected function decodeMimeStr($string, $toCharset = 'utf-8') {

    $newString = '';
		foreach(imap_mime_header_decode($string) as $element) {
			if(isset($element->text)) {
				$fromCharset = !isset($element->charset) || $element->charset == 'default' ? 'iso-8859-1' : $element->charset;
				$newString .= $this->convertStringEncoding($element->text, $fromCharset, $toCharset);
			}
		}
		return $newString;

  }

  protected function convertStringEncoding($string, $fromEncoding, $toEncoding) {

    if(!$string || $fromEncoding == $toEncoding) {
			return $string;
		}
		$convertedString = function_exists('iconv') ? @iconv($fromEncoding, $toEncoding . '//IGNORE', $string) : null;
		if(!$convertedString && extension_loaded('mbstring')) {
			$convertedString = @mb_convert_encoding($string, $toEncoding, $fromEncoding);
		}
		if(!$convertedString) {
			throw new Exception('Mime string encoding conversion failed');
		}
		return $convertedString;

	}
	
	protected function isUrlEncoded($string)
    {
        $hasInvalidChars = preg_match('#[^%a-zA-Z0-9\-_\.\+]#', $string);
        $hasEscapedChars = preg_match('#%[a-zA-Z0-9]{2}#', $string);
        return !$hasInvalidChars && $hasEscapedChars;
    }
	
  protected function decodeRFC2231($string, $charset = 'utf-8') {
		if(preg_match("/^(.*?)'.*?'(.*?)$/", $string, $matches)) {
			$encoding = $matches[1];
			$data = $matches[2];
			if($this->isUrlEncoded($data)) {
				$string = $this->convertStringEncoding(urldecode($data), $encoding, $charset);
			}
		}
		return $string;
	}

  public function imap($methodShortName, $args = [], $prependConnectionAsFirstArg = true) {
		if(!is_array($args)) {
			$args = [$args];
		}
		foreach($args as &$arg) {
			if(is_string($arg)) {
				$arg = imap_utf7_encode($arg);
			}
		}
		if($prependConnectionAsFirstArg) {
			array_unshift($args, $this->conn);
		}
		imap_errors(); // flush errors
		$result = @call_user_func_array("imap_$methodShortName", $args);
		if(!$result) {
			return false;
		}
		return $result;
	}
  
  function get_ticket_id(){
    
    $ticket_alice = get_option('wpsc_ticket_alice');
    
    if(strpos($ticket_alice,'$')){
      $ticket_alice = str_replace("$",'\$',$ticket_alice);
      preg_match_all("/".$ticket_alice."[0-9]+/i", $this->subject, $matches);
    } else{
      preg_match_all("/".$ticket_alice."[0-9]+/i", $this->subject, $matches);
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
    
    $this->ticket_id = $ticket_id;
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
	global $wpscepfunction;	
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
		$wpsc_reply_to_close_ticket = get_option('wpsc_reply_to_close_ticket'); 
		$reply_to_close_ticket 			= get_option('wpsc_allow_reply_to_close_ticket');
		$wpsc_close_ticket_status   = get_option('wpsc_close_ticket_status');
		$ticket_id                  = $args['ticket_id'];
		// $post_id                    = $wpscfunction->get_ticket_post_id($args['ticket_id']);
		if($ticket_id){
			$ticket_data = $wpscfunction->get_ticket($ticket_id);
			$status_id   = $ticket_data['ticket_status']; 
			$user 		= get_user_by( 'email', $args['customer_email']);
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
	
	public function get_cc_mails(){
		if (isset($this->header->cc)) {
			foreach ($this->header->cc as $cc_mail) {
				$this->cc_mail[] = strtolower($cc_mail->mailbox . '@' . $cc_mail->host);
			}
		}
	}

}
