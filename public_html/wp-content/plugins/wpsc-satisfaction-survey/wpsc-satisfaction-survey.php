<?php 
/**
 * Plugin Name: SupportCandy - Satisfaction Survey
 * Plugin URI: https://supportcandy.net/
 * Description: Customers can rate your agents performance for the ticket.
 * Version: 2.0.9
 * Author: Support Candy
 * Author URI: https://supportcandy.net/
 * Requires at least: 4.4
 * Tested up to: 4.9
 * Text Domain: wpsc-sf
 * Domain Path: /lang
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Support_Candy_SF' ) ) :
  
  final class Support_Candy_SF {
    
    public $version = '2.0.9';
    
    public function __construct() {
    
		  $this->define_constants();
			$this->includes();
			add_action( 'init', array($this,'load_textdomain') );
			register_activation_hook(__FILE__,array($this,'activation'));
			register_deactivation_hook( __FILE__, array($this,'deactivate') );
			
		}
    
    function define_constants() {
      $this->define('WPSC_SF_PLUGIN_FILE', __FILE__);
      $this->define('WPSC_SF_ABSPATH', dirname(__FILE__) . '/');
      $this->define('WPSC_SF_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
      $this->define('WPSC_SF_PLUGIN_BASENAME', plugin_basename(__FILE__));
			$this->define('WPSC_SF_STORE_ID', '602');
			$this->define('WPSC_SF_VERSION', $this->version);			
    }
    
    function load_textdomain(){
      $locale = apply_filters( 'plugin_locale', get_locale(), 'wpsc-sf' );
      load_textdomain( 'wpsc', WP_LANG_DIR . '/wpsc/wpsc-sf-' . $locale . '.mo' );
      load_plugin_textdomain( 'wpsc-sf', false, plugin_basename( dirname( __FILE__ ) ) . '/lang' );
    }
    
    public function includes() {
      
      include_once( WPSC_SF_ABSPATH . 'class-wpsc-sf-install.php' );
      include_once( WPSC_SF_ABSPATH . 'class-wpsc-sf.php' );
      $sf = new WPSC_SF();
      
      // Setting
      add_action( 'wpsc_after_setting_pills', array($sf,'sf_setting_pill'));
			add_action( 'wp_ajax_wpsc_sf_save_settings', array($sf,'save_settings'));
      add_action( 'wp_ajax_wpsc_get_sf_settings', array($sf,'get_sf_settings'));
      add_action( 'wp_ajax_wpsc_set_rating_order', array($sf,'set_rating_order'));
      add_action( 'wp_ajax_wpsc_get_add_rating', array($sf,'get_add_rating'));
      add_action( 'wp_ajax_wpsc_set_add_rating', array($sf,'set_add_rating'));
      add_action( 'wp_ajax_wpsc_get_edit_rating', array($sf,'get_edit_rating'));
      add_action( 'wp_ajax_wpsc_set_edit_rating', array($sf,'set_edit_rating'));
	  add_action( 'wp_ajax_wpsc_delete_rating', array($sf,'delete_rating'));
	  add_filter('wpsc_admin_localize_script', array($sf, 'wpsc_admin_localize_script'));

			//add_action( 'wpsc_add_ticket_stat_graph', array($sf,'satisfaction_survey_graph'));
	  		// Macro
			// add_action('wpsc_after_macro_templates', array($sf,'add_macro_template'));
			add_filter('wpsc_replace_macro', array($sf, 'replace_macro'), 10, 3);
			
			// Shortcode
			if ((!is_admin() || defined('DOING_AJAX') ) && !defined('DOING_CRON')) {
				add_shortcode( 'wpsc_sf', array( $sf, 'shortcode' ) );
			}
			
			// Set more feedback
			add_action( 'wp_ajax_wpsc_sf_set_more_feedback', array($sf,'set_more_feedback'));
			
			// Print feedback thread
			add_action('wpsc_print_thread_type', array($sf, 'print_feedback_thread'), 10, 2);
			
			// Print rating widget
			add_action('wpsc_add_ticket_widget', array($sf, 'print_rating_widget'), 10, 3);
			
			// Ticket List
			add_action('wpsc_print_default_tl_field', array($sf, 'print_ticket_list_item'));
			
			// Filter functionality
			add_filter('wpsc_search_filter_query', array($sf, 'filter_search'), 10, 2);
			add_filter('wpsc_filter_autocomplete', array($sf, 'filter_autocomplete'), 10, 3);
			add_filter('wpsc_filter_val_label', array($sf, 'filter_val_label'), 10, 2);
			
			// check change status
			add_action('wpsc_set_change_status', array($sf, 'status_check'), 10, 2);
			
			// Email Notifications
			add_filter('wpsc_en_types', array($sf, 'notification_types'));
			add_action('wpsc_submit_ticket_rating', array($sf, 'rating_notification'));
			add_action('wpsc_sf_submit_feedback', array($sf, 'feedback_notification'), 10, 2);
			
			// Cron
			add_action('wpsc_cron', array($sf, 'sf_cron'));
			
			//Export Data
			add_filter('wpsc_sf_rating_ticket_fields', array($sf ,'wpsc_sf_rating_ticket_fields' ),10,3);
			
			/**
			 * add Meta Key
			 */
			 add_filter('wpsc_get_all_meta_keys', array($sf,'wpsc_get_all_meta_keys'),10,1);
			 add_action('wpsc_after_individual_ticket',array($sf,'wpsc_after_individual_ticket'),10,1);
			 add_action('wp_ajax_wpsc_sf_get_ratings',array($sf,'wpsc_sf_get_ratings')) ;
			 add_action('wp_ajax_wpsc_add_sf_rating',array($sf,'wpsc_add_sf_rating'));
			 add_action('wpsc_set_change_status',array($sf,'wpsc_set_change_ticket_status'),10,3);
			 add_action('wp_ajax_wpsc_sf_set_feedback',array($sf,'set_more_feedback'));
			 
			 // Reports
			 add_action('wpsc_report_sub_menu' , array($sf,'satisfaction_survey_graph'));
			 add_action('wp_ajax_get_ratings_report',array($sf,'get_ratings_report'));
			 add_action('wp_ajax_sf_reports_bt_filter',array($sf,'sf_reports_bt_filter'));
			 add_action('wpsc_after_custom_fields_pie_chart',array($sf,'sf_pie_chart'));
			 add_action('wpsc_allowed_report_on_dash_settings',array($sf,'print_sf_checkbox'));
			 
			// License
			add_filter( 'wpsc_is_add_on_installed', array($sf,'is_add_on_installed'));
			add_action( 'wpsc_addon_license_area', array($sf,'addon_license_area'));
			add_action( 'wp_ajax_wpsc_sf_activate_license', array($sf,'license_activate'));
			add_action( 'wp_ajax_wpsc_sf_deactivate_license', array($sf,'license_deactivate'));
			add_action( 'admin_init', array($this, 'plugin_updator'));

			//Email notification
			add_action('wpsc_after_en_setting_pills',array($sf,'after_en_setting_pills'));
			add_action( 'wp_ajax_wpsc_sf_email_notification_settings', array($sf,'email_notification_settings'));
			add_action('wp_ajax_wpsc_set_sf_email_notification_settings',array($sf,'set_sf_email_notification_settings'));
		} 
		
		function activation(){
			//add rating widget at installtion
			$agent_role_ids = array();
			$agent_role = get_option('wpsc_agent_role');
			if(is_array($agent_role)) {
				foreach ($agent_role as $key => $agent) {
					$agent_role_ids[] = $key;
				}
			}
			
			$customer_access= array();
			$customer_access = $agent_role_ids;
			$customer_access[] = 'customer';
		
			$term = wp_insert_term('Rating', 'wpsc_ticket_widget' );
			if (!is_wp_error($term) && isset($term['term_id'])) {
					add_term_meta ($term['term_id'], 'wpsc_label', __('Rating','wpsc-sf'));
					add_term_meta ($term['term_id'], 'wpsc_ticket_widget_load_order', '5');
					add_term_meta($term['term_id'], 'wpsc_ticket_widget_type', '1');
					add_term_meta($term['term_id'],'wpsc_ticket_widget_role', $customer_access);
					$wpsc_custom_widget_localize = get_option('wpsc_custom_widget_localize');
        	$wpsc_custom_widget_localize['custom_widget_'.$term['term_id']] = get_term_meta($term['term_id'], 'wpsc_label', true);
        	update_option('wpsc_custom_widget_localize', $wpsc_custom_widget_localize);
			}

			$rating_field = get_term_by( 'slug', 'sf_rating', 'wpsc_ticket_custom_fields' );
			if($rating_field){
				update_term_meta ($rating_field->term_id, 'wpsc_allow_ticket_list', '1');
				update_term_meta ($rating_field->term_id, 'wpsc_customer_ticket_list_status', '0');
				update_term_meta ($rating_field->term_id, 'wpsc_agent_ticket_list_status', '0');
				update_term_meta ($rating_field->term_id, 'wpsc_allow_ticket_filter', '1');
				update_term_meta ($rating_field->term_id, 'wpsc_agent_ticket_filter_status', '0');
				update_term_meta ($rating_field->term_id, 'wpsc_customer_ticket_filter_status', '0');
			}

			$wpsc_export_ticket_list = get_option('wpsc_export_ticket_list');
			if($wpsc_export_ticket_list){
				array_push($wpsc_export_ticket_list,'sf_rating');
				update_option('wpsc_export_ticket_list',$wpsc_export_ticket_list);	
			}
		}
		
		/*
     * This will be called while plugin deactivation
     * You can write code for removing something after plugin deactivate etc.
     */
    function deactivate(){
        include( WPSC_SF_ABSPATH.'class-wpsc-sf-uninstall.php' );
    }
    
		function plugin_updator(){
			$license_key    = get_option('wpsc_sf_license_key','');
			$license_expiry = get_option('wpsc_sf_license_expiry','');
			if ( class_exists('Support_Candy') && $license_key && $license_expiry ) {
				$edd_updater = new EDD_SL_Plugin_Updater( WPSC_STORE_URL, __FILE__, array(
								'version' => WPSC_SF_VERSION,
								'license' => $license_key,
								'item_id' => WPSC_SF_STORE_ID,
								'author'  => 'Pradeep Makone',
								'url'     => site_url()
				) );
			}	
		}
		
    private function define($name, $value) {
      if (!defined($name)) {
        define($name, $value);
      }
    }
    
  }
  
endif;

new Support_Candy_SF();