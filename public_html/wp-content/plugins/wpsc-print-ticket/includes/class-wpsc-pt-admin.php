<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

if ( ! class_exists( 'WPSC_Print_Tickets_Admin' ) ) :
  
  final class WPSC_Print_Tickets_Admin	 {
    
      public function __construct() {
        add_action( 'admin_enqueue_scripts', array( $this, 'loadScripts') );
      }
      
      public function loadScripts(){
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('wpsc_print_ticket_admin', WPSC_PT_PLUGIN_URL.'asset/js/admin.js?version='.WPSC_PT_VERSION, array('jquery'), null, true);
        
        $loading_html = '<div class="wpsc_loading_icon"><img src="'.WPSC_PLUGIN_URL.'asset/images/ajax-loader@2x.gif"></div>';
        $localize_script_data = apply_filters( 'wpsc_admin_localize_script', array(
            'ajax_url'          => admin_url( 'admin-ajax.php' ),
            'loading_html'      => $loading_html,
        ));
        wp_localize_script( 'wpsc_print_ticket_admin', 'wpsc_print_ticket_data', $localize_script_data );
      }
      
      //Add Print Ticket Pill
      function pt_setting_pill() {
        include_once( WPSC_PT_ABSPATH . 'includes/pt_setting_pill.php' );
      }
      
      // Print Ticket Setting
      function get_pt_settings() {
        include_once( WPSC_PT_ABSPATH . 'includes/get_pt_settings.php' );
        die();
      }
      
      function set_pt_settings() {
        include WPSC_PT_ABSPATH . 'includes/set_pt_settings.php';
        die();
      }
      
      // Reset Default Settings
      function reset_default_pt_settings() {
        include WPSC_PT_ABSPATH . 'includes/reset_default_pt_settings.php';
        die();
      }
       
      // Add Button to Action Bar
      function get_add_btn_action_bar($ticket_id){
        include WPSC_PT_ABSPATH . 'includes/get_add_btn_action_bar.php';
      }
      
      // Print Button
      function print_button($ticket_id){
        global $current_user;
        $wpsc_print_cust_btn_setting = get_option('wpsc_print_cust_btn_setting');
        if($current_user->has_cap('wpsc_agent') || $wpsc_print_cust_btn_setting){
          include WPSC_PT_ABSPATH . 'includes/print_button.php';
        }
      }
      
      function thank_page_print_button($thankyou_html,$ticket_id) {
        global $current_user;
        $wpsc_print_cust_btn_setting = get_option('wpsc_print_cust_btn_setting');
        if($current_user->has_cap('wpsc_agent') || $wpsc_print_cust_btn_setting){
          include WPSC_PT_ABSPATH . 'includes/thank_page_print_button.php';
        }
        return $thankyou_html;
      }
      
      // Download PDF
      function download_pdf(){
        global $wpscfunction;
        if( isset($_REQUEST['wpsc_action']) && $_REQUEST['wpsc_action'] == 'print_ticket' ){
          
          $ticket_id = isset($_REQUEST['ticket_post']) ? intval($_REQUEST['ticket_post']) : 0;
          if(!$ticket_id) die();
          
          $auth_code = isset($_REQUEST['auth_code']) ? sanitize_text_field($_REQUEST['auth_code']) : '';
          if(!$auth_code) die();
          
          $ticket_auth_code = $wpscfunction->get_ticket_fields($ticket_id,'ticket_auth_code');
          if ($ticket_auth_code!=$auth_code) die();
          
          include WPSC_PT_ABSPATH . 'includes/download_pdf.php';
          
        }
      }
      
      //Add-on installed or not for licensing
  		function is_add_on_installed($flag){
  			return true;
  		}
  		
  		// Print license functionlity for this add-on
  		function addon_license_area(){
  			include WPSC_PT_ABSPATH . 'includes/addon_license_area.php';
  		}
  		
  		// Activate SLA license
  		function license_activate(){
  			include WPSC_PT_ABSPATH . 'includes/license_activate.php';
        die();
  		}
  		
  		// Deactivate SLA license
  		function license_deactivate(){
  			include WPSC_PT_ABSPATH . 'includes/license_deactivate.php';
        die();
  		}
      
      // Appearance Settng Pill
      function get_add_appearance_setting_pill() {
        include WPSC_PT_ABSPATH . 'includes/print_ticket_appearance_setting_pill.php';
      }
      
      // Add Appearance Settng
      function get_add_appearance_setting() {
        include_once( WPSC_PT_ABSPATH . 'includes/get_add_appearance_setting.php' );
        die();
      }
      
      function set_appearance_print_ticket() {
        include_once( WPSC_PT_ABSPATH . 'includes/set_appearance_print_ticket.php' );
        die();
      }
      
      function reset_print_ticket_settings() {
        include_once( WPSC_PT_ABSPATH . 'includes/reset_print_ticket_settings.php' );
        die();
      }

      
  }
  
endif;  