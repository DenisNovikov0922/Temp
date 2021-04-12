<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WPSC_Google_Connection_Pipe' ) ) :

    final class WPSC_Google_Connection_Pipe {

        public function getHeader($headers, $name) {
					foreach ($headers as $header) {
						if ($header['name'] == $name) {
							return $header['value'];
            }
          }
        }

        public function decodeBody($body) {
          $rawData = $body;
          $sanitizedData = strtr($rawData,'-_', '+/');
          $decodedMessage = base64_decode($sanitizedData);
          if(!$decodedMessage){
              $decodedMessage = FALSE;
          }
          return $decodedMessage;
        }
				
				public function get_to_email($headers){
					$text = $this->getHeader($headers, 'To');
          preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $text, $matches);
					$to_email = '';
					if($matches){
              $to_email = $matches[0][0];
          }
					return $to_email;
        }

        public function get_from_email($headers){
					$text = $this->getHeader($headers, 'From');
          preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $text, $matches);
					if(!$matches){
              $text = $this->getHeader($headers, 'Authentication-Results');
              preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $text, $matches);
          }
					return $matches[0][0];
        }
				
				public function get_reply_to_email($headers){
					$text = $this->getHeader($headers, 'Reply-To');
          preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $text, $matches);
					$reply_to_email = '';
					if(isset($matches[0][0])){
						$reply_to_email = $matches[0][0];
					}
					return $reply_to_email;
        }

        public function get_from_name($headers){
					$name = $this->getHeader($headers, 'From');
          $name = explode('<', $name);
					if( !trim($name[0]) ){
  					$email = $this->get_from_email($headers);
						$name = explode('@', $email);
          }
					return str_replace('"', '', $name[0]);
        }


        function get_body($payload) {

            $FOUND_BODY = array(
							'text' => '',
							'html' => ''
						);

            $parts = isset($payload['parts']) ? $payload['parts'] : array() ;

            foreach ($parts as $part) {

                if ( $part['mimeType'] === 'text/html' && $part['body'] ) {
                    $FOUND_BODY['html'] = $this->decodeBody($part['body']['data']);
                    break;
                }

                if( isset($part['parts']) ){

                    foreach ( $part['parts'] as $p ){

                        if ( $p['mimeType'] === 'text/html' && $p['body'] ) {
                            $FOUND_BODY['html'] = $this->decodeBody($p['body']['data']);
                            break;
                        }

                        if($FOUND_BODY['html']) {
                            break;
                        }
                    }

                }

                if($FOUND_BODY['html']) {
                    break;
                }
            }

						foreach ($parts as $part) {

								if ( $part['mimeType'] === 'text/plain' && $part['body']) {
										$FOUND_BODY['text'] = $this->decodeBody($part['body']['data']);
										break;
								}

								if( isset($part['parts']) ){

										foreach ( $part['parts'] as $p ){

												if ( $p['mimeType'] === 'text/plain' && $p['body'] ) {
														$FOUND_BODY['text'] = $this->decodeBody($p['body']['data']);
														break;
												}

												if($FOUND_BODY['text']) {
														break;
												}
										}

								}
								
						}

            if( !$FOUND_BODY['text'] && !$FOUND_BODY['html'] ){

                $body = $payload['body'];

                $body_content = isset($body['data']) ? $this->decodeBody($body['data']) : '';

                if( !( strpos($body_content, '<html') > -1 || strpos($body_content, '<body') > -1 ) ){
                  $FOUND_BODY['text'] = $body_content;
                } else {
									$FOUND_BODY['html'] = $body_content;
								}

            }

            return $FOUND_BODY;
        }

        public function get_attachments( $access_token,$user,$messege_id,$payload ){

            $attachment_ids = array();

            $parts = isset($payload['parts']) ? $payload['parts'] : array() ;

            foreach ($parts as $part) {

                if (isset($part['filename']) && $part['filename']) {

                    $file_name      = $part['filename'];
                    $mime_type      = $part['mimeType'];
                    $attachmentId   = $part['body']['attachmentId'];
										
										$response = wp_remote_post( 'https://www.googleapis.com/gmail/v1/users/'.$user.'/messages/'.$messege_id.'/attachments/'.$attachmentId, array(
						          'method'      => 'GET',
						          'timeout'     => 45,
						          'redirection' => 5,
						          'httpversion' => '1.0',
						          'blocking'    => true,
						          'headers'     => array(),
						          'body'        => array(
						              'access_token' => $access_token,
						          ),
						          'cookies'     => array()
						          )
						        );
										
										if (is_wp_error( $response )) return;
						        $attachment = json_decode( $response['body'], true );
										
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
									    
									    add_term_meta ($term['term_id'], 'filename', $file_name);

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

										$data = strtr($attachment['data'], array('-' => '+', '_' => '/'));
										$myfile = fopen($save_directory, "w+");;
										fwrite($myfile, base64_decode($data));
										fclose($myfile);
									    
									    add_term_meta ($term['term_id'], 'save_file_name', $save_file_name);
									    add_term_meta ($term['term_id'], 'active', '0');
										add_term_meta ($term['term_id'], 'time_uploaded', $now);
										add_term_meta ($term['term_id'], 'is_restructured', 1);

									    $attachment_ids[] = $term['term_id'];
									}
										
								}
							}
							
							return $attachment_ids;
							
					}
    		
				public function get_cc_email($headers){
					$text = $this->getHeader($headers, 'Cc');
					preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $text, $matches);
					$cc_email = array();
					if($matches){
						foreach ($matches[0] as $match) {
						$cc_email[] = $match;
						}
					}
					return $cc_email;
				}
		}

endif;
