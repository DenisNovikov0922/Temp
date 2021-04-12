<?php
/**
 * Plugin Name: Operator Registered User
 * Plugin URI:  https://www.codextent.com
 * Description: Enable Operator users to register new user using Registration magic Plugin.
 * Version:     1.0.1
 * Author:      Codextent
 * Author URI:  http://www.codextent.com
 * Copyright:   2020 Codextent
 *
 * Text Domain: opregusr-text-domain
 * Domain Path: /languages/
 */
 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Opregusr_Start{

	protected static $instance;
	
	static $requirements_error = array();
	
	var $admin_instance = false;
	var $front_instance = false;
		
	/**
	 * Class Construct.
	 *
	 * @since 1.0
	 */	
	public function __construct() {
		
		//Define Defaults
		$this->define_defaults();
		
		//Load plugin languages
		add_action( 'plugins_loaded', array( $this, 'plugin_text_domain') );
		
		//Load plugin requirements so can be accessed/checked
		add_action( 'plugins_loaded', array( $this, 'requirements_load'), 10 );
			
		//On plugin activation | Dont activate if requirements not met
		register_activation_hook( __FILE__, array( $this, 'plugin_activation' ) );
		
		// Admin end: We need to inform user if requirements error
		add_action( 'admin_notices',  array( $this, 'admin_requirement_check' ) );
		
		//Initiate Plugin		
		add_action( 'plugins_loaded', array( $this, 'plugin_init'), 15 );
	}
	
	
	
	/**
	 * Define Constants and other defaults
	 *
	 * @param  bool $network_wide is a multisite network activation
	 *
	 * @since  1.0
	 */
	protected function define_defaults(){
		
		// Plugin Name.
		if ( ! defined( 'OPREGUSR_PLG_NAME' ) ) {
			define( 'OPREGUSR_PLG_NAME', 'Operator Registered User' );
		}
		

		if ( ! defined( 'OPREGUSR_PLUGIN_FILE' ) ) {
			define( 'OPREGUSR_PLUGIN_FILE', __FILE__);
		}			
		// Plugin Folder Path.
		if ( ! defined( 'OPREGUSR_PLUGIN_DIR' ) ) {
			define( 'OPREGUSR_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		}
		// Plugin Folder URL.
		if ( ! defined( 'OPREGUSR_PLUGIN_URL' ) ) {
			define( 'OPREGUSR_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		}
		// Plugin Assets Folder URL.
		if ( ! defined( 'OPREGUSR_ASSETS_URL' ) ) {
			define( 'OPREGUSR_ASSETS_URL', OPREGUSR_PLUGIN_URL.'assets/' );
		}
		// Plugin Base Name
		if ( ! defined( 'OPREGUSR_PLUGIN_BASENAME' ) ) {
			define( 'OPREGUSR_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
		}
	
		// Plugin Required Constants
		if ( ! defined( 'OPREGUSR_PHP_VERSION_REQUIRED' ) ) {
			define( 'OPREGUSR_PHP_VERSION_REQUIRED', '5.6.0' );
		}
		if ( ! defined( 'OPREGUSR_WP_VERSION_REQUIRED' ) ) {
			define( 'OPREGUSR_WP_VERSION_REQUIRED', '4.4' );
		}
		//Registration Magic Plugin
		if ( ! defined( 'OPREGUSR_RM_REQUIRED' ) ) {
			define( 'OPREGUSR_RM_REQUIRED', '4.6.1.2' );
		}
		
	}
	
	/**
	 * Check dependency
	 *
	 * @param  bool $network_wide is a multisite network activation
	 *
	 * @since  1.0
	 */
	public function plugin_activation() {
		
		$requirement_error = self::requirements_check();
		
		if($requirement_error !== false){
			
			self::deactivate_plugin();
			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}
			wp_die(implode("\r\n",$requirement_error));
		}
		
		global $wpdb;
		$record_table = $wpdb->prefix."rm_submissions_operator";
		$query = "CREATE TABLE IF NOT EXISTS `{$record_table}` (
				  `id` int(111) NOT NULL AUTO_INCREMENT,
				  `operator_user` int(11) NOT NULL,
				  `submission_id` int(111) NOT NULL,
				  `registered_user` int(111) NOT NULL,
				  `date_created` varchar(222) NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
				
		$wpdb->query($query);
	}
	
	/**
	 * Check dependency & display error in admin area.
	 *
	 * @since  1.0
	 */
	public function admin_requirement_check(){
		
		$requirement_error = self::$requirements_error;
		
		$class = 'notice notice-error';
	
		if(!empty($requirement_error)){
			foreach($requirement_error as $notice){
				printf( '<div class="%1$s"><p><strong>%2$s:</strong> %3$s</p></div>', $class, OPREGUSR_PLG_NAME, $notice);
			}
		}
		
	}
		
	/**
	 * load plugin files
	 *
	 * @since  1.0
	 */
	public function plugin_init() {
		
		//Dont initiate plugin if requirement error
		$requirement_error = self::$requirements_error;
		if($requirement_error !== false){
			return;	
		}
			
		// Load admin
		if ( is_admin() ) {

			require_once trailingslashit( OPREGUSR_PLUGIN_DIR ) . 'admin/main.php';
			
			//create admin instance
			$this->admin_instance = new Opregusr_Admin();
		}
		
		// Load Front 
		if ( !is_admin() ) {
			
			require_once trailingslashit( OPREGUSR_PLUGIN_DIR ) . 'front/main.php';
			
			//create front instance
			$this->front_instance = new Opregusr_Front();
		}
	}
	
	/**
	 * load plugin text Domain
	 *
	 * @since  1.0
	 */
	public function plugin_text_domain() {
		load_plugin_textdomain( 'opregusr-text-domain', false, basename( dirname(__FILE__) ) . '/languages' );
    }
	
	/**
	 * Deractivate the plugin
	 *
	 * @since  1.0
	 */
	public static function deactivate_plugin() {
		deactivate_plugins( plugin_basename( __FILE__ ) );
	}
	
	/**
	* Check the plugin requirements 
	* Return error message array | false if no error
	*
	* @since  1.0
	*/
	public static function requirements_check(){
	
		$requirement_error = array();
		//PHP Version check
		if( version_compare(PHP_VERSION, OPREGUSR_PHP_VERSION_REQUIRED, '<') ){
			$msg = sprintf( 'Minimum PHP version required is %1$s but you are running %2$s.', OPREGUSR_PHP_VERSION_REQUIRED, PHP_VERSION );
			$requirement_error['php_version'] =  esc_html__( $msg, 'opregusr-text-domain');
		}
		//WP Version check
		if( version_compare(get_bloginfo('version'), OPREGUSR_WP_VERSION_REQUIRED, '<') ){
			$msg = sprintf( 'Minimum WordPress version required is %1$s but you are running %2$s.', OPREGUSR_WP_VERSION_REQUIRED, get_bloginfo('version') );	
			$requirement_error['wp_version'] =  esc_html__( $msg, 'opregusr-text-domain');
		}
		//Registration Magic requirements checking
		if(! defined( 'RM_PLUGIN_VERSION' )){
			$msg = 'Required Registration Magic plugin not found in your website.';
			$requirement_error['rm_activate'] =  esc_html__( $msg, 'opregusr-text-domain');
			
		}else if( version_compare( RM_PLUGIN_VERSION, OPREGUSR_RM_REQUIRED, '<') ){
			$msg = sprintf( 'Minimum Registration Magic plugin version required is %1$s but you are running %2$s.', OPREGUSR_RM_REQUIRED, RM_PLUGIN_VERSION );	
			$requirement_error['rm_version'] =  esc_html__( $msg, 'opregusr-text-domain');
		}
		
		if(empty($requirement_error)){return false;}
		else{return $requirement_error;}
	}
	
	
	public function requirements_load(){
		
		self::$requirements_error = self::requirements_check();
	}
	
	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0
	 */
	public function __clone() {
		wc_doing_it_wrong( __FUNCTION__, esc_html__( 'Cloning is forbidden.', 'opregusr-text-domain' ), '1.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0
	 */
	public function __wakeup() {
		wc_doing_it_wrong( __FUNCTION__, esc_html__( 'Unserializing instances of this class is forbidden.', 'opregusr-text-domain' ), '1.0' );
	}


	/**
	 * Returns the class instance.
	 *
	 * @since  1.0
	 */
	public static function instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

}

// ok! ready to go.
function opregusr() { 
	return Opregusr_Start::instance();
}
opregusr();