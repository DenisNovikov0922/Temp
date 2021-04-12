<?php
/**
 * The extension bootstrap file
 *
 * MailPoet integration
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
/**
 * Main Class 
 */
final class RM_EX_MailPoet {

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
			self::$instance = new RM_EX_MailPoet();
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
           require( $this->includes_dir . '/rm_mailpoet.php'    );
           require( $this->includes_dir . '/rm_mailpoet_options.php'    );
           require( $this->includes_dir . '/rm_mailpoet_ui_strings.php'    );
	}

	/**
	 * Setup the default hooks and actions
	 *
	 * @access private
	 * @uses add_action() To add various actions
	 */
	private function set_actions() { 
            add_action( 'admin_menu',array($this,'admin_menu'));
            add_action('rm_extended_apps',array($this,'show_app_option'),2);
            add_action('rm_extended_apps_formcard_menu',array($this,'show_app_option_fcm'),2,2);
            if(rm_is_mailpoet_active())
            {
                add_action('rm_subscribe_newsletter',array($this,'subscribe'),10,2);
                add_action('rm_show_subscribe_opt',array($this,'show_opt'),10,3);
            }    
            
	}
        
        public function show_app_option($rdrto)
        { 
            ?>
            <div class="rm-grid-icon difl">  
                <a href="?page=rm_form_sett_mailpoet&rm_form_id=<?php echo $_GET['rm_form_id']; ?>&rdrto=<?php echo $rdrto; ?>" class="rm_fd_link">  
                    <div class="rm-grid-icon-area dbfl">
                        <img class="rm-grid-icon dibfl" src="<?php echo RM_IMG_URL; ?>mailpoet-logo.png">
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php echo RM_MailPoet_UI_Strings::get('NAME_MAILPOET'); ?></div>
                </a>
            </div>     
            <?php
        }
        
        public function show_app_option_fcm($form_id, $rdrto)
        { 
            ?>
            <div class="rm-formcard-tab-item">  
                <a href="?page=rm_form_sett_mailpoet&rm_form_id=<?php echo $form_id; ?>&rdrto=<?php echo $rdrto; ?>" class="rm_fd_link">  
                    <img class="rm-formcard-icon" src="<?php echo RM_IMG_URL; ?>mailpoet-logo.png">
                    <div class="rm-formcard-label"><?php echo RM_MailPoet_UI_Strings::get('NAME_MAILPOET'); ?></div>
                </a>
            </div>     
            <?php
        }
        
        public function admin_menu()
        {
             add_submenu_page("", "RM MailPoet settings", "RM MailPoet settings", "manage_options", "rm_form_sett_mailpoet", array($this,'form_sett_manage'));
        }
        
        public function form_sett_manage()
        {  
            $next_page =  (isset($_GET['rdrto']) && $_GET['rdrto']) ? $_GET['rdrto'] : "rm_form_sett_manage";
            $form_id= $_GET['rm_form_id'];
            $service= new RM_Mailpoet_Service();
            $model= new RM_Forms();
            $model->load_from_db($form_id);
    
            if (RM_PFBC_Form::isValid("form_sett_mailpoet")) 
            {
                $options = array();
                $options['enable_mailpoet'] = isset($_POST['enable_mailpoet'])?$_POST['enable_mailpoet']:null;
                $options['mailpoet_form']= isset($_POST['mailpoet_form'])?$_POST['mailpoet_form']:null;
                $options['mailpoet_field_mappings']= $service->map_fields($_POST);
                $options['form_is_opt_in_checkbox_mp'] = isset($_POST['form_is_opt_in_checkbox_mp']) ? $_POST['form_is_opt_in_checkbox_mp'] : null;
                $options['form_opt_in_text_mp'] = isset($_POST['form_opt_in_text_mp'])?$_POST['form_opt_in_text_mp']:null;
                $options['form_opt_in_default_state_mp'] = isset($_POST['form_opt_in_default_state_mp']) ? $_POST['form_opt_in_default_state_mp'] : null;
                
               
                if (isset($_GET['rm_form_id']) && (int) $_GET['rm_form_id']) {
                    $model->load_from_db($_GET['rm_form_id']);
                    $model->set($options);
                    $model->update_into_db();
                    RM_Utilities::redirect('?page='.$next_page.'&rm_form_id='.$_GET['rm_form_id']);
                    return;
                } else {
                    echo '<div class="rmnotice">' . RM_UI_Strings::get('MSG_FS_NOT_AUTHORIZED') . '</div>';
                    return;
                }
            }
            
            if (isset($form_id) && (int) $form_id) {
            $data = new stdClass();
            $data->next_page = $next_page;
            $data->form_id = $_GET['rm_form_id'];
            $model->load_from_db($data->form_id);
            $mp_form_id= !empty($_GET['mp_form_id']) ? (int) $_GET['mp_form_id'] : null;
            if(!empty($mp_form_id))
                $data->mailpoet_fields= $service->get_fields_by_form($mp_form_id);
            else
                $data->mailpoet_fields= $service->get_fields_by_form ($model->form_options->mailpoet_form);
                
            //echo '<pre>';
            //print_r($data->mailpoet_fields); die;
            $data->mp_form_id= $mp_form_id;
            $data->mailpoet_forms = $service->get_forms(true);
            $data->model= $model;
            }            
            do_action('rm_pre_admin_template_render', "form_sett_mailpoet"); 
            require_once 'includes/view_settings.php';
        }
        
        public function subscribe($form_id,$request)
        {
            $rm_form= new RM_Forms();
            $rm_form->load_from_db($form_id);
            
            
            $form= new RM_Forms();
            $form->load_from_db($form_id);
            
            if($rm_form->form_options->enable_mailpoet[0]==1)
            {   
                $form_options_mp=  $rm_form->form_options;

                if ($form_options_mp->form_is_opt_in_checkbox_mp == 1 || (isset($form_options_mp->form_is_opt_in_checkbox_mp[0]) && $form_options_mp->form_is_opt_in_checkbox_mp[0] == 1))
                    $should_subscribe = isset($request['rm_subscribe_mp']) && $request['rm_subscribe_mp'][0] == 1 ? 'yes' : 'no';
                else
                    $should_subscribe = 'yes';

                if($should_subscribe == 'yes'){
                    $form_options_mp= $rm_form->form_options;
                    $merge_fields_array = array();
                    $mp_form_id = $form_options_mp->mailpoet_form;
                    $field_mappings= $form_options_mp->mailpoet_field_mappings;
                    
                    $data= array();
                    if(is_array($field_mappings))
                    {
                        foreach($field_mappings as $key=>$value)
                        { 
                            if(stristr($value,'date'))
                            {
                                $datetime= isset($request[$value]) ?  strtotime($request[$value]) : time();

                                    $data[$key]['year']= strftime('%Y', $datetime);
                                    $data[$key]['month']= strftime('%m', $datetime);
                                    $data[$key]['day']= strftime('%d', $datetime);

                               
                                
                            }
                            else
                                $data[$key]= isset($request[$value]) ?  $request[$value] : '';
                        }
                    }

                    $mp_service = new RM_Mailpoet_Service();
                    $mp_service->subscribe($data,$mp_form_id);
                }
              }		              
        }
        
        public function show_opt($form_id,$form,$editing_sub)
        {    
            $rm_form= new RM_Forms();
            $rm_form->load_from_db($form_id);
            
            if ($rm_form->form_options->form_is_opt_in_checkbox_mp[0] == 1 && $rm_form->form_options->enable_mailpoet[0] == 1 && $editing_sub == false)
            {  
                //This outer div is added so that the optin text can be made full width by CSS.
                
                if(!empty($form)){
                    $form->addElement(new Element_HTML('<div class="rm_optin_text">'));
                
                    if($rm_form->form_options->form_opt_in_default_state_mp == 'Checked')
                        $form->addElement(new Element_Checkbox('', 'rm_subscribe_mp', array(1 => $rm_form->form_options->form_opt_in_text_mp ? : RM_UI_Strings::get('MSG_SUBSCRIBE')),array("value"=>1)));
                    else 
                        $form->addElement(new Element_Checkbox('', 'rm_subscribe_mp', array(1 => $rm_form->form_options->form_opt_in_text_mp ? : RM_UI_Strings::get('MSG_SUBSCRIBE'))));

                    $form->addElement(new Element_HTML('</div>'));
                }
              
                else{
                    
                    if ($rm_form->form_options->form_opt_in_default_state_mp == 'Checked')
                    echo '<input type="checkbox" name="rm_subscribe_mp" value="1" checked />  ';
                    else
                    echo '<input type="checkbox" name="rm_subscribe_mp" value="1"  />  ';
                    
                    echo $rm_form->form_options->form_opt_in_text_mp.'</br>' ? : RM_UI_Strings::get('MSG_SUBSCRIBE').'</br>';
                }
            } 
        }
        
        
}

/**
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 */
function rm_initialize_mailpoet() {
            return RM_EX_MailPoet::instance();
}

function rm_is_mailpoet_active()
{ 
     if (is_plugin_active('mailpoet/mailpoet.php') || is_plugin_active('wysija-newsletters/index.php')) {
         return true;
     }
     return false;
}
   
rm_initialize_mailpoet();
