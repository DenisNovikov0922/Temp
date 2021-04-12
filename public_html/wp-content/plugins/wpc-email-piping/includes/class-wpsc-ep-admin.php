<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

if ( ! class_exists( 'WPSC_EP_Admin' ) ) :
  
  final class WPSC_EP_Admin {
    
      function ep_setting_pill(){
        include_once( WPSC_EP_ABSPATH . 'includes/ep_setting_pill.php' );
      }
      
      function load_settings(){
        include_once( WPSC_EP_ABSPATH . 'includes/settings.php' );
        die();
      }
      
      function set_email_piping_settings(){
        include_once( WPSC_EP_ABSPATH . 'includes/set_email_piping_settings.php' );
        die();
      }
      
      function get_access_token(){
        include_once( WPSC_EP_ABSPATH . 'includes/get_access_token.php' );
      }
      
      function connect_imap(){
        include_once( WPSC_EP_ABSPATH . 'includes/connect_imap.php' );
      }
      
      function import_emails(){
        include_once( WPSC_EP_ABSPATH . 'includes/import_emails.php' );
      }
      
      function set_ep_other_settings(){
        include_once( WPSC_EP_ABSPATH . 'includes/set_ep_other_settings.php' );
        die();
      }
      
      // Add-on installed or not for licensing
  		function is_add_on_installed($flag){
  			return true;
  		}
  		
  		// Print license functionlity for this add-on
  		function addon_license_area(){
  			include WPSC_EP_ABSPATH . 'includes/addon_license_area.php';
  		}
  		
  		// Activate Email Piping license
  		function license_activate(){
  			include WPSC_EP_ABSPATH . 'includes/license_activate.php';
        die();
  		}
  		
  		// Deactivate Email Piping license
  		function license_deactivate(){
  			include WPSC_EP_ABSPATH . 'includes/license_deactivate.php';
        die();
  		}
      
      function get_ep_rules_settings(){
        include_once( WPSC_EP_ABSPATH . 'includes/get_ep_rules_settings.php' );
        die();
      }
      
      function get_ep_rules_form_field(){
       include_once( WPSC_EP_ABSPATH . 'includes/get_ep_rules_form_field.php' );
       die();
      }

      function set_ep_rules_form_field(){
       include_once( WPSC_EP_ABSPATH . 'includes/set_ep_rules_form_field.php' );
       die();
      }

      function get_edit_ep_rules_form_field(){
       include_once( WPSC_EP_ABSPATH . 'includes/get_edit_ep_rules_form_field.php' );
       die();
      }

      function set_edit_ep_rules_form_field(){
       include_once( WPSC_EP_ABSPATH . 'includes/set_edit_ep_rules_form_field.php' );
       die();
      }

      function delete_ep_rules_form_field(){
       include_once( WPSC_EP_ABSPATH . 'includes/delete_ep_rules_form_field.php' );
       die();
      }

      function set_ep_rule_list_order(){
       include_once( WPSC_EP_ABSPATH . 'includes/set_ep_rule_list_order.php' );
       die();
      }

      // change email from email
      function change_email_from_email( $from_email, $ticket_id ){
       
         global $wpscfunction;
         $to_email           = $wpscfunction->get_ticket_meta( $ticket_id, 'to_email', true );
         $wpsc_ep_from_email = get_option('wpsc_ep_from_email');
         if( $wpsc_ep_from_email && $to_email ){
           $from_email = $to_email;
         }
         return $from_email;
       
      }

      /**
       * Adds forwarded from option to condition options
       */
      function add_condition_option( $condition_options ){
        
          $condition_options[] = array(
            'key'         => 'ep_forwarded_from',
            'label'       => __( 'Forwarded From (Email Piping)', 'wpsc-ep' ),
            'has_options' => 0,
          );
          return $condition_options;
        
      }
      
      /**
       * Check email piping related conditions
       */
      function check_ticket_ep_conditions( $inner_flag, $ticket_id, $unique_condition ){
        
          global $wpscfunction;
          $to_email = $wpscfunction->get_ticket_meta( $ticket_id, 'to_email', true );
          foreach ( $unique_condition as $condition){
              
              switch ( $condition->field ){
                
                  case 'ep_forwarded_from':
                      
                    if( $condition->compare == 'match' ) {
                      $inner_flag = $condition->cond_val == $to_email ? true : false;
                    } elseif($condition->compare == 'not_match' ){
                      $inner_flag = $condition->cond_val != $to_email ? true : false;
                    } else {
                      $inner_flag = strpos( $to_email, $condition->cond_val ) !== false ? true : false;
                    }
                    break;
                
              }
              
              if( $inner_flag ) break;
              
          }
          return $inner_flag;
        
      }

      function wpsc_ticket_thread_reply_source($reply_type, $reply_source,$thread_id,$ticket_id){
        if ($reply_source == 'gmail') {
           $reply_type = "Gmail" ;
        } elseif ($reply_source == 'imap') {
          $reply_type = "Imap" ;
        }
        return $reply_type;
      }
      
      function wpsc_after_en_setting_pills(){
        include_once( WPSC_EP_ABSPATH . 'includes/email_notifications/add_en_setting_tab.php' );
      }

      function wpsc_get_ep_email_notifications(){
        include_once( WPSC_EP_ABSPATH . 'includes/email_notifications/wpsc_get_ep_email_notifications.php' );
        die();
      }

      function wpsc_set_ep_en_setting(){
        include_once( WPSC_EP_ABSPATH . 'includes/email_notifications/wpsc_set_ep_en_setting.php' );
        die();
      }

      function add_external_en_setting_scripts(){
        ?>
        <script>
          function wpsc_get_ep_ticket_notifications(){
            jQuery('.wpsc_setting_pills li').removeClass('active');
            jQuery('#wpsc_ep_ticket_notifications').addClass('active');
            jQuery('.wpsc_setting_col2').html(wpsc_admin.loading_html);
  
            var data = {
              action: 'wpsc_get_ep_email_notifications'
            };

            jQuery.post(wpsc_admin.ajax_url, data, function(response) {
              jQuery('.wpsc_setting_col2').html(response);
            });
          }
        </script>
        <?php
      }

      function remove_ep_from_en( $email_addresses,$email,$ticket_id ){
         
        return $this->remove_email_piping_address( $email_addresses );
         
      }

      function remove_ep_from_enotify( $email_addresses,$email,$thread_id,$ticket_id){
          
        return $this->remove_email_piping_address( $email_addresses );

      }

      function remove_email_piping_address( $email_addresses ){
          $all_ep_addresses = array();
          $ep_gmail_address = get_option('wpsc_ep_email_address');
          if( strlen($ep_gmail_address) ){
            $all_ep_addresses[] = $ep_gmail_address;
          }

          $ep_imap_address = get_option('wpsc_ep_imap_email_address');
          if( strlen($ep_imap_address) ){
            $all_ep_addresses[] = $ep_imap_address;
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
              $wpsc_ep_to_address = is_array($wpsc_ep_to_address) ? $wpsc_ep_to_address : array();

              $all_ep_addresses = array_merge($all_ep_addresses, $wpsc_ep_to_address);
          }
          
          $email_addresses = array_diff($email_addresses, $all_ep_addresses);
          
          return $email_addresses;
      }
  }
  
endif;