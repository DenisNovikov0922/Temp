<?php
/**
 * The RM extension bootstrap file
 *
 * Adds offline payment feature to RM
 *
 * @link              http://www.registrationmagic.com
 * @since             3.5.3.0
 * @package           offlinepay
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

// Check if plugin is active and class not already loaded
if (!class_exists( 'RM_EX_Offline_Pay' ) ) :
define('RM_OLP_EMAIL_INFO_TO_USER', 101);
/**
 * Main Class 
 */
final class RM_EX_Offline_Pay {

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
			self::$instance = new RM_EX_Offline_Pay();
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

		$this->version    = '1.0.0';
		$this->db_version = '1.0';

		/** Paths *************************************************************/

		// Setup some base path and URL information
		$this->file       = __FILE__;
		$this->basename   = apply_filters( 'rm_olp_plugin_basenname', plugin_basename( $this->file ) );
		$this->plugin_dir = apply_filters( 'rm_olp_plugin_dir_path',  plugin_dir_path( $this->file ) );
		$this->plugin_url = apply_filters( 'rm_olp_plugin_dir_url',   plugin_dir_url ( $this->file ) );
                $this->images_url = apply_filters( 'rm_olp_images_dir_url',   trailingslashit( $this->plugin_url . 'images'  ) );
		
// Includes
		$this->includes_dir = apply_filters( 'rm_olp_includes_dir', trailingslashit( $this->plugin_dir . 'includes'  ) );
		$this->includes_url = apply_filters( 'rm_olp_includes_url', trailingslashit( $this->plugin_url . 'includes'  ) );

		// Languages
		$this->lang_dir     = apply_filters( 'rm_olp_lang_dir',     trailingslashit( $this->plugin_dir . 'languages' ) );


		$this->current_user   = new WP_User(); // Currently logged in user

		/** Misc **************************************************************/

		$this->domain         = 'rm_olp';      // Unique identifier for retrieving translated strings
		$this->errors         = new WP_Error(); // Feedback
                
                /**************** Template path ****************/
                $this->template_url= apply_filters('rm_olp_style_url',  trailingslashit( $this->plugin_url . 'includes/views'  ));
                 
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
                require( $this->includes_dir . 'common/ui_strings.php' );
                require( $this->includes_dir . 'common/options.php' );
                
                /** Public *********************************************************************/
                require( $this->includes_dir . 'public/functions.php');
                require( $this->includes_dir . 'public/actions.php' );
                require( $this->includes_dir . 'public/do-actions.php' );
                
                
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
		add_action( 'rm_ex_activate_'   . $this->basename, 'rm_olp_activation'   );
		add_action( 'rm_ex_deactivate_' . $this->basename, 'rm_olp_deactivation' );

		// If deactivated, do not add any actions
		if ( rm_olp_deactivation( $this->basename ) )
			return;

		// Array of core actions
		$actions = array(
                    'setup_current_user', // Get current logged in user
                );

		// Add the actions
		foreach ( $actions as $class_action )
			add_action( 'rm_olp_' . $class_action, array( $this, $class_action ), 5 );
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
		$this->shortcodes = new RM_OLP_Shortcodes();
	}

	/**
	 * Setup the currently logged-in user
	 *
	 * @uses wp_get_current_user()
	 */
	public function setup_current_user() {
		$this->current_user = wp_get_current_user();
	}

}

/**
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $rm_olp = rm_olp(); ?>
 *
 */
function rm_olp() {
	return RM_EX_Offline_Pay::instance();
}

rm_olp();

endif; // class_exists check
