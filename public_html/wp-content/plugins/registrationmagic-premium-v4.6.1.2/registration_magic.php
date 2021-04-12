<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.registrationmagic.com
 * @since             3.0.0
 * @package           registration_magic
 *
 * @wordpress-plugin
 * Plugin Name:       RegistrationMagic Premium
 * Plugin URI:        http://www.registrationmagic.com
 * Description:       A powerful system for customizing registration forms, setting up paid registrations, tracking submissions, managing users, assigning user roles, analyzing stats, and much more!!
 * Version:           4.6.1.2
 * Tags:              registration, form, custom, analytics, simple, submissions
 * Requires at least: 3.3.0
 * Requires PHP: 5.6
 * Author:            Registrationmagic
 * Author URI:        https://registrationmagic.com/
 * Text Domain:       registrationmagic-gold
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

if(version_compare(PHP_VERSION, '5.3') < 0){

  if(is_admin()){

    function rm_plugin_notice(){      
      ?>
      <div style="text-align:center;background-color:#ffffce;color:orange" class= "notice notice-error is-dismissible">
        <p>
        <?php
          printf(__('RegistrationMagic requires <b>at least PHP 5.3</b>. You have %s'), PHP_VERSION);
         ?>
        </p>
      </div>
      <?php 
      if (!function_exists('deactivate_plugins')) {
        require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
    }
    
    deactivate_plugins( plugin_basename(__FILE__ ) );
   
    }

    add_action('admin_notices', 'rm_plugin_notice');
  }
  
  return;  
}

if (defined('REGMAGIC_GOLD'))
{
    if (!function_exists('deactivate_plugins')) 
        require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
    deactivate_plugins( plugin_basename(__FILE__ ) );
    return;
}

define('REGMAGIC_GOLD','99');
$rmsilver = 'custom-registration-form-builder-with-submission-manager-silver/registration_magic.php';
$rmbasic = 'custom-registration-form-builder-with-submission-manager/registration_magic.php';
$rmgoldi2 = 'registrationmagic-gold-i2/registration_magic.php';
if (defined('REGMAGIC_GOLD_i2')) {
    if (!function_exists('deactivate_plugins')) {
        require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
    }
    deactivate_plugins($rmgoldi2);
}

if (defined('REGMAGIC_SILVER')) {
    if (!function_exists('deactivate_plugins')) {
        require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
    }
    
    deactivate_plugins($rmsilver);    
}

if (defined('REGMAGIC_BASIC')) {
    if (!function_exists('deactivate_plugins')) {
        require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
    }
    deactivate_plugins($rmbasic);
}

if (!defined('RM_PLUGIN_VERSION')) {
    define('RM_PLUGIN_BASENAME', plugin_basename(__FILE__ ));
    define('RM_PLUGIN_VERSION', '4.6.1.2');
    define('RM_DB_VERSION', 5.3);
    define('RM_SHOW_WHATSNEW_SPLASH', false);  //Set it to 'false' to disable whatsnew screen.
    //User level on front end
    define('RM_FRONT_OTP_USER', 0x2);
    define('RM_FRONT_NO_USER', 0x1);
    define('RM_FRONT_WP_USER', 0x4);

//define FB SDK req flags. Flags should be combined using logical OR and should be checked using AND.
    define('RM_FB_SDK_REQ_PHP_NA', 0x2);  //Php version is not sufficient
    define('RM_FB_SDK_REQ_EXT_NA', 0x4);  //mbstring extension not installed or disabled
    define('RM_FB_SDK_REQ_OK', 0x1);      //Requirements met. DO NOT TEST FOR THIS FLAG USING &. use === instead.
//Error IDs
    define('RM_ERR_ID_EXT_ZIP', 1);
    define('RM_ERR_ID_EXT_CURL', 2);
    define('RM_ERR_ID_EXT_SIMPLEXML', 3);
    define('RM_ERR_ID_EXT_MCRYPT', 4);
    define('RM_ERR_ID_EXT_MBSTRING', 5);
    define('RM_ERR_ID_PHP_VERSION', 6);
    define('RM_ERR_ID_SESSION_PATH', 7);

//Dependency flags
    define('RM_REQ_PHP_VERSION', 0x8);
    define('RM_REQ_EXT_ZIP', 0x10);
    define('RM_REQ_EXT_CURL', 0x20);
    define('RM_REQ_EXT_SIMPLEXML', 0x40);
    define('RM_REQ_EXT_MCRYPT', 0x80);
    define('RM_REQ_EXT_MBSTRING', 0x100);

    define('RM_BASE_DIR', plugin_dir_path(__FILE__));
    define('RM_BASE_URL', plugin_dir_url(__FILE__));
    define('RM_ADMIN_DIR', RM_BASE_DIR . "admin/");
    define('RM_PUBLIC_DIR', RM_BASE_DIR . "public/");
    define('RM_IMG_DIR', RM_BASE_DIR . "images/");
    define('RM_IMG_URL', plugin_dir_url(__FILE__) . 'images/');
    define('RM_INCLUDES_DIR', RM_BASE_DIR . 'includes/');
    define('RM_EXTERNAL_DIR', RM_BASE_DIR . 'external/');

//form types
    define('RM_BASE_FORM', 99);
    define('RM_CONTACT_FORM', 0);
    define('RM_REG_FORM', 1);
    
//sent email types : values 1-99 are reserved for RM base, extensions must use values outside this.
    define('RM_EMAIL_GENERIC', 1);
    define('RM_EMAIL_AUTORESP', 2);
    define('RM_EMAIL_BATCH', 3);
    define('RM_EMAIL_USER_ACTIVATION_ADMIN', 4);
    define('RM_EMAIL_POSTSUB_ADMIN', 5);
    define('RM_EMAIL_USER_ACTIVATED_USER', 6);
    define('RM_EMAIL_PASSWORD_USER', 7); //MUST NOT BE SAVED IN DB!!
    define('RM_EMAIL_NOTE_MSG', 8); //Message sent from submission view. It is a note whose type is message.
    define('RM_EMAIL_NOTE_ADDED', 9);
    define('RM_EMAIL_TEST', 10);
    
//read status
    define('RM_SUB_STATUS_READ', 1);
    define('RM_SUB_STATUS_UNREAD', 0);
    
    $regmagic_errors = array(); //Global variable to store errors throghout the plugin, so that we can display the error msgs on proper screens which belong to our plugin.
    $rm_fb_sdk_req = RM_FB_SDK_REQ_OK;  //Set default value.
    $rm_env_requirements = 0;
//Check for plugin requirements before proceeding
    function registration_magic_check_requirements() {
        global $rm_env_requirements;

        $installed_php_version = phpversion();
        //var_dump(get_loaded_extensions());die;
        if (version_compare('5.3', $installed_php_version, '<='))
            $rm_env_requirements |= RM_REQ_PHP_VERSION;

        if (extension_loaded('mbstring'))
            $rm_env_requirements |= RM_REQ_EXT_MBSTRING;

        if (extension_loaded('zip'))
            $rm_env_requirements |= RM_REQ_EXT_ZIP;

        //if (extension_loaded('mcrypt'))
            //$rm_env_requirements |= RM_REQ_EXT_MCRYPT;

        if (extension_loaded('SimpleXML'))
            $rm_env_requirements |= RM_REQ_EXT_SIMPLEXML;

        if (extension_loaded('curl'))
            $rm_env_requirements |= RM_REQ_EXT_CURL;
    }

    registration_magic_check_requirements();

    /**
     * registers the plugin autoload
     */
    function registration_magic_register_autoload() {
        require_once plugin_dir_path(__FILE__) . 'includes/class_rm_autoloader.php';

        $autoloader = new RM_Autoloader();
        $autoloader->register();
    }

    /**
     * includes or initializes all the external libraries used in the plugin
     * 
     * @since 3.0.0
     */
    function registration_magic_include_external_libs() {
        $installed_php_version = phpversion();
        global $rm_env_requirements;
        $gopts = new RM_Options;
        require_once RM_EXTERNAL_DIR . 'session/rm_wpdb_sessions.php'; 
        // Skip session start if theme editor operation 
        $theme_editing= isset($_REQUEST['action']) && $_REQUEST['action']=='edit-theme-plugin-file' ? true : null;
        if(!session_id() && !$theme_editing)
            session_start();
        require_once RM_EXTERNAL_DIR . 'PFBC/Form.php';
        require_once RM_EXTERNAL_DIR . 'mailchimp/class_rm_mailchimp.php';
        require_once RM_EXTERNAL_DIR . 'AWeber/class_rm_aweber.php';
        require_once RM_EXTERNAL_DIR . 'cron/cron_helper.php';
        require_once RM_EXTERNAL_DIR . 'plugin-updates/plugin-update-checker.php';

        //check for FB SDK v5 requirements and setup the global var accordingly.
        global $rm_fb_sdk_req;
        global $rm_env_requirements;
        if ($rm_env_requirements & RM_REQ_EXT_CURL) {
            $mbstring_ext_available = extension_loaded('mbstring');

            if (version_compare('5.4', $installed_php_version, '>'))
                $rm_fb_sdk_req |= RM_FB_SDK_REQ_PHP_NA;

            if ($mbstring_ext_available === false)
                $rm_fb_sdk_req |= RM_FB_SDK_REQ_EXT_NA;
        }
        
        //Get configured pgws
        $pgws = $gopts->get_value_of('payment_gateway');

        if(is_array($pgws) && count($pgws) > 0) {
            if(in_array('paypal',$pgws))
                require_once RM_EXTERNAL_DIR . 'PayPal/paypal.php';
        }
    }

    registration_magic_register_autoload();
    registration_magic_include_external_libs();
    
    register_activation_hook(__FILE__, 'RM_Activator::activate');
    register_deactivation_hook(__FILE__, 'RM_Deactivator::deactivate');

    //Set up update check
    $rm_update_checker = Puc_v4_Factory::buildUpdateChecker('http://registrationmagic.com/metadata.json', __FILE__, 'registrationmagic-gold');
    
    $rm_form_diary = array();
    
    /**
     * Begins execution of the plugin.
     *
     * Since everything within the plugin is registered via hooks,
     * then kicking off the plugin from this point in the file does
     * not affect the page life cycle.
     *
     * @since    3.0.0
     */
    function run_registration_magic() {
        $plugin = new Registration_Magic();
        $plugin->run();
    }

    run_registration_magic();

    //Kick extender
    RM_Extender::init();
    
}
