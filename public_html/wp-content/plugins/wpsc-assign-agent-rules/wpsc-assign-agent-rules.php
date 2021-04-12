<?php
/**
 * Plugin Name: SupportCandy - Assign Agent Rules
 * Plugin URI: https://supportcandy.net/
 * Description: Assign agents conditionally when new ticket is created.
 * Version: 2.0.4
 * Author: Support Candy
 * Author URI: https://supportcandy.net/
 * Requires at least: 4.4
 * Tested up to: 4.9
 * Text Domain: wpsc-caa
 * Domain Path: /lang
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WPSC_Conditional_Agent_Assign' ) ) :

  final class WPSC_Conditional_Agent_Assign {

    public $version = '2.0.4';

    public function __construct() {
    
		  $this->define_constants();
			$this->includes();
			add_action( 'init', array($this,'load_textdomain') );
			register_activation_hook(__FILE__,array($this,'activation'));
			register_deactivation_hook( __FILE__, array($this,'deactivate'));
		}

    function define_constants() {
      $this->define('WPSC_CAA_PLUGIN_FILE', __FILE__);
      $this->define('WPSC_CAA_ABSPATH', dirname(__FILE__) . '/');
      $this->define('WPSC_CAA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
      $this->define('WPSC_CAA_PLUGIN_BASENAME', plugin_basename(__FILE__));
			$this->define('WPSC_CAA_STORE_ID', '267');
      $this->define('WPSC_CAA_VERSION', $this->version);
    }

    function load_textdomain(){
      $locale = apply_filters( 'plugin_locale', get_locale(), 'wpsc-caa' );
      load_textdomain( 'wpsc', WP_LANG_DIR . '/wpsc/wpsc-caa' . $locale . '.mo' );
      load_plugin_textdomain( 'wpsc-caa', false, plugin_basename( dirname( __FILE__ ) ) . '/lang' );
    }

    public function includes() {
      include_once( WPSC_CAA_ABSPATH . 'class-wpsc-caa-install.php' );
      include_once( WPSC_CAA_ABSPATH . 'class-wpsc-caa.php' );
      $admin  = new WPSC_Conditional_Agent_AssignBackend();

      // Setting
      add_action( 'wpsc_after_setting_pills', array($admin,'conditional_agent_assign_setting_pill'));
      add_action( 'wp_ajax_wpsc_get_caa_settings', array($admin,'get_caa_settings'));
			add_action('wpsc_after_submit_reply',array($admin,'wpsc_after_submit_reply'),10,2);
			
			// Add Agent Roles
			add_action( 'wp_ajax_wpsc_get_add_new_agent_rule', array($admin,'wpsc_get_add_new_agent_rule'));
			add_action( 'wp_ajax_wpsc_set_add_new_agent_rule', array($admin,'wpsc_set_add_new_agent_rule'));
			add_action( 'wp_ajax_wpsp_get_edit_condition', array($admin,'wpsc_get_edit_condition'));
			add_action( 'wp_ajax_wpsc_set_edit_condition', array($admin,'wpsc_set_edit_condition'));
			add_action( 'wp_ajax_wpsc_delete_agent_condition', array($admin,'wpsc_delete_agent_condition'));
			add_action('wp_ajax_wpsc_set_other_settings', array($admin, 'wpsc_set_assign_auto_responder'));
		
			// Conditional Checkpoints
			add_action( 'wpsc_ticket_created', array($admin,'wpsc_after_create_ticket'), 10);
			
			// License
			add_filter( 'wpsc_is_add_on_installed', array($admin,'is_add_on_installed'));
			add_action( 'wpsc_addon_license_area', array($admin,'addon_license_area'));
			add_action( 'wp_ajax_wpsc_caa_activate_license', array($admin,'license_activate'));
			add_action( 'wp_ajax_wpsc_caa_deactivate_license', array($admin,'license_deactivate'));
			add_action( 'admin_init', array($this, 'plugin_updator'));
    }

    private function define($name, $value) {
      if (!defined($name)) {
        define($name, $value);
      }
	}
	
	function activation(){
		$widget = get_term_by( 'slug', 'conditional_agent_assign', 'wpsc_ticket_custom_fields' );
		if($widget){
			update_term_meta ($widget->term_id, 'wpsc_allow_ticket_list', '1');
			update_term_meta ($widget->term_id, 'wpsc_customer_ticket_list_status', '0');
			update_term_meta ($widget->term_id, 'wpsc_agent_ticket_list_status', '0');
			update_term_meta ($widget->term_id, 'wpsc_allow_ticket_filter', '1');
			update_term_meta ($widget->term_id, 'wpsc_agent_ticket_filter_status', '0');
			update_term_meta ($widget->term_id, 'wpsc_customer_ticket_filter_status', '0');
		}
	}

	function deactivate(){
		include(WPSC_CAA_ABSPATH .'class-wpsc-caa-uninstall.php');
	}
		
		function plugin_updator(){
			$license_key    = get_option('wpsc_caa_license_key','');
			$license_expiry = get_option('wpsc_caa_license_expiry','');
			if ( class_exists('Support_Candy') && $license_key && $license_expiry ) {
				$edd_updater = new EDD_SL_Plugin_Updater( WPSC_STORE_URL, __FILE__, array(
								'version' => WPSC_CAA_VERSION,
								'license' => $license_key,
								'item_id' => WPSC_CAA_STORE_ID,
								'author'  => 'Pradeep Makone',
								'url'     => site_url()
				) );
			}	
    }

  }

endif;

new WPSC_Conditional_Agent_Assign();