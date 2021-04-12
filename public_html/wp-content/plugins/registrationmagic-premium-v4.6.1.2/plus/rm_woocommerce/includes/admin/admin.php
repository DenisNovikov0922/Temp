<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'RM_WC_Admin' ) ) :
    

class RM_WC_Admin {
    
    private $admin_dir;
    private $admin_url;
    private $images_url;
    private $css_url;
    private $js_url;
    
    public function __construct() { 
        $this->setup_globals();
        $this->includes();
        $this->setup_actions();
    }
    
    
    /**
     *  Globals
     *
     */
	private function setup_globals() {
		$rm_wc = rm_wc();
		$this->admin_dir  = trailingslashit( $rm_wc->includes_dir . 'admin'  ); // Admin path
		$this->admin_url  = trailingslashit( $rm_wc->includes_url . 'admin'  ); // Admin url
                $this->images_url = trailingslashit( $rm_wc->admin_url   . 'images' ); // Admin images URL
		$this->css_url    = trailingslashit( $rm_wc->admin_url   . 'css'    ); // Admin css URL
		$this->js_url     = trailingslashit( $rm_wc->admin_url   . 'js'     ); // Admin js URL
	}

    /**
     * Including files
     *
     */
    private function includes() {
            require( $this->admin_dir .'functions.php' );
    }

    /**
     * Setup the admin hooks, actions and filters
     *
     */
    private function setup_actions() { 
            /** Actions ****************************/
          add_action( 'rm_wc_admin_menu',array( $this, 'admin_menus')); 
    }

    /**
     * Add the admin menus
     */
    public function admin_menus() {
        add_submenu_page("", "RM WC setting", "RM WC setting", "manage_options", "rm_wc_settings", 'rm_wc_setting_manage');
    }
    
    public function rmwc_admin_scripts(){
      
    }
}


endif;

/**
 * Load Admin
 */
function rm_wc_admin() {
        $erf= rm_wc();
	$erf->admin = new RM_WC_Admin();
}
