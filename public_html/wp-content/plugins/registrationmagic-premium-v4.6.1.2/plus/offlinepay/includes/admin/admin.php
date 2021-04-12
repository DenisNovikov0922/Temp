<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'RM_OLP_Admin' ) ) :
    

class RM_OLP_Admin {
    
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
		$rm_olp = rm_olp();
		$this->admin_dir  = trailingslashit( $rm_olp->includes_dir . 'admin'  ); // Admin path
		$this->admin_url  = trailingslashit( $rm_olp->includes_url . 'admin'  ); // Admin url
                $this->images_url = trailingslashit( $rm_olp->admin_url   . 'images' ); // Admin images URL
		$this->css_url    = trailingslashit( $rm_olp->admin_url   . 'css'    ); // Admin css URL
		$this->js_url     = trailingslashit( $rm_olp->admin_url   . 'js'     ); // Admin js URL
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
        add_action( 'rm_olp_admin_menu',array( $this, 'admin_menus'));
        add_action('admin_enqueue_scripts','rm_olp_admin_enqueue');
        add_action('rm_pre_admin_template_render', 'rm_olp_enqueue_script_admin');
        add_action('wp_ajax_rm_olp_update_payment', 'rm_olp_update_payment_callback_ajax');
        add_action('rm_gopts_payment_save', 'rm_olp_save_payment_settings');
        
        /** Filter *********************************/
        add_filter('rm_payment_status_sub_detail_admin', 'rm_olp_filter_payment_status_admin', 10, 2);
        add_filter('rm_extend_payprocs_options', 'rm_olp_filter_payproc_options_admin', 10, 2);
        add_filter('rm_extend_payprocs_config', 'rm_olp_filter_payproc_configs_admin', 10, 2);        
    }

    /**
     * Add the admin menus
     */
    public function admin_menus() {
        
    }
    
    public function rmwc_admin_scripts(){
      
    }
}


endif;

/**
 * Load Admin
 */
function rm_olp_admin() {
        $olp= rm_olp();
	$olp->admin = new RM_OLP_Admin();
}

rm_olp_admin();