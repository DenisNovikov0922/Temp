<?php
/**
 * The plugin bootstrap file
 *
 * Bridge between Registration Magic and Woocommerce
 *
 * @link              http://www.registrationmagic.com
 * @since             3.5.3.0
 * @package           rm-wc
 *
 * @wordpress-plugin
 * Plugin Name:       RM-Woocommerce
 * Plugin URI:        http://www.registrationmagic.com
 * Description:       Seamless Integration between Woocommerce and Registration magic
 * Version:           3.5.3.0
 * Requires at least: 3.3.0
 * Author:            CMSHelplive
 * Author URI:        http://cmshelplive.com
 * Text Domain:       rm-wc
 * Domain Path:       /languages
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );


add_action( 'admin_menu','rm_wc_admin_form_sett_menu');
add_action('rm_extended_apps','rm_form_wc_sett_app_option');
add_action('rm_extended_apps_formcard_menu','rm_form_wc_sett_app_option_fcm',10,2);
require('includes/common/rm_wc_ui_strings.php' ); 
function rm_wc_admin_form_sett_menu()
{    
     add_submenu_page("", __("RM Woocommerce settings",'registrationmagic-gold'), __("RM Woocommerce settings",'registrationmagic-gold'), "manage_options", "rm_form_sett_wc", 'rm_wc_form_sett_manage');
}

function rm_wc_form_sett_manage()
{
    $next_page =  (isset($_GET['rdrto']) && $_GET['rdrto']) ? $_GET['rdrto'] : "rm_form_sett_manage";
    $form_id= $_GET['rm_form_id'];
    $model= new RM_Forms();
    $model->load_from_db($form_id);
    $data = new stdClass();
    $data->next_page = $next_page;
    $data->model= $model;
    do_action('rm_pre_admin_template_render', "form_sett_wc");         
    include 'includes/views/form_settings.php';
}

  
function rm_form_wc_sett_app_option($rdrto)
{ 
    ?>
    <div class="rm-grid-icon difl">  
        <a href="?page=rm_form_sett_wc&rm_form_id=<?php echo $_GET['rm_form_id']; ?>&rdrto=<?php echo $rdrto; ?>" class="rm_fd_link">  
            <div class="rm-grid-icon-area dbfl">
                <img class="rm-grid-icon dibfl" src="<?php echo RM_IMG_URL; ?>woo.png">
            </div>
            <div class="rm-grid-icon-label dbfl"><?php echo RM_WC_UI_Strings::get('NAME_WC'); ?></div>
        </a>
    </div>     
    <?php
}

function rm_form_wc_sett_app_option_fcm($form_id, $rdrto)
{ 
    ?>
    <div class="rm-formcard-tab-item">
        <a href="?page=rm_form_sett_manage&rm_form_id=<?php echo $form_id; ?>&rdrto=<?php echo $rdrto; ?>#rm-thirdparty-section" class="rm_fdlink_more">   
            <?php _e("More",'registrationmagic-gold') ?>
        </a>

    </div>
    <!--
    <div class="rm-formcard-tab-item">  
        <a href="?page=rm_form_sett_wc&rm_form_id=<?php echo $form_id; ?>&rdrto=<?php echo $rdrto; ?>" class="rm_fd_link">  
                <img class="rm-formcard-icon" src="<?php echo RM_IMG_URL; ?>woo.png">
            <div class="rm-formcard-label"><?php echo RM_WC_UI_Strings::get('NAME_WC'); ?></div>
        </a>
    </div>   
    -->
    <?php
}

 function rm_is_wc_active()
 {
    if (is_plugin_active('woocommerce/woocommerce.php') ) 
        return true;
    return false;
         
 }
 
/**
 * Check if WooCommerce is active
 **/
if ( !rm_is_wc_active() ) {
    return;
}

// Check if plugin is active and class not already loaded
if ( /*is_plugin_active( 'registrationmagic-gold/registration_magic.php') &&*/ !class_exists( 'RM_WC' ) ) :
/**
 * Main Class 
 */
final class RM_WC {

	/**
	 * Most of these variables are stored in a
	 * private array that gets updated with the help of PHP magic methods.
	 *
	 * This is a precautionary measure, to avoid potential errors produced by
	 * unanticipated direct manipulation of run-time data.
	 *
	 * @var array
	 */
	private $data;

	/**
	 * @var mixed False when not logged in; WP_User object when logged in
	 */
	public $current_user = false;

	/**
	 * @var array Topic views
	 */
	public $views        = array();

	/**
	 * @var array Overloads get_option()
	 */
	public $options      = array();

	/**
	 * @var array Overloads get_user_meta()
	 */
	public $user_options = array();
        
        /**
         * @var null when object is not initialized 
         */
        private static $instance=null; 
	/**
	 * Ensuring only one instance 
	 *
	 * @return RM_WC object
	 */
	public static function instance() {

		// Only run these methods if they haven't been ran previously
		if (self::$instance===null) {
			self::$instance = new RM_WC();
			self::$instance->set_globals();
			self::$instance->includes();
			self::$instance->set_actions();
		}

		// Always return the instance
		return self::$instance;
	}

	/**
	 * A private constructor to prevent multiple objects.
	 *
	 */
	private function __construct() { /* Do nothing here */ }

	/**
	 * Prevent from being cloned
	 *
	 */
	public function __clone() { _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'rm-wc' ), '2.1' ); }

	/**
	 * Prevent from being unserialized
	 *
	 */
	public function __wakeup() { _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'rm-wc' ), '2.1' ); }

	/**
	 * Checking the existence of a field
	 *
	 */
	public function __isset( $key ) { return isset( $this->data[$key] ); }

	/**
	 * Method for getting variables
	 *
	 */
	public function __get( $key ) { return isset( $this->data[$key] ) ? $this->data[$key] : null; }

	/**
	 * Method for setting variables
	 *
	 */
	public function __set( $key, $value ) { $this->data[$key] = $value; }

	/**
	 * Method for unsetting variables
	 *
	 */
	public function __unset( $key ) { if ( isset( $this->data[$key] ) ) unset( $this->data[$key] ); }

	/**
	 * Method to prevent notices and errors from invalid method calls
	 *
	 */
	public function __call( $name = '', $args = array() ) { unset( $name, $args ); return null; }


	/**
	 * @access private
	 */
	private function set_globals() {

		/** Versions **********************************************************/

		$this->version    = '3.5.3.0';
		$this->db_version = '4.2';

		/** Paths *************************************************************/

		// Setup some base path and URL information
		$this->file       = __FILE__;
		$this->basename   = apply_filters( 'rm_wc_plugin_basenname', plugin_basename( $this->file ) );
		$this->plugin_dir = apply_filters( 'rm_wc_plugin_dir_path',  plugin_dir_path( $this->file ) );
		$this->plugin_url = apply_filters( 'rm_wc_plugin_dir_url',   plugin_dir_url ( $this->file ) );
                $this->images_url = apply_filters( 'rm_wc_images_dir_url',   trailingslashit( $this->plugin_url . 'images'  ) );
		
// Includes
		$this->includes_dir = apply_filters( 'rm_wc_includes_dir', trailingslashit( $this->plugin_dir . 'includes'  ) );
		$this->includes_url = apply_filters( 'rm_wc_includes_url', trailingslashit( $this->plugin_url . 'includes'  ) );

		// Languages
		$this->lang_dir     = apply_filters( 'rm_wc_lang_dir',     trailingslashit( $this->plugin_dir . 'languages' ) );


		$this->current_user   = new WP_User(); // Currently logged in user

		/** Misc **************************************************************/

		$this->domain         = 'rm_wc';      // Unique identifier for retrieving translated strings
		$this->errors         = new WP_Error(); // Feedback
                
                /**************** Template path ****************/
                $this->template_url= apply_filters('rm_wc_style_url',  trailingslashit( $this->plugin_url . 'includes/views'  ));
                 
	}

	/**
	 * Include required files
	 *
	 * @access private
	 * @uses is_admin() If in WordPress admin, load additional file
	 */
	private function includes() {
                /** Common includes ************************************************************/
                require( $this->includes_dir . 'common/shortcodes.php'    );
                require( $this->includes_dir . 'common/rm_wc_options.php' );
                
                /** Core *********************************************************************/
                require( $this->includes_dir . 'core/functions.php');
                require( $this->includes_dir . 'core/rm_wc_fields.php' );
                require( $this->includes_dir . 'core/rm_wc_registration.php' );
                require( $this->includes_dir . 'core/actions.php' );
                require( $this->includes_dir . 'core/do-actions.php' );
                
                
                /** View files **************************************************************/
                
		/** Admin *************************************************************/

		// Quick admin check and load if needed
		if ( is_admin() ) {
                    require( $this->includes_dir . 'admin/admin.php'   );
                    require( $this->includes_dir . 'admin/actions.php' );
		}
	}

	/**
	 * Setup the default hooks and actions
	 *
	 * @access private
	 * @uses add_action() To add various actions
	 */
	private function set_actions() {
		// Add actions to plugin activation and deactivation hooks
		add_action( 'activate_'   . $this->basename, 'rm_wc_activation'   );
		add_action( 'deactivate_' . $this->basename, 'rm_wc_deactivation' );

		// If deactivated, do not add any actions
		if ( rm_wc_deactivation( $this->basename ) )
			return;

		// Array of core actions
		$actions = array(
                    'setup_current_user', // Get current logged in user
                    'load_textdomain' // Load text domain rm_wc
                );

		// Add the actions
		foreach ( $actions as $class_action )
			add_action( 'rm_wc_' . $class_action, array( $this, $class_action ), 5 );
	}
       
    
      
        
	/** Public Methods ********************************************************/

	/**
	 * Load the translation file for current language. Checks the languages
	 * folder inside the plugin first, and then the default WordPress
	 * languages folder.
	 *
	 * Note that custom translation files inside the plugin folder
	 * will be removed on plugin updates. If you're creating custom
	 * translation files, please use the global language folder.
	 *
	 *
	 * @uses apply_filters() Calls 'plugin_locale' with {@link get_locale()} value
	 * @uses load_textdomain() To load the textdomain
	 */
	public function load_textdomain() {
		
	}

	/**
	 * Register the shortcodes
	 *
	 * @uses RM_WC_Shortcodes
	 */
	public function register_shortcodes() {
		$this->shortcodes = new RM_WC_Shortcodes();
	}

	/**
	 * Setup the currently logged-in user
	 *
	 * @uses wp_get_current_user()
	 */
	public function setup_current_user() {
		$this->current_user = wp_get_current_user();
	}
        
        public function set_data($key,$value){
            $this->data[$key]= $value;
        }
        
        public function get_data(){
           return $this->data;
        }

}

/**
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $rm_wc = rm_wc(); ?>
 *
 */
function rm_wc() {
	return RM_WC::instance();
}

rm_wc();

else:   
    function rm_gold_instllation_check() {
    ?>
    <div class="notice notice-success is-dismissible">
        <p><?php _e( "RM-Woocommerce won't work as Registration Magic Gold is not active/installed. ", 'registrationmagic-gold' ); ?></p>
    </div>
    <?php
 }
add_action( 'admin_notices', 'rm_gold_instllation_check' );
  

endif; // class_exists check

