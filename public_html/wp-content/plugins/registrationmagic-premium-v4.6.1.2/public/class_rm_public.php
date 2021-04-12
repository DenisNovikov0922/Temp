<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://registration_magic.com
 * @since      1.0.0
 *
 * @package    Registraion_Magic
 * @subpackage Registraion_Magic/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Registraion_Magic
 * @subpackage Registraion_Magic/public
 * @author     CMSHelplive
 */
class RM_Public {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $registraion_magic    The ID of this plugin.
     */
    private $plugin_name;
    public static $form_counter=0;
    public static $login_form_counter=0;
    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * The controller of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $controller    The main controller of this plugin.
     */
    private $controller;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    private static $editor_counter = 1;
    
    // Helps to avoid success message for same form twice
    private static $success_form= false;
    
    public function __construct($plugin_name, $version, $controller) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->controller = $controller;
    }

    public function get_plugin_name() {
        return $this->plugin_name;
    }

    public function get_version() {
        return $this->version;
    }

    public function get_controller() {
        return $this->controller;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     * 
     */
    public function enqueue_styles() {
       
        $settings = new RM_Options;
        $theme = $settings->get_value_of('theme');
        $layout = $settings->get_value_of('form_layout');
        wp_enqueue_style('style_rm_rating', RM_BASE_URL . 'public/js/rating3/rateit.css', array(), $this->version, 'all');
               
        switch ($theme) {
            case 'classic' :
                if ($layout == 'label_top')
                    wp_enqueue_style('rm_theme_classic_label_top', plugin_dir_url(__FILE__) . 'css/theme_rm_classic_label_top.css', array(), $this->version, 'all');
                elseif ($layout == 'two_columns')
                    wp_enqueue_style('rm_theme_classic_two_columns', plugin_dir_url(__FILE__) . 'css/theme_rm_classic_two_columns.css', array(), $this->version, 'all');
                else
                    wp_enqueue_style('rm_theme_classic', plugin_dir_url(__FILE__) . 'css/theme_rm_classic.css', array(), $this->version, 'all');
                break;
                
            default :
                if ($layout == 'label_top')
                    wp_enqueue_style('rm_theme_matchmytheme_label_top', plugin_dir_url(__FILE__) . 'css/theme_rm_matchmytheme_label_top.css', array(), $this->version, 'all');
                elseif ($layout == 'two_columns')
                    wp_enqueue_style('rm_theme_matchmytheme_two_columns', plugin_dir_url(__FILE__) . 'css/theme_rm_matchmytheme_two_columns.css', array(), $this->version, 'all');
                else
                    wp_enqueue_style('rm_theme_matchmytheme', plugin_dir_url(__FILE__) . 'css/theme_rm_matchmytheme.css', array(), $this->version, 'all');
                break;
        }
        //wp_enqueue_style('rm-jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/smoothness/jquery-ui.css', false, $this->version, 'all');
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/style_rm_front_end.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        $gopt= new RM_Options();
        $magic_pop= $gopt->get_value_of('display_floating_action_btn');
        wp_register_script('rm_front', plugin_dir_url(__FILE__) . 'js/script_rm_front.js', array('jquery', 'jquery-ui-core', 'jquery-ui-sortable', 'jquery-ui-tabs', 'jquery-ui-datepicker','jquery-effects-core','jquery-effects-slide'), $this->version, false);
        $login_service= new RM_Login_Service();
        $auth_options= $login_service->get_auth_options();
        $rm_ajax_data= array(
                        "url"=>admin_url('admin-ajax.php'),
                        "gmap_api"=>$gopt->get_value_of("google_map_key"),
                        "max_otp_attempt"=>!empty($auth_options['en_resend_otp']) ? $auth_options['otp_resend_limit']: 0,
                        'no_results'=>__('No Results Found','registrationmagic-gold'),
                        'invalid_zip'=>__('Invalid Zip Code','registrationmagic-gold'),
                        'request_processing'=>__('Please wait...','registrationmagic-gold'),
                        'hours'=>__('Hours','registrationmagic-gold'),
                        'minutes'=>__('Minutes','registrationmagic-gold'),
                        'seconds'=>__('Seconds','registrationmagic-gold'),
                        'days'=>__('Days','registrationmagic-gold'),
                        'months'=>__('Months','registrationmagic-gold'),
                        'years'=>__('Years','registrationmagic-gold')
                        );
        wp_localize_script( 'rm_front', 'rm_ajax', $rm_ajax_data );
        wp_enqueue_script('rm_front');
        
        wp_register_script('rm_front_form_script', RM_BASE_URL."public/js/rm_front_form.js",array('rm_front'), $this->version, false);
        //Register jQ validate scripts but don't actually enqueue it. Enqueue it from within the shortcode callback.
        wp_register_script('rm_jquery_validate', RM_BASE_URL."public/js/jquery.validate.min.js");
        wp_register_script('rm_jquery_validate_add', RM_BASE_URL."public/js/additional-methods.min.js");
        wp_register_script('rm_jquery_conditionalize', RM_BASE_URL."public/js/conditionize.jquery.js");
        
        if(isset($_GET['action']) && $_GET['action']=='registrationmagic_embedform'){
            wp_enqueue_script('google_charts', 'https://www.gstatic.com/charts/loader.js');
            wp_enqueue_script("rm_chart_widget",RM_BASE_URL."public/js/google_chart_widget.js");
            $service= new RM_Services();
            $gmap_api_key= $service->get_setting('google_map_key');
            if(!empty($gmap_api_key)){
                wp_enqueue_script ('google_map_key', 'https://maps.googleapis.com/maps/api/js?key='.$gmap_api_key.'&libraries=places');
                wp_enqueue_script("rm_map_widget_script",RM_BASE_URL."public/js/map_widget.js");
            }
            wp_enqueue_script("rm_pwd_strength",RM_BASE_URL."public/js/password.min.js");
            wp_enqueue_script("rm_mobile_data_script", RM_BASE_URL . "public/js/mobile_field/data.js");
            wp_enqueue_script("rm_mobile_script", RM_BASE_URL . "public/js/mobile_field/intlTelInput.js");
            wp_enqueue_style("rm_mobile_style", RM_BASE_URL . "public/css/mobile_field/intlTelInput.css");
            wp_localize_script('rm_mobile_script','rm_country_list', RM_Utilities::get_countries() );
            wp_enqueue_script("rm_mask_script", RM_BASE_URL . "public/js/jquery.mask.min.js");
            wp_enqueue_script('rm_jquery_conditionalize');
        }
        
        
    }

    public function run_controller($attributes = null, $content = null, $shortcode = null) {
        return $this->controller->run();
    }
    
    public function rm_front_submissions($attr) {

        $form_prev= isset($_GET['form_prev']) ? absint($_GET['form_prev']) : '';
        if(is_user_logged_in() && class_exists('Profile_Magic') && empty($attr) && empty($form_prev) && !isset($_REQUEST['submission_id'])){
            return do_shortcode('[PM_Profile]');
        }
        $user_model= new RM_User;
        if(!empty($_GET['resend']) && !empty($_GET['rm_user'])){
            $re_verification_link= RM_Utilities::get_acc_verification_link($_GET['rm_user']);
            echo 'Click here to resend the verification link.'.$re_verification_link;
            return;
        }
        /* User Verification */
        if(!empty($_GET['rm_hash']) && !empty($_GET['rm_user'])){
            
            $user_id= absint($_GET['rm_user']);
            if(empty($user_id))
                return;
            /*
            if(is_user_logged_in())
                return;
            */
            
            $hash= sanitize_text_field($_GET['rm_hash']);
            $user_hash= get_user_meta($user_id, 'rm_activation_hash', true);
            $gopts= new RM_Options();
           
            if(get_user_meta($user_id, 'rm_user_status', true)===0){
                echo $gopts->get_value_of('acc_act_notice');
                echo do_shortcode('[RM_Login]');
                return;
            }
            
            // check for payment status
            $submission_id= get_user_meta($user_id, 'RM_UMETA_SUB_ID', true); // Get submission ID from where it is 
            if(!empty($submission_id)){
                $submission_model= new RM_Submissions;
                $submission_model->load_from_db($submission_id);
                $status= $submission_model->get_payment_status();
                if(!empty($status) && !in_array(strtolower($status),array('completed','succeeded'))){
                  // Payment not completed.
                  echo RM_UI_Strings::get('LABEL_ACC_NOT_ACTIVATED_PENDING_PAYMENT');
                  return;
                }
            }
          
            if($hash==$user_hash){
                $act_message= $gopts->get_value_of('acc_act_notice');
                $act_expiry= $gopts->get_value_of('acc_act_link_expiry');
                if($act_expiry>0){
                    $user_info = get_userdata($user_id);
                    $reg_date= get_user_meta($user_id, 'rm_activation_time', true);
                    $reg_timestamp= strtotime($reg_date);
                    $current_time= current_time('timestamp');
                    $time_diff= $current_time-$reg_timestamp;
                    $seconds_diff= $time_diff/60;
                    $hour_diff= $seconds_diff/60;
                    if($act_expiry>=$hour_diff){
                        $user_model->activate_user($user_id);
                        echo $act_message;
                        echo do_shortcode('[RM_Login]');
                    } else {
                        $act_expiry_message= $gopts->get_value_of('acc_act_link_exp_notice');
                        $re_verification_link= RM_Utilities::get_acc_verification_link($user_id);
                        $act_expiry_message= str_ireplace('{{send verification email}}', $re_verification_link, $act_expiry_message);
                        $act_expiry_message= str_ireplace('{{SEND_VERIFICATION_EMAIL}}', $re_verification_link, $act_expiry_message);
                        
                        echo '<div class="rm_exp_link_msg">'.$act_expiry_message.'</div>';
                    }
                } else if($act_expiry==0) {
                    $user_model->activate_user($user_id);
                    delete_user_meta($user_id, 'rm_activation_hash');
                    echo $act_message;
                    echo do_shortcode('[RM_Login]');
                }
                //delete_user_meta( $user_id, $meta_key, $meta_value )
            } else{
                if(isset($_GET['rm_user'])){
                    $user_id= absint($_GET['rm_user']);
                    if($user_id==0)
                        return;
                    $user= get_userdata($user_id);
                    if(empty($user))
                        return;
                     $invalid_msg= $gopts->get_value_of('acc_invalid_act_code');
                     echo $invalid_msg;
                     echo '<form method="get"><input type="hidden" name="rm_user" value="'.$user_id.'"><input type="text" name="rm_hash" placeholder="Activation Code"><br><input type="submit" value="Submit"></form>';
                }
               
            }
            return;
        }
        /* Shows form preview */
        if(!empty($_GET['form_prev']) && !empty($_GET['form_id']) && is_super_admin())
        {  
            $form_id= $_GET['form_id'];
            $form_factory= new RM_Form_Factory();
            $form= $form_factory->create_form($form_id);
            $form->set_preview(true);
            echo '<script>jQuery(document).ready(function(){jQuery(".entry-header").remove();}); </script>';
            echo '<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">';
            echo '<div class="rm_embedeed_form">' . $form->render() . '</div>';
            return;
        }
        
        if (RM_Utilities::fatal_errors()) {
            ob_start();
            include_once RM_ADMIN_DIR . 'views/template_rm_cant_continue.php';
            $html = ob_get_clean();
            return $html;
        }
        
        $xml_loader = RM_XML_Loader::getInstance(plugin_dir_path(__FILE__) . 'rm_config.xml');

        $request = new RM_Request($xml_loader);
        if(isset($_POST['rm_slug'])){
            $request->setReqSlug($_POST['rm_slug'], true);
        }
        else{
            $request->setReqSlug('rm_front_submissions', true);
        }
        
        $params = array('request' => $request, 'xml_loader' => $xml_loader,'attr'=>$attr);
        $this->controller = new RM_Main_Controller($params);
        return $this->controller->run();
    }

    public function rm_login($attributes) {
        self::$login_form_counter++;
        $_REQUEST['login_popup_show']  = 0;
        if(!empty($_POST) && isset($_POST['rm_form_sub_id']) && ($_POST['rm_form_sub_id']=='rm_login_form_'.self::$login_form_counter || $_POST['rm_form_sub_id']=='rm_otp_form_'.self::$login_form_counter)){
            $_REQUEST['login_popup_show']  = 1;
        }
        $_REQUEST['hidden_forms_id']  = array();
        if(!empty($_POST) && isset($_POST['rm_form_sub_id'])){
            if($_POST['rm_form_sub_id']=='rm_login_form_'.self::$login_form_counter){
                echo '<style>#'.$_POST['rm_form_sub_id'].'{display:block;}</style>';
                echo '<style>#'.str_replace('rm_login_form_','rm_otp_form_',$_POST['rm_form_sub_id']).'{display:block;}</style>';
            }else{
                $_REQUEST['hidden_forms_id'][] = 'rm_login_form_'.self::$login_form_counter;
                //echo '<script>jQuery(document).ready(function(){jQuery("#rm_login_form_'.self::$login_form_counter.'").html("<div class=\'rm-login-attempted-notice\'>Note: You are already attempting login using a different login form on this page. To keep your logging experience simple and secure, this login form in no longer accessible. Please continue the login process using the form with which you attempted login before the page refresh.</div>")});</script>';
            }
        }
        
        if (RM_Utilities::fatal_errors()) {
            ob_start();
            include_once RM_ADMIN_DIR . 'views/template_rm_cant_continue.php';
            $html = ob_get_clean();
            return $html;
        }
        
        $xml_loader = RM_XML_Loader::getInstance(plugin_dir_path(__FILE__) . 'rm_config.xml');
       
        $request = new RM_Request($xml_loader);
        $request->setReqSlug('rm_login_form', true);

        $params = array('request' => $request, 'xml_loader' => $xml_loader,'attr'=>$attributes);
        $this->controller = new RM_Main_Controller($params);
        return $this->controller->run();
    }

    public function rm_user_form_render($attribute) {
        self::$form_counter++;
        $this->disable_cache();
        if (RM_Utilities::fatal_errors()) {
            ob_start();
            include_once RM_ADMIN_DIR . 'views/template_rm_cant_continue.php';
            $html = ob_get_clean();
            return $html;
        }
        $xml_loader = RM_XML_Loader::getInstance(plugin_dir_path(__FILE__) . 'rm_config.xml');
        $form_id= $attribute['id'];
        $request = new RM_Request($xml_loader);
        
        if(!self::$success_form && isset($request->req['rm_success']) && $request->req['rm_success']=="1" && !empty($form_id) && isset($request->req['rm_form_id']) && $form_id==$request->req['rm_form_id']){
            self::$success_form= true;
            $form = new RM_Forms();
            $form->load_from_db($form_id);
            $form_options= $form->form_options;
            $html = "<div class='rm-post-sub-msg'>";
            $sub_id = isset($request->req['rm_sub_id']) ? absint($request->req['rm_sub_id']) : 0;
            $html .= $form_options->form_success_message != "" ? apply_filters('rm_form_success_msg',$form_options->form_success_message,$form_id,$sub_id) : $form->form_name . " Submitted ";
            $html .= '</div>';
            return $html;
        }
        $request->setReqSlug('rm_user_form_process', true);
        $params = array('request' => $request, 'xml_loader' => $xml_loader, 'form_id' => isset($attribute['id']) ? $attribute['id'] : null,'force_enable_multiform'=>true);
       
        if(isset($attribute['prefill_form']))
            $request->setReqSlug('rm_user_form_edit_sub', true);
        
        
        $this->controller = new RM_Main_Controller($params);
        return $this->controller->run();
    }
    
    // Set flag to notify third party caching plugins to not to cache this page.
    // Honoring this flag is up to the cache provider plugin.
    protected function disable_cache()
    {        
        if(!defined('DONOTCACHEPAGE'))
            define( 'DONOTCACHEPAGE', true );
    }

    public function register_otp_widget() {
        register_widget('RM_OTP_Widget');
    }
    
    public function register_login_btn_widget()
    {  
        register_widget('RM_Login_Btn_Widget');
    }
    
    public function register_form_widget()
    {
        register_widget('RM_Form_Widget');
    }

    function execute_login() {
        $xml_loader = RM_XML_Loader::getInstance(plugin_dir_path(__FILE__) . 'rm_config.xml');

        $request = new RM_Request($xml_loader);
        $request->setReqSlug('rm_login_form', true);

        $params = array('request' => $request, 'xml_loader' => $xml_loader);
        $this->controller = new RM_Main_Controller($params);
        return $this->controller->run();
    }

    public function cron() {
        RM_DBManager::delete_front_user(1, 'h');
    }

    public function render_embed() {
        //Set X-Frame-Options to allow
        @header('X-Frame-Options: GOFORIT');
        $id = $_GET['form_id'];
        ?>
        <pre class="rm-pre-wrapper-for-script-tags"><script type="text/javascript">
            var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
        </script></pre>
        <?php
        do_action('wp_head');
        define('RM_AJAX_REQ', true);
        echo '<div class="rm_embedeed_form">' . $this->do_shortcode("[RM_Form id='$id']") . '</div>';
        die;
    }

    public function do_shortcode($content, $ignore_html = false) {
        if (has_shortcode($content,'RM_Form') || has_shortcode($content,'CRF_Login') || has_shortcode($content,'CRF_Form') || has_shortcode($content,'CRF_Submissions') || has_shortcode($content,'RM_Users') || has_shortcode($content,'RM_Front_Submissions')){
            return do_shortcode($content, $ignore_html);
        }
        return $content;
    }

    public function floating_action() {
        $xml_loader = RM_XML_Loader::getInstance(plugin_dir_path(__FILE__) . 'rm_config.xml');

        $request = new RM_Request($xml_loader);
        $request->setReqSlug('rm_front_fab', true);

        $params = array('request' => $request, 'xml_loader' => $xml_loader);
        $this->controller = new RM_Main_Controller($params);
        return $this->controller->run();
        
    }
    
     public function rm_user_list($attribute){ 
        if (RM_Utilities::fatal_errors()) {
            ob_start();
            include_once RM_ADMIN_DIR . 'views/template_rm_cant_continue.php';
            $html = ob_get_clean();
            return $html;
        }
        $xml_loader = RM_XML_Loader::getInstance(plugin_dir_path(__FILE__) . 'rm_config.xml');
 
        $request = new RM_Request($xml_loader);
        $request->setReqSlug('rm_front_user_list', true);
        
        $params = array('request' => $request, 'xml_loader' => $xml_loader,'attribute'=>$attribute);
        
        $this->controller = new RM_Main_Controller($params);
        return $this->controller->run();
    }
    
    public function rm_mark_email_read() { 
        //Safety check that it is indeed invoked by WP ajax call
        if (defined('DOING_AJAX') && DOING_AJAX) {
            if(isset($_POST['action'], $_POST['rm_email_id']) && $_POST['action'] == 'rm_mark_email_read') {
                $email_id = $_POST['rm_email_id'];
                $front_service = new RM_Front_Service;        
                $front_service->mark_email_read($email_id);
            }
            wp_die();
        }
    }
    
    public function register_stat_ids() { 
        $result = array();
        if(isset($_POST['form_ids'])) {            
            
            $form_ids = $_POST['form_ids'];
            
            if(is_array($form_ids) && count($form_ids) > 0) {
                $front_form_service = new RM_Front_Form_Service;            
                foreach($form_ids as $form_uid) {
                    $form_id = explode("_", $form_uid);
                    if(count($form_id) == 3) {
                        $form_id = intval($form_id[1]);                                                
                        $result[$form_uid] = $front_form_service->create_stat_entry(array('form_id' => $form_id));
                    }                
                }
            }
        }
        echo json_encode($result);
        wp_die();
    }
    
    public function unique_field_value_check()
    { 
       if(empty($_POST['value']) || empty($_POST['field_name']))
       {
            echo json_encode(array('status'=> 'valid'));
       }
       
       $service= new RM_Front_Form_Service();  
       $field= explode('_', $_POST['field_name']);
       
       
       if($service->is_unique_field_value($field[1], $_POST['value']))
       {
            echo json_encode(array('status'=> 'valid')); 
            wp_die();
       }
       $field_model= new RM_Fields();
       $field_model->load_from_db($field[1]); 
       
       $msg= ucwords($field_model->field_label).' '.RM_UI_Strings::get("ERROR_UNIQUE");
       if($field_model->field_options->field_is_unique==1)
           $msg= $field_model->field_options->un_err_msg;
       echo json_encode(array('status'=> 'invalid','msg'=> $msg)); 
       wp_die();
    }

    public function request_non_cached_copy() {
        return;
        global $post;
        
        if( isset($_GET['rmcb']) || isset($request->req['rm_pproc']))
            return;
        
        if($post instanceof WP_Post && has_shortcode($post->post_content, 'RM_Form')) {
            $red_url = add_query_arg('rmcb', time());
            wp_redirect($red_url);
            exit();
        }
    }
    
    public function load_states(){
        if(empty($_POST['country']))
            die('Unknown country');
            
        $country= strtolower($_POST['country']);
       
        $states= array();
        if($country=="us"){
            $states= RM_Utilities::get_usa_states();
        } else if($country=="canada"){
             $states= RM_Utilities::get_canadian_provinces();
        }
        echo json_encode($states);
        
        die;
    }
    
    public function send_activation_link(){
        $user_id= absint($_POST['user_id']);
        $response= array('success'=>true);
        
        if(empty($user_id)){
            $response['success']= false;
            $response['msg']= __('No such user exists', 'registrationmagic-gold');
            echo json_encode($response);
            exit;
        }
        $user_info = get_userdata($user_id); 
        if(empty($user_info)){
            $response['success']= false;
            $response['msg']= __('No such user exists', 'registrationmagic-gold');
            echo json_encode($response);
            exit;
        }
        
        $activation_nonce= sanitize_text_field($_POST['activation_nonce']);
        if(wp_verify_nonce( $activation_nonce, 'rm_send_verification_nonce' )){
            RM_Email_Service::send_activation_link($user_id);
            
            $response['msg']= __('Verification link has been sent on your registered email account. Please check.', 'registrationmagic-gold');
        }
        else{
             $response['msg']= __('Incorrect security token. Please try after some time.', 'registrationmagic-gold');
        }
        echo json_encode($response);
        exit;
    }
    
    public function remove_expired_otp(){
        //RM_DBManager::delete_expired_otp();
        RM_DBManager::remove_expired_bans();  // Removes expired IP bans
    } 
    
    public function generate_fa_otp(){
        $response= array('status'=>true);
        $username= sanitize_text_field($_POST['username']);
        if(empty($username)){
            $response['status']= false;
        }
        $login_service= new RM_Login_Service();
        $auth_options= $login_service->get_auth_options();
        $user= null;
        if(email_exists($username)){
            $user= get_user_by('email', $username);
        }
        if(empty($user)){
            $user= get_user_by('login',$username);
        }
        $login_service->send_2fa_otp($user);
        $expired= isset($_POST['expired']) ? absint($_POST['expired']) : 0;
        if(empty($expired)){
            $response['msg']= $auth_options['otp_resent_msg'];   
        }
        else
        {
         $response['msg']= $auth_options['otp_regen_success_msg'];   
        }
        
        echo json_encode($response);
        die;
        
    }
    
    public function logs_retention(){
        $login_service= new RM_Login_Service();
        $log_options= $login_service->get_log_options();
        
    }
    
    public function load_user_registrations(){
        $front_service= new RM_Front_Service();
       
        $data = new stdClass;
        $data->is_authorized = true;
        $data->submissions = array();
        $data->form_names = array();
        $data->submission_exists = false;
        $data->total_submission_count = 0;
        $user_email = $front_service->get_user_email();
        //data for user page
        $user = get_user_by('email', $user_email);
        if ($user instanceof WP_User) {
            $data->is_user = true;
            $data->user = $user;
            $data->custom_fields = $front_service->get_custom_fields($user_email);
        } else {
            $data->is_user = false;
        }

        //For pagination of submissions
        $entries_per_page_sub = 20;
        $req_page_sub = (isset($request->req['rm_reqpage_sub']) && $request->req['rm_reqpage_sub'] > 0) ? $request->req['rm_reqpage_sub'] : 1;
        $offset_sub = ($req_page_sub - 1) * $entries_per_page_sub;

        if (isset($request->req['rm_edit_user_details'])) { 
            $form_ids = json_decode(stripslashes($request->req['form_ids']));
            $submissions = $front_service->get_latest_submission_for_user($user_email, $form_ids);
            $data->total_submission_count = $total_entries_sub = count($submissions);
            $distinct = true;
        } else { 
            $submissions = $front_service->get_submissions_by_email($user_email, $entries_per_page_sub, $offset_sub);
            $data->total_submission_count = $total_entries_sub = $front_service->get_submission_count($user_email);
            $distinct = false;
        }

        $submission_ids = array();
        if ($submissions) 
        {
            $data->submission_exists = true;
            foreach ($submissions as $submission) {
                $form_name = $front_service->get('FORMS', array('form_id' => $submission->form_id), array('%d'), 'var', 0, 1, 'form_name');
                $data->submissions[$i] = new stdClass();
                $data->submissions[$i]->submission_ids = array();
                $data->submissions[$i]->submission_id = $submission->submission_id;
                $data->submissions[$i]->submitted_on = $submission->submitted_on;
                $data->submissions[$i]->form_name = $form_name;
                $data->form_names[$submission->submission_id] = $form_name;
                $submission_ids[$i] = $front_service->get_oldest_submission_from_group($submission->submission_id);
                $i++;
            }
            $total_entries_pay = 0;
            $settings = new RM_Options;
            $data->date_format = get_option('date_format');
            $data->payments = $front_service->get_payments_by_submission_id($submission_ids, 999999, 0, null, true);
            if ($data->payments)
                foreach ($data->payments as $i => $p) {
                    if (!isset($data->form_names[$p->submission_id])) {
                        $data->form_names[$p->submission_id] = $front_service->get('FORMS', array('form_id' => $p->form_id), array('%d'), 'var', 0, 1, 'form_name');
                    }
                    $data->payments[$i]->total_amount = $settings->get_formatted_amount($data->payments[$i]->total_amount, $data->payments[$i]->currency);
                    $total_entries_pay = $i+1;
                }

            //For pagination of payments
            $entries_per_page_pay = 20;
            $req_page_pay = (isset($request->req['rm_reqpage_pay']) && $request->req['rm_reqpage_pay'] > 0) ? $request->req['rm_reqpage_pay'] : 1;
            $data->offset_pay = $offset_pay = ($req_page_pay - 1) * $entries_per_page_pay;
            $data->total_pages_pay = (int) ($total_entries_pay / $entries_per_page_pay) + (($total_entries_pay % $entries_per_page_pay) == 0 ? 0 : 1);
            $data->curr_page_pay = $req_page_pay;
            $data->starting_serial_number_pay = $offset_pay + 1;
            $data->end_offset_this_page = ($data->curr_page_pay < $data->total_pages_pay) ? $data->offset_pay + $entries_per_page_pay : $total_entries_pay;
            $data->total_pages_sub = (int) ($total_entries_sub / $entries_per_page_sub) + (($total_entries_sub % $entries_per_page_sub) == 0 ? 0 : 1);
            $data->curr_page_sub = $req_page_sub;
            $data->starting_serial_number_sub = $offset_sub + 1;
            //Pagination Ends submissions
            $data->inbox = $this->get_inbox_data($user_email, $service, $request, $params);
            include('views/my_account/registrations.php');
        } elseif ($data->is_user === true) {
            $data->payments = false;
            $data->submissions = false;
            $data->inbox = $this->get_inbox_data($user_email, $service, $request, $params);
            include('views/my_account/registrations.php');
        } 
    }
    
    public function password_recovery($attrs) {
        $xml_loader = RM_XML_Loader::getInstance(plugin_dir_path(__FILE__) . 'rm_config.xml');
        $request = new RM_Request($xml_loader);
        $request->setReqSlug('rm_login_lost_password', true);
        $params = array('request' => $request, 'xml_loader' => $xml_loader);
        $this->controller = new RM_Main_Controller($params);
        return $this->controller->run();
    }
    
    public function paypal_ipn(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $paypal_service = new RM_Paypal_Service();
            $resp = $paypal_service->callback('ipn',null,null);
        }
        die;
    }
    
    public function payment_completed_response($response,$submission,$form_id,$payment_status){
        $gopt=new RM_Options;
        $form = new RM_Forms();
        $form->load_from_db($form_id);
        $user_service = new RM_User_Services();
        $is_logging_in= false;
        // Payment completed
        if(!empty($form->form_type) && $payment_status && !is_user_logged_in()){
            //Check for user activation
            $activate_user = $form->form_options->user_auto_approval=='default' ? $gopt->get_value_of('user_auto_approval'): $form->form_options->user_auto_approval;
            if($activate_user=='yes'){
                $user = get_user_by('email',$submission->get_user_email());
                $user_service->activate_user_by_id($user->ID);
            }
            
            // Login after registration
            if(!empty($form->form_options->auto_login)){
                RM_Utilities::login_user_by_id($user->ID);
                $is_logging_in= true;
            }
        }
        
        // Success message
        $response['msg'] .= '<div id="rmform">';
        $response['msg'] .= "<br><br><div class='rm-post-sub-msg'>";
        $response['msg'] .= $form->form_options->form_success_message != "" ? apply_filters('rm_form_success_msg',$form->form_options->form_success_message,$form_id,$sub_id) : $form->get_form_name() . " ".__('Submitted','registrationmagic-gold');
        $response['msg'] .= '</div>';
        
            
        // After submission redirection
        $response['redirect']= RM_Utilities::get_form_redirection_url($form);
        $redirection_page='';
        if(!empty($response['redirect'])){
            $redirection_type = $form->get_form_redirect();
            if ($redirection_type=== "page") {
                $page_id = $form->get_form_redirect_to_page();
                $page = get_post($page_id);
                if($page instanceof WP_Post)
                    $redirection_page = $page->post_title ? $page->post_title : '#' . $page_id . ' '.__('(No Title)','registrationmagic-gold');
            } else if($redirection_type==='url') {
                    $redirection_page = $form->get_form_redirect_to_url();
            }
            if(!empty($redirection_page)){
                $response['msg'] .= '<br><span>'.RM_UI_Strings::get("MSG_REDIRECTING_TO").' '.$redirection_page.'</span>';
            }
        }
        
        if($is_logging_in && empty($redirection_page)){
            //$response['msg'] .= '<br><span>'.RM_UI_Strings::get("MSG_ASYNC_LOGIN").'</span>';
            if(empty($response['redirect'])){
                $response['reload_params'] = "?rm_success=1&rm_form_id=$form_id&rm_sub_id=".$submission->id;
            }
        }
        $response['msg'] .= '</div>';
        return $response;
    }
    
    public function intercept_login(){
        $slug= isset($_POST['rm_slug']) ? sanitize_text_field($_POST['rm_slug']) : '';
        if($slug!='rm_login_form')
            return;
        
        $username = sanitize_text_field($_POST['username']);
        $login_service = new RM_Login_Service();
        $login_form= json_decode($login_service->get_form(),true);
        $user= $login_service->get_user($username);
        $auth_otp = isset($_POST['auth_otp']) ? sanitize_text_field($_POST['auth_otp']) : false;
        if(empty($auth_otp)){
            $password = sanitize_text_field($_POST['pwd']);
        }
        if(empty($user))
            return;
        $user_service= new RM_User_Services();
        if(!empty($auth_otp)){
            if($login_service->check_otp($auth_otp,$user)){
                $auth_options= $login_service->get_auth_options();
                if(!(!empty($auth_options['otp_expiry']) && $login_service->is_otp_expired($auth_otp,$user))){
                     $user_service->auto_login_by_id($user->ID);
                }
            }
            return;
        }
        
        
        $prov_acc_act= RM_Utilities::rm_is_prov_login_active($user->ID);
        $is_disabled = (int) get_user_meta($user->ID, 'rm_user_status', true);
        if($is_disabled==1 && !empty($prov_acc_act)){
            $is_disabled= false;
        }
        
        if(empty($is_disabled)){
            $applicable= $login_service->two_fact_auth_applicable($user);
            if(empty($applicable)){
                //$user = wp_signon(array('user_login'=>$user->user_login,'user_password'=>$password));
                if(wp_check_password($password,$user->user_pass,$user->ID)){
                    wp_set_auth_cookie($user->ID);
                    wp_set_current_user($user->ID);
                    do_action('rm_user_signon',$user);
                }
            }
        }
    }
}
