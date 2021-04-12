<?php
/**
 * Plugin Name: SupportCandy - Print Tickets
 * Plugin URI:  https://supportcandy.net/
 * Description: Print TIcket add-on for SupportCandy
 * Version: 1.0.5
 * Author: Support Candy
 * Author URI:  https://supportcandy.net/
 * Text Domain: wpsc-pt
 * Domain Path: /lang
 */
 
 if ( ! defined( 'ABSPATH' ) ) {
 	exit; // Exit if accessed directly
 }

 final class WPSC_Print_Tickets {
   
   public $version = '1.0.5';

   public function __construct() {
       $this->define_constants();
       $this->includes();
       add_action( 'init', array($this,'load_textdomain') );
   }
   
   function define_constants() {
       define('WPSC_PT_PLUGIN_FILE', __FILE__);
       define('WPSC_PT_ABSPATH', dirname(__FILE__) . '/');
       define('WPSC_PT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
       define('WPSC_PT_PLUGIN_BASENAME', plugin_basename(__FILE__));
       define('WPSC_PT_STORE_ID', '16152');
       define('WPSC_PT_VERSION', $this->version);
   }
   
   function load_textdomain(){
 			$locale = apply_filters( 'plugin_locale', get_locale(), 'wpsc-pt' );
 			load_textdomain( 'wpsc-pt', WP_LANG_DIR . '/wpsc/wpsc-pt-' . $locale . '.mo' );
 			load_plugin_textdomain( 'wpsc-pt', false, plugin_basename( dirname( __FILE__ ) ) . '/lang' );
 	}
  
  public function includes() {
      
      include_once( WPSC_PT_ABSPATH . 'class-wpsc-print_ticket-install.php' );
    	include_once( WPSC_PT_ABSPATH . 'includes/class-wpsc-pt-admin.php' );
      
      $admin = new WPSC_Print_Tickets_Admin	();
  		if ($this->is_request('admin')) {
        
        // Setting
        add_action( 'wpsc_after_setting_pills', array($admin,'pt_setting_pill'));
        add_action( 'wp_ajax_wpsc_get_pt_settings', array($admin,'get_pt_settings'));
        add_action( 'wp_ajax_wpsc_set_pt_settings', array($admin,'set_pt_settings'));
        add_action( 'wp_ajax_wpsc_reset_default_pt_settings', array($admin,'reset_default_pt_settings'));
        
        // Print button
        add_action('wpsc_after_indidual_ticket_action_btn', array($admin,'print_button'));
        add_action('wpsc_after_guest_indidual_ticket_action_btn', array($admin,'print_button'));
        add_filter('wpsc_after_thankyou_page_button', array($admin,'thank_page_print_button'), 10, 2);
        
        // Add button to action bar
        add_action( 'wpsc_add_action_btn_individual_ticket', array($admin,'get_add_btn_action_bar'));
        
        //Appearance Setting
        add_action( 'wpsc_after_appearance_setting_pills', array($admin,'get_add_appearance_setting_pill'));
        add_action( 'wp_ajax_wpsc_get_pt_appearance_settings', array($admin,'get_add_appearance_setting'));
        add_action( 'wp_ajax_wpsc_set_appearance_print_ticket', array($admin,'set_appearance_print_ticket'));
        add_action( 'wp_ajax_wpsc_reset_print_ticket_settings', array($admin,'reset_print_ticket_settings'));
        
        // License
  			add_filter( 'wpsc_is_add_on_installed', array($admin,'is_add_on_installed'));
  			add_action( 'wpsc_addon_license_area', array($admin,'addon_license_area'));
  			add_action( 'wp_ajax_wpsc_pt_activate_license', array($admin,'license_activate'));
  			add_action( 'wp_ajax_wpsc_pt_deactivate_license', array($admin,'license_deactivate'));
  			add_action( 'admin_init', array($this, 'plugin_updator'));

      }
      
      add_action( 'init', array($admin,'download_pdf'), 1000);
      
  }
  
  private function is_request($type) {
     switch ($type) {
         case 'admin' :
             return is_admin();
         case 'frontend' :
             return (!is_admin() || defined('DOING_AJAX') ) && !defined('DOING_CRON');
     }
  }
  
  private function define($name, $value) {
    if (!defined($name)) {
      define($name, $value);
    }
  }

  
  function plugin_updator(){
    $license_key    = get_option('wpsc_pt_license_key','');
    $license_expiry = get_option('wpsc_pt_license_expiry','');
    if(class_exists('Support_Candy') && $license_key && $license_expiry ) {
      $edd_updater = new EDD_SL_Plugin_Updater(WPSC_STORE_URL, __FILE__, array(
              'version' => WPSC_PT_VERSION,
              'license' => $license_key,
              'item_id' => WPSC_PT_STORE_ID,
              'author'  => 'Pradeep Makone',
              'url'     => site_url()
      ) );
    }	
  }
  

}

new WPSC_Print_Tickets();