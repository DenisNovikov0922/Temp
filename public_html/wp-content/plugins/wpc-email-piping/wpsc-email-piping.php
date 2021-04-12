<?php 
/**
 * Plugin Name: SupportCandy - Email Piping
 * Plugin URI:  https://supportcandy.net/
 * Description: Email Piping add-on for SupportCandy
 * Version: 2.1.2
 * Author: Support Candy
 * Author URI:  https://supportcandy.net/
 * Text Domain: wpsc-ep
 * Domain Path: /lang
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

final class WPSC_Email_Piping {
	
	public $version = '2.1.2';
	
	public function __construct() {
			$this->define_constants();
			$this->includes();
			add_action( 'init', array($this,'load_textdomain') );
	}
	
	function define_constants() {
			$this->define('WPSC_EP_PLUGIN_FILE', __FILE__);
			$this->define('WPSC_EP_ABSPATH', dirname(__FILE__) . '/');
			$this->define('WPSC_EP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			$this->define('WPSC_EP_PLUGIN_BASENAME', plugin_basename(__FILE__));
			$this->define('WPSC_EP_STORE_ID', '117');
			$this->define('WPSC_EP_VERSION', $this->version);
	}
	
	function load_textdomain(){
			$locale = apply_filters( 'plugin_locale', get_locale(), 'wpsc-ep' );
			load_textdomain( 'wpsc-ep', WP_LANG_DIR . '/wpsc/wpsc-ep-' . $locale . '.mo' );
			load_plugin_textdomain( 'wpsc-ep', false, plugin_basename( dirname( __FILE__ ) ) . '/lang' );
	}
	
	public function includes() {
		
		include_once( WPSC_EP_ABSPATH . 'vendor/autoload.php' );
		include_once( WPSC_EP_ABSPATH . 'class-wpsc-ep-install.php' );
		include_once( WPSC_EP_ABSPATH . 'includes/class-wpsc-ep-admin.php' );
		include_once( WPSC_EP_ABSPATH . 'class-wpsc-ep-function.php' );
		
		$admin = new WPSC_EP_Admin();
		if ($this->is_request('admin')) {
			// Show seetings
			add_action('wpsc_after_setting_pills', array($admin,'ep_setting_pill'));
			add_action('wp_ajax_wpsc_get_ep_settings', array($admin,'load_settings'));
			add_action('wp_ajax_wpsc_set_ep_settings', array($admin, 'set_email_piping_settings'));
			add_action('wp_ajax_wpsc_set_ep_other_settings', array($admin, 'set_ep_other_settings'));
			// Process Connection 
			add_action('admin_init', array($admin, 'get_access_token'));
			add_action('admin_init', array($admin, 'connect_imap'));
			
			//show settings for email pipingset_agent_list_order Rules
			add_action('wp_ajax_wpsc_get_ep_rules_settings', array($admin,'get_ep_rules_settings'));
			add_action('wp_ajax_wpsc_get_ep_rules_form_field', array($admin,'get_ep_rules_form_field'));
			add_action('wp_ajax_wpsc_set_ep_rules_form_field', array($admin,'set_ep_rules_form_field'));
			add_action('wp_ajax_wpsc_get_edit_ep_rules_form_field', array($admin,'get_edit_ep_rules_form_field'));
			add_action('wp_ajax_wpsc_set_edit_ep_rules_form_field', array($admin,'set_edit_ep_rules_form_field'));
			add_action('wp_ajax_wpsc_delete_ep_rules_form_field', array($admin,'delete_ep_rules_form_field'));
			add_action('wp_ajax_wpsc_set_ep_rule_list_order', array($admin,'set_ep_rule_list_order'));
			
		}
		
		// Email import cron
		add_action('wpsc_cron',array($admin,'import_emails'));
		
		// change email notification from email & reply to email
		add_filter( 'wpsc_create_ticket_from_email_headers',array($admin,'change_email_from_email'),10,2);
		add_filter( 'wpsc_create_ticket_replyto_headers',array($admin,'change_email_from_email'),10,2);
		add_filter( 'wpsc_reply_from_email_headers',array($admin,'change_email_from_email'),10,2);
		add_filter( 'wpsc_reply_replyto_headers',array($admin,'change_email_from_email'),10,2);
		add_filter( 'wpsc_assign_agent_from_email_headers',array($admin,'change_email_from_email'),10,2);
		add_filter( 'wpsc_assign_agent_replyto_headers',array($admin,'change_email_from_email'),10,2);
		add_filter( 'wpsc_change_cat_from_email_headers',array($admin,'change_email_from_email'),10,2);
		add_filter( 'wpsc_change_cat_replyto_headers',array($admin,'change_email_from_email'),10,2);
		add_filter( 'wpsc_change_priority_from_email_headers',array($admin,'change_email_from_email'),10,2);
		add_filter( 'wpsc_change_priority_replyto_headers',array($admin,'change_email_from_email'),10,2);
		add_filter( 'wpsc_change_status_from_email_headers',array($admin,'change_email_from_email'),10,2);
		add_filter( 'wpsc_change_status_replyto_headers',array($admin,'change_email_from_email'),10,2);
		add_filter( 'wpsc_delete_ticket_from_email_headers',array($admin,'change_email_from_email'),10,2);
		add_filter( 'wpsc_delete_ticket_replyto_headers',array($admin,'change_email_from_email'),10,2);
		add_filter( 'wpsc_add_note_from_email_headers',array($admin,'change_email_from_email'),10,2);
		add_filter( 'wpsc_add_note_ticket_replyto_headers',array($admin,'change_email_from_email'),10,2);


		// Conditions
		add_filter( 'wpsc_condition_options', array( $admin, 'add_condition_option' ) );
		add_filter( 'wpsc_check_custom_ticket_condition', array( $admin, 'check_ticket_ep_conditions' ), 10, 3 );
		
		add_filter('wpsc_ticket_thread_reply_source',array($admin,'wpsc_ticket_thread_reply_source'),10,4);
		// License
		add_filter( 'wpsc_is_add_on_installed', array($admin,'is_add_on_installed'));
		add_action( 'wpsc_addon_license_area', array($admin,'addon_license_area'));
		add_action( 'wp_ajax_wpsc_ep_activate_license', array($admin,'license_activate'));
		add_action( 'wp_ajax_wpsc_ep_deactivate_license', array($admin,'license_deactivate'));
		add_action( 'admin_init', array($this, 'plugin_updator'));

		//email notification setting
		add_action('wpsc_after_en_setting_pills',array($admin,'wpsc_after_en_setting_pills'));
		add_action('wp_ajax_wpsc_get_ep_email_notifications', array($admin,'wpsc_get_ep_email_notifications'));
		add_action('wp_ajax_wpsc_set_ep_en_setting', array($admin,'wpsc_set_ep_en_setting'));
		add_action('wpsc_add_external_en_setting_scripts',array($admin,'add_external_en_setting_scripts'));

		//remove ep email from en
		add_filter( 'wpsc_en_assign_agent_email_addresses', array( $admin, 'remove_ep_from_en' ),10,3 );
		add_filter( 'wpsc_en_change_category_email_addresses', array( $admin, 'remove_ep_from_en' ),10,3 );
		add_filter( 'wpsc_en_change_priority_email_addresses', array( $admin, 'remove_ep_from_en' ),10,3 );
		add_filter( 'wpsc_en_change_status_email_addresses', array( $admin, 'remove_ep_from_en' ),10,3 );
		add_filter( 'wpsc_en_delete_ticket_email_addresses', array( $admin, 'remove_ep_from_en' ),10,3 );
		add_filter( 'wpsc_en_create_ticket_email_addresses', array( $admin, 'remove_ep_from_en' ),10,3 );
		add_filter( 'wpsc_en_submit_note_email_addresses', array( $admin, 'remove_ep_from_enotify' ),10,4 );
		add_filter( 'wpsc_en_submit_reply_email_addresses', array( $admin, 'remove_ep_from_enotify' ),10,4 );
	}
	
	private function define($name, $value) {
			if (!defined($name)) {
					define($name, $value);
			}
	}
	
	function plugin_updator(){
		$license_key    = get_option('wpsc_ep_license_key','');
		$license_expiry = get_option('wpsc_ep_license_expiry','');
		if ( class_exists('Support_Candy') && $license_key && $license_expiry ) {
			$edd_updater = new EDD_SL_Plugin_Updater( WPSC_STORE_URL, __FILE__, array(
							'version' => WPSC_EP_VERSION,
							'license' => $license_key,
							'item_id' => WPSC_EP_STORE_ID,
							'author'  => 'Pradeep Makone',
							'url'     => site_url()
			) );
		}	
	}
	
	private function is_request($type) {
			switch ($type) {
					case 'admin' :
							return is_admin();
					case 'frontend' :
							return (!is_admin() || defined('DOING_AJAX') ) && !defined('DOING_CRON');
			}
	}
	
}

new WPSC_Email_Piping();
