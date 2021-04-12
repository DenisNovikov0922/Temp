<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WPSC_Email_Piping_Install' ) ) :
  
  final class WPSC_Email_Piping_Install {

    public function __construct() {
			add_action( 'init', array($this,'register_post_type'), 100 );
		  $this->check_version();
    }
		    		
		// Register post types and texonomies
    public function register_post_type(){
      
      // Register sla texonomy
			$args = array(
          'public'             => false,
          'rewrite'            => false
      );
      register_taxonomy( 'wpsc_ep_rules', 'wpsc_ticket', $args );
      
    }
				
		/**
		 * Check version of WPSC
		 */
		public function check_version(){
				$installed_version = get_option( 'wpsc_ep_current_version', 0 );
				if( $installed_version != WPSC_EP_VERSION ){
						add_action( 'init', array($this,'upgrade'), 101 );
				}

		}
		
		// Upgrade
		public function upgrade(){
			
		  	$installed_version = get_option( 'wpsc_ep_current_version', 0 );
				$installed_version = $installed_version ? $installed_version : 0;
				if ( $installed_version < '1.0.0' ) {
					update_option('wpsc_ep_allowed_user', '1');
				}
				
				if ( $installed_version < '1.0.1' ) {
				   update_option('wpsc_ep_debug_mode', '0');
					 if(get_option('wpsc_ep_refresh_token')){
						 update_option('wpsc_ep_piping_type', 'gmail');
					 } else {
						 update_option('wpsc_ep_piping_type', 'imap');
					 }
				}
				
				if ( $installed_version < '1.0.2' ) {
					$blocked_emails =  get_option('wpsc_ep_block_emails');
					if($blocked_emails){
						$block_emails = explode("\n",$blocked_emails);
						update_option('wpsc_ep_block_emails',$block_emails);
					}
				}
				
				if ( $installed_version < '1.0.8' ) {
					update_option('wpsc_ep_email_type','text');
				}
				
				if ($installed_version < '2.0.1'){
					update_option('wpsc_ep_from_email', '0');
				}
				
				if ($installed_version < '2.0.2'){
					update_option('wpsc_ep_accept_emails', 'all');
					
					$wpsc_ct_warn_email_subject = __("Reply not added",'wpsc-ep');
					update_option('wpsc_ct_warn_email_subject', $wpsc_ct_warn_email_subject);
					
					$wpsc_ct_warn_email_body = __("<p>Hello {customer_name},</p><p>Ticket #{ticket_id} is Closed. Further reply to closed ticket is not allowed.</p><p>Please create new ticket on our website if you need any help.</p>",'wpsc-ep');
					update_option('wpsc_ct_warn_email_body', $wpsc_ct_warn_email_body);
					
				}
				
				if ($installed_version < '2.0.3') {
					update_option('wpsc_add_additional_recepients','0');
				}

				if ($installed_version < '2.0.6'){
					update_option('wpsc_ep_accept_emails', 'all');
					
					$wpsc_close_user_warn_email_subject = __("Not allowed!",'wpsc-ep');
					update_option('wpsc_close_user_warn_email_subject', $wpsc_close_user_warn_email_subject);
					
					$wpsc_close_user_warn_email_body = __("<p> Hello,</p><p>The ticket can not be created for non-registered users.</p>",'wpsc-ep');
					update_option('wpsc_close_user_warn_email_body', $wpsc_close_user_warn_email_body);
					
				}

				
				update_option( 'wpsc_ep_current_version', WPSC_EP_VERSION );				
		}		
  }
endif;

new WPSC_Email_Piping_Install();