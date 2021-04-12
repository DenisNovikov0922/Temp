<?php
/**
 * The extension bootstrap file
 *
 * Authorizenet integration
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
/**
 * Main Class 
 */
final class RM_ANET {

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
			self::$instance = new RM_ANET();
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
            $this->basename   = apply_filters( 'rm_anet_plugin_basenname', plugin_basename( $this->file ) );
            $this->plugin_dir = apply_filters( 'rm_anet_plugin_dir_path',  plugin_dir_path( $this->file ) );
            $this->plugin_url = apply_filters( 'rm_anet_plugin_dir_url',   plugin_dir_url ( $this->file ) );
            $this->includes_dir = apply_filters( 'rm_anet_includes_dir', trailingslashit( $this->plugin_dir . 'includes'  ) );
	}

	/**
	 * Include required files
	 *
	 * @access private
	 * @uses is_admin() If in WordPress admin, load additional file
	 */
	private function includes() {
                require( $this->includes_dir . '/rm_anet_sim.php'    );
                require( $this->includes_dir . '/rm_anet_options.php'    );     
	}

	/**
	 * Setup the default hooks and actions
	 *
	 * @access private
	 * @uses add_action() To add various actions
	 */
	private function set_actions() { 
            add_filter('rm_extend_payprocs_options',array($this,'admin_anet_option'),10,2);
            add_filter('rm_extend_payprocs_config',array($this,'admin_anet_config'),10,2);
            add_action('rm_gopts_payment_save',array($this,'admin_anet_save_options'),10,1);
            add_action('init', array($this, 'check_authorize_response'));
            add_action('rm_pre_form_proc',array($this,'pre_form_proc'),10,1);
            add_action('rm_payment_procs_options_frontend', array($this,'show_payment_option'),10,2);
            add_filter('rm_process_payment', array($this,'process_payment'),10,4);
            add_action( 'admin_enqueue_scripts', array($this,'admin_anet_enque_script') );
            add_action( 'wp_ajax_rm_authnet_ipn', array($this,'check_authorize_response'));
            add_action( 'wp_ajax_nopriv_rm_authnet_ipn', array($this,'check_authorize_response'));
	}
        
         public function admin_anet_option($pay_procs_options, $data)
        {
            $pay_procs_options['anet']= "<img style='width:auto;' src='" . $this->plugin_url . "images/adn.png" . "'>";
            return $pay_procs_options;
            
        }
        
        public function admin_anet_config($config, $data)
        {
            $anet_options= new RM_Anet_Options();
            $options= $anet_options->get_all_options();
            
            $options_anet_login_id = array("id" => "rm_anet_login_id", "value" => $anet_options->get_value_of('anet_login_id'), "longDesc" => RM_UI_Strings::get('HELP_OPTIONS_ANET_LOGIN_ID'));
            $options_anet_trans_key = array("id" => "rm_anet_trans_key", "value" => $anet_options->get_value_of('anet_trans_key'), "longDesc" => RM_UI_Strings::get('HELP_OPTIONS_ANET_TRANS_KEY'));
            $options_anet_sign_key = array("id" => "rm_anet_sign_key", "value" => $anet_options->get_value_of('anet_sign_key'), "longDesc" => RM_UI_Strings::get('HELP_OPTIONS_ANET_SIGN_KEY'));
            $options_anet_test = array("id" => "rm_anet_test", "longDesc" => RM_UI_Strings::get('HELP_OPTIONS_ANET_TESTMODE'),"value"=>$anet_options->get_value_of('anet_test_mode'));

            $elements= array();
            $elements[]= new Element_Checkbox(RM_UI_Strings::get('LABEL_TEST_MODE'), "anet_test_mode", array("yes" => ''),$options_anet_test );
            $elements[]= new Element_Textbox(RM_UI_Strings::get('LABEL_ANET_LOGIN_ID'), "anet_login_id", $options_anet_login_id);
            $elements[]= new Element_Textbox(RM_UI_Strings::get('LABEL_ANET_TRANSACTION_KEY'), "anet_trans_key", $options_anet_trans_key);
            $elements[]= new Element_Textbox(RM_UI_Strings::get('LABEL_ANET_SIGN_KEY'), "anet_sign_key", $options_anet_sign_key);
            $config['anet']= $elements;
            return $config;
        }
        
        public function show_payment_option($radio_array)
        {
            if(!RM_Utilities::is_ssl()){
                return $radio_array;
            }
            $anet_opts = new RM_Anet_Options;
            $payment_gateways = $anet_opts->get_value_of('payment_gateway');
            if(in_array('anet', $payment_gateways))
            {
                $radio_array['anet_sim'] = "<img src='" . $this->plugin_url . "images/authorize-net-logo.png" . "'>";
            }
            return $radio_array;
        }
        
        public function process_payment($payment_done, $form, $request, $params)
        {
           if(!isset($request->req['rm_payment_method']))
                return $payment_done;
           $payment_method = $request->req['rm_payment_method'];
           if($payment_method=="anet_sim")
           {
              $anet= new RM_Anet_Sim();
              return $anet->process_payment($form, $request, $params);  
           }
           
           return $payment_done;
        }
        
       
        
        public function admin_anet_save_options($req)
        {  
            $anet_options= new RM_Anet_Options();
            $options['anet_test_mode'] = isset($req['anet_test_mode']) ? "yes" : null;
                        
            if(!empty($req['anet_login_id']))
                $options['anet_login_id'] = RM_Utilities::enc_str ($req['anet_login_id']);
            
            if(!empty($req['anet_trans_key']))
                $options['anet_trans_key'] = RM_Utilities::enc_str($req['anet_trans_key']);
            
            if(!empty($req['anet_sign_key']))
                $options['anet_sign_key'] = RM_Utilities::enc_str($req['anet_sign_key']);
            $anet_options->set_values($options);
        }
        public function check_authorize_response()
        {
             $anet= new RM_Anet_Sim();
             $anet->check_authorize_response();
        }
        
        public function pre_form_proc($request)
        {   
            global $wp;
            $msg= '';
            if(isset($request['anet']) && $request['anet']=='1')
            {  
                $log_id= $request['log_id'];
                if(empty($log_id))
                    return;
                
             
            $log = RM_DBManager::get_row('PAYPAL_LOGS', $log_id);
            $ex_data = maybe_unserialize($log->ex_data);
            if(!empty($log) && !empty($log->log))
            {
                $anet_log = maybe_unserialize($log->log); 
                $submission_id =  $log->submission_id;
                $submission =  new RM_Submissions();
                $submission->load_from_db($submission_id);
                
                $service= new RM_Front_Form_Service();
                $form_factory= new RM_Form_Factory();
                $fe_form = $form_factory->create_form($submission->get_form_id());
                $form_options= $fe_form->get_form_options();
                if( $log->status=='completed')
                {
                    $options= new RM_Anet_Options();
                    $sign_key = $options->get_value_of('anet_sign_key');
                    $login_id= $options->get_value_of('anet_login_id');
  
                    $hash= $request['hash'];
                   
                    $calculated_hash= strtoupper(md5( $sign_key . $login_id . $anet_log['x_trans_id'] .  $anet_log['x_amount']));
                    
                    if(trim($hash)!=$calculated_hash)
                        return;
                   
                    $user_id = (int)$ex_data['user_id'];
                    if ($user_id>0 && $form_options->auto_login)
                    {
                        $_SESSION['RM_SLI_UID'] = $user_id;     
                    }
                    if(!empty($user))
                    {
                        echo '<div id="rmform">';
                        echo "<div class='rminfotextfront'>" . RM_UI_Strings::get("MSG_PAYMENT_SUCCESS") . "</br>";
                        echo '</div></div>';
                    }
                    
                   
                }
                else if($anet_log['status']=='pending')
                {
                    echo '<div id="rmform">';
                    echo "<div class='rminfotextfront'>" . RM_UI_Strings::get("MSG_PAYMENT_PENDING") . "</br>";
                    echo '</div></div>';
                }
                      
                $msg = ob_get_clean();
                $x = new stdClass;
                $x->form_options = $form_options;
                $x->form_name = $fe_form->get_form_name();
                $x->form_id = $submission->get_form_id();
                $x->sub_id = $submission_id;
                $after_sub_msg = $service->after_submission_proc($x);
                echo  $msg . '<br><br>' . $after_sub_msg;
                $current_url = home_url(add_query_arg(array(),$wp->request)); 
                $current_url=add_query_arg( array('rm_success'=>'1','rm_form_id'=>$submission->get_form_id(),'rm_sub_id'=>$submission_id), $current_url);
                RM_Utilities::redirect($current_url, false, 0, false);
                die;
            }
                
            }
        }
        
        public function admin_anet_enque_script($hook) {
            //Enqueue only on payment config page
            if('admin_page_rm_options_payment' == $hook) {
                 wp_enqueue_script( 'rm_anet_script', $this->plugin_url.'js/admin.js' );
            }
        }
}

/**
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $rm_wc = rm_wc(); ?>
 *
 */
function rm_anet() {
	return RM_ANET::instance();
}

rm_anet();
