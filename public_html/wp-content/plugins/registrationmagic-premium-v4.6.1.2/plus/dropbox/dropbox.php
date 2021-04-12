<?php
/**
 * The extension bootstrap file
 *
 * Dropbox integration
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
/**
 * Main Class 
 */
final class RM_EX_DPX {

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
         * @var null when object is not initialized 
         */
        private static $instance=null; 
	/**
	 * Ensuring only one instance 
	 *
	 * @return RM_ANET object
	 */
	public static function instance() { 

		// Only run these methods if they haven't been ran previously
		if (self::$instance===null) {
			self::$instance = new RM_EX_DPX();
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
            $this->file       = __FILE__;
            $this->ex_dir = plugin_dir_path( $this->file );
            $this->ex_url = plugin_dir_url ( $this->file );
            $this->includes_dir = trailingslashit( $this->ex_dir . 'includes');
	}

	/**
	 * Include required files
	 */
	private function includes() {
            //require_once($this->includes_dir.'/lib/Dropbox/autoload.php'); 
            require( $this->includes_dir . '/rm_dpx.php'    );
            require( $this->includes_dir . '/rm_dpx_options.php'    );
            require( $this->includes_dir . '/rm_dpx_ui_strings.php'    );
	}

	/**
	 * Setup the default hooks and actions
	 *
	 * @access private
	 * @uses add_action() To add various actions
	 */
	private function set_actions() { 
            add_action( 'admin_notices', array($this,'admin_notice') );
            add_action( 'admin_menu',array($this,'admin_dpx_menu'));
            add_filter('rm_extend_thirdparty_config',array($this,'admin_dpx_config'),10,1);
            add_action('rm_gopts_thirdparty_save',array($this,'admin_dpx_save_options'),10,1);
            add_action('rm_after_submission',array($this,'after_form_submission'),10,3);
            add_action('rm_extended_apps',array($this,'show_dpx_app_option'),1);
            add_action('rm_extended_apps_formcard_menu',array($this,'show_dpx_app_option_fcm'),1,2);
	}
        
        public function show_dpx_app_option($rdrto)
        {
            ?>
            <div class="rm-grid-icon difl">  
                <a href="?page=rm_form_sett_dropbox&rm_form_id=<?php echo $_GET['rm_form_id']; ?>&rdrto=<?php echo $rdrto; ?>" class="rm_fd_link">  
                    <div class="rm-grid-icon-area dbfl">
                        <img class="rm-grid-icon dibfl" src="<?php echo $this->ex_url; ?>includes/images/dropbox.png">
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php echo RM_Dpx_UI_Strings::get('LABEL_F_DPX_SETT'); ?></div>
                </a>
            </div> 
            <?php
        }
        
        public function show_dpx_app_option_fcm($form_id, $rdrto)
        {
            ?>
            <div class="rm-formcard-tab-item">  
                <a href="?page=rm_form_sett_dropbox&rm_form_id=<?php echo $form_id; ?>&rdrto=<?php echo $rdrto; ?>" class="rm_fd_link">  
                    <img class="rm-formcard-icon" src="<?php echo $this->ex_url; ?>includes/images/dropbox.png">
                    <div class="rm-formcard-label"><?php echo RM_Dpx_UI_Strings::get('LABEL_F_DPX_SETT'); ?></div>
                </a>
            </div> 
            <?php
        }
        
        public function admin_dpx_menu()
        {
             add_submenu_page("", "RM Dropbox settings", "RM Dropbox settings", "manage_options", "rm_form_sett_dropbox", array($this,'dpx_setting_manage'));
        }
        
        public function admin_dpx_config($data)
        { 
            $dpx_options= new RM_Dpx_Options();
            $elements= array();
            $enable_dpx= $dpx_options->get_value_of('enable_dpx');
            $elements[]=  new Element_Checkbox(RM_UI_Strings::get('LABEL_DPX_OPTION_INTEGRATION'), "enable_dpx", array("yes" => ''),array("id" => "id_rm_enable_dpx_cb", "class" => "id_rm_enable_dpx_cb" , "value" =>  $enable_dpx,  "onclick" => "hide_show(this)", "longDesc" => RM_UI_Strings::get('HELP_OPTIONS_THIRDPARTY_DPX_ENABLE')));
            
            if($enable_dpx == 'yes')
                $elements[]= new Element_HTML('<div class="childfieldsrow" id="id_rm_enable_dpx_cb_childfieldsrow">');
            else
                $elements[]=new Element_HTML('<div class="childfieldsrow" id="id_rm_enable_dpx_cb_childfieldsrow" style="display:none">');
            $elements[]= new Element_Textbox(RM_Dpx_UI_Strings::get('LABEL_DPX_CLIENT_ID'), "dpx_client_id", array("value" => $dpx_options->get_value_of('dpx_client_id'), "id" => "id_dpx_client_id", "longDesc" => RM_Dpx_UI_Strings::get('HELP_OPTIONS_DPX_CLIENT_ID')));
            $elements[]= new Element_Textbox(RM_Dpx_UI_Strings::get('LABEL_DPX_CLIENT_SECRET'), "dpx_client_secret", array("value" => $dpx_options->get_value_of('dpx_client_secret'), "id" => "id_dpx_client_secret", "longDesc" => RM_Dpx_UI_Strings::get('HELP_OPTIONS_DPX_CLIENT_SECRET')));
            $elements[]= new Element_Textbox(RM_Dpx_UI_Strings::get('LABEL_DPX_ACCESS_TOKEN'), "dpx_access_token", array("value" => $dpx_options->get_value_of('dpx_access_token'), "id" => "id_dpx_access_token", "longDesc" => RM_Dpx_UI_Strings::get('HELP_OPTIONS_DPX_TOKEN')));
             $elements[]= new Element_HTML('</div>');
            $data['thirdparty_configs']['dpx']= $elements;
            return $data;
        }
        
        public function dpx_setting_manage()
        {
            $next_page =  (isset($_GET['rdrto']) && $_GET['rdrto']) ? $_GET['rdrto'] : "rm_form_sett_manage";
            $form_id= $_GET['rm_form_id'];
            $model= new RM_Forms();
            $model->load_from_db($form_id);
    
            if(RM_PFBC_Form::isValid('form_sett_dpx'))
            {        
                $options['enable_dpx'] = isset($_POST['enable_dpx']) ? 1 : null;
                $model->set($options);
                $model->update_into_db();
                RM_Utilities::redirect(admin_url('/admin.php?page='.$next_page.'&rm_form_id='.$form_id));
                exit;
            }
            
            if (isset($form_id) && (int) $form_id) {
            $data = new stdClass();
            $data->next_page = $next_page;
            $data->model = $model;
            }
            do_action('rm_pre_admin_template_render', "form_sett_dpx");            
            require_once 'includes/view_settings.php';
        }
        
        
        
        public function admin_dpx_save_options($req)
        {  
            $dpx_options= new RM_Dpx_Options();
            $options['enable_dpx'] = isset($req['enable_dpx']) ? "yes" : null;
                    
            if(isset($req['dpx_client_id']))
                $options['dpx_client_id'] = RM_Utilities::enc_str($_POST['dpx_client_id']);
            if(isset($req['dpx_client_secret']))
                $options['dpx_client_secret'] = RM_Utilities::enc_str($_POST['dpx_client_secret']);
            if(isset($req['dpx_access_token']))
                $options['dpx_access_token'] = RM_Utilities::enc_str($_POST['dpx_access_token']);
                
         
            $dpx_options->set_values($options);
        }
        
        public function after_form_submission($sub_detail,$req,$sub_pdf_loc)
        {            
            $submission= new RM_Submissions();
            $submission->load_from_db($sub_detail->submission_id);
            
            $form= new RM_Forms();
            $form->load_from_db($submission->get_form_id());
            $form_options= $form->get_form_options();
            
            
            $dpx_options= new RM_Dpx_Options();
            $access_token= $dpx_options->get_value_of('dpx_access_token');
            $client_id= $dpx_options->get_value_of('dpx_client_id');
            $client_secret= $dpx_options->get_value_of('dpx_client_secret');
            
            $g_dpx_enabled= $dpx_options->get_value_of('enable_dpx');
            if (!extension_loaded('mbstring'))
                return ;
            if($g_dpx_enabled=="yes" && !empty($access_token) && $form_options->enable_dpx=="1")
            {    
                include('vendor/autoload.php');
                $app = new Kunnu\Dropbox\DropboxApp($client_id,$client_secret,$access_token);
                $dropbox= new Kunnu\Dropbox\Dropbox($app);
                       
                try{
                $blog_name= get_bloginfo('name');
                $blog_name = empty($blog_name) ? '/' : '/'.$blog_name.'/';
                $dropbox_path= $blog_name.$form->get_form_name().'/'.$submission->get_user_email().'_'.$submission->get_submission_id().".pdf";
                $dropbox_path = apply_filters('rm_dx_filename',$dropbox_path,$submission,$form);
                $file = $dropbox->upload($sub_pdf_loc, $dropbox_path, array('autorename' => false));
                
                }
                catch(Dropbox\Exception_InvalidAccessToken $exception)
                {
                   // print_r($exception); die;
                }
              
                //Delete the attachment once mail is sent
                if(file_exists($sub_pdf_loc))
                    unlink($sub_pdf_loc);
                
            }
        }
        
        public function admin_notice(){
            $dpx_options= new RM_Dpx_Options();
            $access_token= $dpx_options->get_value_of('dpx_access_token');
            $g_dpx_enabled= $dpx_options->get_value_of('enable_dpx');
            $dpx_notice_shown= $dpx_options->get_value_of('dpx_notice_shown');
             
            if(!isset($_REQUEST['disable_dpx']) && !empty($access_token) && $g_dpx_enabled==null && empty($dpx_notice_shown)){
                ?>
                <div id="rm_dpx_conf_notice" style="text-align:center;background-color:#ffffce;color:orange" class= "notice notice-error is-dismissible">
                    <p>
                        Due to a change in how Dropbox APIs work, you need to update your RegistrationMagic Dropbox settings. Click <a href="<?php echo admin_url('admin.php?page=rm_options_thirdparty&disable_dpx=1#id_rm_enable_dpx_cb-0'); ?>">here</a> to do it now.
                    </p>
                </div>
                <?php
               
            }
            if(isset($_REQUEST['disable_dpx'])){
                 $dpx_options->set_value_of('dpx_notice_shown',1);   
            }
        }
}

/**
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 */
function rm_initialize_dpx() {
	return RM_EX_DPX::instance();
}

rm_initialize_dpx();
