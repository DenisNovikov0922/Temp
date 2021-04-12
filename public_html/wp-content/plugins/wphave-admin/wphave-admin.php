<?php /*
Plugin Name: wphave - Admin
Plugin URI: https://wphave.com
Description: The WordPress admin theme.
Version: 2.1
Author: Martin Jost
Author URI: https://wphave.com
Text Domain: wphave-admin
Domain Path: /languages
*/

if( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( ! class_exists('wphave_admin') ) :

	class wphave_admin {
		
		/** @var string The plugin version number */
		var $version = '2.1';
		
		/** @var string The plugin name */
		var $plugin = 'wphave - Admin';
		
		/** @var string The plugin namespace */
		var $namespace = 'wphave_admin';
		
		/** @var string The plugin author name */
		var $author = 'Martin Jost';
		
		/** @var string The plugin author url */
		var $author_url = 'https://wphave.com';
		
		/** @var string The plugin author mail */
		var $author_mail = 'support@wphave.com';
		
		/*
		*  __construct
		*
		*  A dummy constructor to ensure the plugin is only initialized once
		*
		*  @type	function
		*  @date	06/18/19
		*  @since	2.0
		*
		*  @param	N/A
		*  @return	N/A
		*/

		function __construct() {
			
			/* Do nothing here */

		}
		
		/*
		*  initialize
		*
		*  The real constructor to initialize the plugin
		*
		*  @type	function
		*  @date	06/18/19
		*  @since	2.0
		*
		*  @param	N/A
		*  @return	N/A
		*/
		
		function initialize() {
			
			/****************
			* VARIABLES
			****************/
			
			$version = $this->version;
			$namespace = $this->namespace;
			$author = $this->author;
			$author_url = $this->author_url;
			$author_mail = $this->author_mail;
			$plugin = $this->plugin;
			$basename = plugin_basename( __FILE__ );
			$path = plugin_dir_path( __FILE__ );
			$url = plugin_dir_url( __FILE__ );
			$slug = dirname( $basename );
			
			
			/****************
			* CONSTANTS
			****************/

			define( 'WPHAVE_ADMIN_VERSION', $version );
			define( 'WPHAVE_ADMIN_NAMESPACE', $namespace );
			define( 'WPHAVE_ADMIN_AUTHOR_URL', $author_url );
			define( 'WPHAVE_ADMIN_AUTHOR_MAIL', $author_mail );
			define( 'WPHAVE_ADMIN_PLUGIN', __FILE__ );
			define( 'WPHAVE_ADMIN_PLUGIN_NAME', $plugin );
			define( 'WPHAVE_ADMIN_PATH', $path );
			define( 'WPHAVE_ADMIN_URL', $url );			
			
			define( 'WPHAVE_ADMIN_GET_APL_SALT', '0e9a5c5ab21c75ed' );
			define( 'WPHAVE_ADMIN_GET_APL_PRODUCT_ID', 5 );
			define( 'WPHAVE_ADMIN_GET_APL_INCLUDE_KEY_CONFIG', '139555bdf37001aa' );
			
			define( 'WPHAVE_ADMIN_ENVATO_ID', '20354956' );
			define( 'WPHAVE_ADMIN_ENVATO_URL', 'https://codecanyon.net/item/wphave-admin-a-clean-and-modern-wordpress-admin-theme/20354956' );
			define( 'WPHAVE_ADMIN_ENVATO_REVIEW_URL', 'http://codecanyon.net/downloads#item-' . WPHAVE_ADMIN_ENVATO_ID );
			
			
			/****************
			* PLUGIN TEXTDOMAIN
			****************/

			load_plugin_textdomain( 'wphave-admin', null, $slug . '/languages' );
			
			
			/****************
			* INCLUDE REQUIRED FUNCTIONS
			****************/
			
			include_once( WPHAVE_ADMIN_PATH . 'inc/helper.php' );
			include wphave_admin_dir( 'inc/init/update.php' );
			include wphave_admin_dir( 'inc/init/setup.php' );
			include wphave_admin_dir( 'inc/settings.php' );			
			include wphave_admin_dir( 'inc/enqueue.php' );
			include wphave_admin_dir( 'inc/generate-styles.php' );
			include wphave_admin_dir( 'inc/notices.php' );
			include wphave_admin_dir( 'inc/side-menu.php' );

			
			/****************
			* ACTIONS
			****************/
			
			add_action('init', array( $this, 'wphave_admin_init' ), 5);
			
		}
		
		/*
		*  init
		*
		*  This function will run after all plugins and theme functions have been included.
		*  To ensure specific functions doesn't undefined, if the plugin call it to early, we can use this init function.
		*
		*  @type	action (init)
		*  @date	06/18/19
		*  @since	2.0
		*
		*  @param	N/A
		*  @return	N/A
		*/

		function wphave_admin_init() {
			
			/****************
			* WPLESS
			****************/
			
			if( ! class_exists( 'wp_less' ) ) {
				// Only included if wpless is not already loaded by another plugin or theme to prevent conflicts
				include_once( wphave_admin_dir('inc/wp-less/wp-less.php') );
			}
			
			include wphave_admin_dir( 'inc/wp-less-vars.php' );
			
			
			/****************
			* PLUGIN PAGES
			****************/
			
			include wphave_admin_dir( 'inc/pages/setup.php' );
			
			if( ! wphave_option('disable_page_wp') ) { 
				include wphave_admin_dir( 'inc/pages/wp.php' );
			}
			
			if( ! wphave_option('disable_page_constants') ) { 
				include wphave_admin_dir( 'inc/pages/constants.php' );
			}
			
			if( ! wphave_option('disable_page_system') ) { 
				include wphave_admin_dir( 'inc/pages/server-info.php' );
			}
			
			if( ! wphave_option('disable_transient_manager') ) { 
				include wphave_admin_dir( 'inc/pages/transient-manager.php' );
			}
			
			if( ! wphave_option('disable_page_export') ) { 
				include wphave_admin_dir( 'inc/pages/ex-import.php' );
			}
			
			if( ! wphave_option('disable_page_ms') ) { 
				include wphave_admin_dir( 'inc/pages/multisite-sync.php' );
			}
			
			if( ! wphave_option('disable_page_error_log') ) { 
				include wphave_admin_dir( 'inc/pages/error-log.php' );
			}
			
			if( ! wphave_option('disable_page_htaccess') ) { 
				include wphave_admin_dir( 'inc/pages/htaccess-file.php' );
			}
			
			if( ! wphave_option('disable_page_php_ini') ) { 
				include wphave_admin_dir( 'inc/pages/php-ini-file.php' );
			}
			
			if( ! wphave_option('disable_page_robots_txt') ) { 
				include wphave_admin_dir( 'inc/pages/robots-txt-file.php' );
			}
			
			
			/****************
			* PLUGIN FUNCTIONS
			****************/

			include wphave_admin_dir( 'inc/db-widgets/db-widgets.php' );
			include wphave_admin_dir( 'inc/layout.php' );	
			include wphave_admin_dir( 'inc/login.php' );
			include wphave_admin_dir( 'inc/footer.php' );
			include wphave_admin_dir( 'inc/wp.php' );
			
			if( is_user_logged_in() ) {
				include wphave_admin_dir( 'inc/frontend.php' );
			}
			
		}

	} // end class

	/*
	*  wphave admin
	*
	*  The main function responsible for returning the one true wphave Instance to functions everywhere.
	*  Use this function like you would a global variable, except without needing to declare the global.
	*
	*  Example: <?php wphave_admin = wphave_admin(); ?>
	*
	*  @type	function
	*  @date	06/18/19
	*  @since	2.0
	*
	*  @param	N/A
	*  @return	N/A
	*/

	if ( ! function_exists( 'wphave_admin' ) ) :

		function wphave_admin() {

			// Globals
			global $wphave_admin;

			// Initialize
			if( ! isset( $wphave_admin ) ) {
				$wphave_admin = new wphave_admin();
				$wphave_admin->initialize();
			}

			// Return
			return $wphave_admin;

		}

	endif;

	// Initialize
	wphave_admin();

endif;