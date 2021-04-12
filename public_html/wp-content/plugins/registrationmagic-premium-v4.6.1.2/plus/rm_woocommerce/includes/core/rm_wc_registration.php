<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( !class_exists( 'RM_WC_Registration' ) ) :
    
class RM_WC_Registration extends RM_WC_Fields
{
    protected $gopts;
    
    public function __construct($form_id){
     //Load form from database
       $this->form = new RM_Forms;
       $this->form->load_from_db($form_id);
       $this->gopts = new RM_WC_Options;
    }
    
    public function render(){ 
        $rm_wc= rm_wc();
        //Filter
        $x = true;
        $x = apply_filters('rmwc_field_render_start', $x);
        if(!$x)
            return;
        if(!$this->should_form_render())
        {
            return;
        }
        
        $service = new RM_Front_Form_Service;
        $form_id= $this->form->get_form_id();
        $form_options= $this->form->get_form_options();
        //Creating Stat entry
        $params=array('form_id'=>$form_id);
         
        //Check IP ban       
        if($service->is_ip_banned())
        {
            //$validation_errors->add('RMWC_ERR_EMAIL_BANNED', RM_UI_Strings::get('MSG_BANNED'));
            return false;
        }
        
        //Checking Submission Limit
        if($service->is_off_limit_submission($this->form->get_form_id(),$form_options) )
        {
            return false;
        }
                       
        // Check if stat ID already exists in POST
        if(isset($_POST['stat_id']) && $_POST['stat_id']>0)
            $stat_id= $_POST['stat_id'];
        else
            $stat_id= "__uninit";//$service->create_stat_entry($params);
	    
        $field_html='';
        $exclude= array('price','pricev');
	global $post;
        if($post->ID==get_option( 'woocommerce_checkout_page_id' )){
            $exclude= array('price','pricev');
        }    
        $fields= $this->get_fields($exclude,true);
        $this->render_css($fields);
        $this->render_js($fields);
        $pages = $this->form->get_form_pages();
        $invalid_fields = $this->get_invalid_fields();        
        
        $form_service= new RM_Front_Form_Service();
        $form_user_role = $this->form->get_form_user_role();
        $role_picker_ele = null;
        $rm_role_override_enabled = ($this->gopts->get_value_of('woo_enable_rm_role_override') == 'yes');
        if ($rm_role_override_enabled && (!empty($form_options->form_should_user_pick) || !(isset($form_user_role) && !empty($form_user_role))))
        {
            $role_pick = $form_options->form_should_user_pick;

            if ($role_pick)
            {
                $role_as = empty($form_options->form_user_field_label) ? RM_UI_Strings::get('LABEL_ROLE_AS') : $form_options->form_user_field_label;
                
                $fffac = new RM_Form_Factory();
                $ff = $fffac->create_form($form_id);
                $role_picker_ele = new Element_Radio($role_as, "role_as", $ff->get_allowed_roles(false), array("id" => "rm_role_selector_".$form_id."_1", "class" => "rm_role_selector","style" => $form_options->style_textfield, "required" => "1","labelStyle" => $form_options->style_label));
            }
        }
        if(count($fields)>0): 
              include_once($rm_wc->includes_dir.'/views/registration.php');
        endif;
        if ($form_service->get_setting('enable_mailchimp') == 'yes' && $form_options->form_is_opt_in_checkbox == 1 && $form_options->enable_mailchimp[0] == 1) {
                
                echo RM_UI_Strings::get("MSG_SUBSCRIBE");
                if ($form_options->form_opt_in_default_state == 'Checked')
                    echo '<input type="checkbox" name="rm_subscribe_mc" value="1" checked />';
                else
                    echo '<input type="checkbox" name="rm_subscribe_mc" value="1" />';
            }

            if ($form_service->get_setting('enable_ccontact') == 'yes' && $form_options->form_is_opt_in_checkbox_cc[0] == 1 && $form_options->enable_ccontact[0] == 1) {
                echo RM_UI_Strings::get("MSG_SUBSCRIBE");
                if ($form_options->form_opt_in_default_state_cc == 'Checked')
                    echo '<input type="checkbox" name="rm_subscribe_cc" value="1" checked />';
                else
                    echo '<input type="checkbox" name="rm_subscribe_cc" value="1"  />';
            }
            if ($form_service->get_setting('enable_aweber') == 'yes' && $form_options->form_is_opt_in_checkbox_aw[0] == 1 && $form_options->enable_aweber[0] == 1) {
                echo RM_UI_Strings::get("MSG_SUBSCRIBE");
                if ($form_options->form_opt_in_default_state_aw == 'Checked')
                    echo '<input type="checkbox" name="rm_subscribe_aw" value="1" checked />';
                else
                    echo '<input type="checkbox" name="rm_subscribe_aw" value="1"  />';
            }
            echo '<input type="hidden" name="rm_cond_hidden_fields" id="rm_cond_hidden_fields" value="">';
            
            do_action('rm_show_subscribe_opt',$form_id,null,false);
            $form_recpatcha= $form_options->enable_captcha;
            if(get_option('rm_option_enable_captcha')=="yes" && $form_recpatcha=="default"){
                $captcha = new Element_Captcha();
                $js= $captcha->getJSFiles();
                foreach($js as $key=>$file){
                    wp_enqueue_script($key,$file);
                }
                $captcha->render();
            }
            
        }
    
   
    
   public function save_submission($username, $email, $validation_errors){
    if($this->form==null)
        return;   
    $form_options=$this->form->get_form_options();
    $service= new RM_Front_Form_Service();
   
    // Check for reCaptcha
    $form_recpatcha= $form_options->enable_captcha;
    if(get_option('rm_option_enable_captcha')=="yes" && $form_recpatcha=="default"){
        $version =  get_option('rm_option_recaptcha_v');
        $version= $version=='v3' ? 3 : 2;
        if($version==3){
            $privateKey = get_option('rm_option_private_key3');
        }
        else{
            $privateKey = get_option('rm_option_private_key');
        }
        $r_validation= new Validation_Captcha($privateKey,$version);
        $valid= $r_validation->isValid();
        if(empty($valid)){
            $validation_errors->add(__('Invalid reCaptcha', 'registrationmagic-gold'),__('Invalid reCaptcha', 'registrationmagic-gold'));
            return false;
        }
    }
    

    //Checking Submission Limit
    if($service->is_off_limit_submission($this->form->get_form_id(),$form_options) )
    {
        $validation_errors->add('RMWC_ERR_OFF_LIMIT_SUB', RM_UI_Strings::get('ALERT_SUBMISSIOM_LIMIT'));
        return false;
    }
    
    //Check ip ban again, as we do not want user to be created.
    if($service->is_ip_banned())
    {
        $validation_errors->add('RMWC_ERR_EMAIL_BANNED', RM_UI_Strings::get('MSG_BANNED'));
        return false;
    }
    
    //Checking Email Banned
     if ($service->is_email_banned($email))
        {
            $validation_errors->add('RMWC_ERR_EMAIL_BANNED', RM_UI_Strings::get('MSG_BANNED'));
            $service->update_stat_entry($_POST['stat_id'], 'ban');
            return false;
        } 
     $exclude= array('price','pricev','username','userpassword');  
     global $post;
        if(!empty($post) && $post->ID==get_option( 'woocommerce_checkout_page_id' )){
        //$exclude[]= 'file';
	//$exclude[]= 'image';	
         }	   
     $fields= $this->get_fields($exclude);
             
     if(count($fields)>0):
        $this->validate($fields,$validation_errors);
        $_SESSION['rm_wc_errors']= serialize($validation_errors); 
       
        if(!is_wp_error($validation_errors) || empty($validation_errors->errors)):
            $db_data= $this->get_db_data();
           
        //we have come to this point, add filter to modify customer role.
        $rm_role_override_enabled = ($this->gopts->get_value_of('woo_enable_rm_role_override') == 'yes');
        if($rm_role_override_enabled)
            add_filter('woocommerce_new_customer_data', array($this,'assign_custom_role'));
      
        $primary_email_field_id=  RM_DBManager::get_primary_fields_id($this->form->get_form_id(),'email');
        
        $db_data[$primary_email_field_id['0']]->value=$email;
            $sub_detail = $service->save_submission($this->form->get_form_id(), $db_data, $email);
            rm_wc()->set_data($email,array('field_data'=>$db_data,'form_id'=>$this->form->get_form_id()));
           //updating stats
            $service->update_stat_entry($_POST['stat_id'],'update',$sub_detail->submission_id);
        
           
            //Sending email
            if ($form_options->form_should_send_email)
            {
                $parameters = new stdClass;
                $parameters->req = $_POST;
                $parameters->db_data = $db_data; 
                $parameters->email = $email;
                $parameters->email_content = $form_options->form_email_content;
                $parameters->email_subject = $form_options->form_email_subject;
                $parameters->sub_id = $sub_detail->submission_id;
                $parameters->form_id = $this->form->get_form_id();
                RM_Email_Service::auto_responder($parameters);
            }
            
            $submissions = new RM_Submissions;
            $submissions->load_from_db($sub_detail->submission_id);
            if ($form_options->form_is_unique_token)
                $token = $sub_detail->token;
            else
                $token = null;
            
            $should_attach_sub_pdf = $service->get_setting('admin_notification_includes_pdf');
            
            $sub_pdf_loc = null;
            if($should_attach_sub_pdf == 'yes' || $form_options->enable_dpx=="1")
            {
                //Address for submission pdf to create temporarily
                $sub_pdf_loc = get_temp_dir().'rm_submission_'.$submissions->get_submission_id().'.pdf';
                //Ouput the pdf to desired location
                $service->output_pdf_for_submission($submissions,array('name' => $sub_pdf_loc, 'type' => 'F'));  
            }
            
            $parameters = new stdClass;
            $parameters->sub_data = $submissions->get_data();
            $parameters->form_name = $this->form->get_form_name();
            //Attachments for the mail
            $parameters->attachment = $sub_pdf_loc;
            $parameters->sub_id = $sub_detail->submission_id;
            $parameters->form_id = $this->form->get_form_id();
            wp_schedule_single_event( time() + 30, 'rm_wc_notify_admin',array($parameters,$token,$sub_pdf_loc,$form_options));
            wp_schedule_single_event( time() + 2, 'rm_after_submission',array($sub_detail,$_REQUEST,$sub_pdf_loc));
            wp_schedule_single_event(time()+30,'rm_wc_submission_completed',array($this->form->get_form_id(),$email,$db_data));
         
          // Submission to external URL
            if ((int) ($form_options->should_export_submissions) === 1)
            {
                $this->submit_to_external_url($email,$db_data);
            }
            
            $this->register_at_third_party($email,$primary_email_field_id);
         
        endif;
     endif;

    }

private function register_at_third_party($email,$primary_email_field_id)
{   
    $service= new RM_Front_Form_Service();
    $form_options=$this->form->get_form_options();
    $_REQUEST['Email_'.$primary_email_field_id['0']]= $email;
    if ($service->get_setting('enable_mailchimp') == 'yes' && $form_options->enable_mailchimp[0]==1)
    {
        $form_options_mc=  $form_options;

      if ($form_options_mc->form_is_opt_in_checkbox == 1 || (isset($form_options_mc->form_is_opt_in_checkbox[0]) && $form_options_mc->form_is_opt_in_checkbox[0] == 1))
            $should_subscribe = isset($_REQUEST['rm_subscribe_mc']) && $_REQUEST['rm_subscribe_mc'][0] == 1 ? 'yes' : 'no';
      else
            $should_subscribe = 'yes';
            if($should_subscribe == 'yes'){ 
            try
             {
                $service->subscribe_to_mailchimp($_REQUEST, $form_options);
             }
             catch(Exception $e){}  

             }

    }
    if ($service->get_setting('enable_ccontact') == 'yes' && $form_options->enable_ccontact[0]==1)
    {
       $form_options_cc=  $form_options;
           
        if ($form_options_cc->form_is_opt_in_checkbox_cc[0] == 1)
        $should_subscribe = isset($_REQUEST['rm_subscribe_cc']) && $_REQUEST['rm_subscribe_cc'][0] == 1 ? 'yes' : 'no';
        else
        $should_subscribe = 'yes';
      
        if($should_subscribe == 'yes'){
            try
             {
           $service->subscribe_to_ccontact($_REQUEST, $form_options);
             }
             catch(Exception $e){} 
        }
        }
          
        if ($service->get_setting('enable_aweber') == 'yes' && $form_options->enable_aweber[0]==1)
        {
            $form_options_aw=  $this->form_options;
           
          if ($form_options_aw->form_is_opt_in_checkbox_aw[0] == 1)
            $should_subscribe = isset($_REQUEST['rm_subscribe_aw']) && $_REQUEST['rm_subscribe_aw'][0] == 1 ? 'yes' : 'no';
          else
            $should_subscribe = 'yes';
      
          if($should_subscribe == 'yes'){
           
            try
             {
           $service->subscribe_to_aweber($_REQUEST, $this->get_form_options());
             }
             catch(Exception $e){}
        }
        }
        
        do_action('rm_subscribe_newsletter',$this->form->get_form_id(),$_REQUEST);
}
private function submit_to_external_url($emai,$db_data)
{ 
    $service= new RM_Front_Form_Service();
    $form_factory = new RM_Form_Factory();
    $form_id= $this->form->get_form_id();
    $fe_form = $form_factory->create_form($form_id);
    $form_options=$this->form->get_form_options();
    $service->export_to_external_url($form_options->export_submissions_to_url, $db_data);
}
public function redirect_user()
{
    if($this->form==null)
        return;   
    $form_options=$this->form->get_form_options();
    $msg_str = "<div class='rm-post-sub-msg'>";
    $msg_str .= $form_options->form_success_message != "" ? $form_options->form_success_message : $this->form->form_name . " ".__('Submitted','registrationmagic-gold');

    $redirectrion_type=$this->form->get_form_redirect();
    if ($redirectrion_type) {
                $redir_str = "<br>" . RM_UI_Strings::get("MSG_REDIRECTING_TO") . "<br>";
                
                if ($redirectrion_type === "page") {
                    $page_id = $this->form->get_form_redirect_to_page();
                    $page_title = get_post($page_id)->post_title? : '#' . $page_id . ' '.__('(No Title)','regustrationmagic-gold');
                    $redir_str .= $page_title;
                    echo $msg_str . '<br><br>' . $redir_str."</div>";
                    RM_Utilities::redirect(null, true, $page_id, true);
                } else {
                   
                    $url = $this->form->get_form_redirect_to_url();
                    $redir_str .= $url;
                    echo $msg_str . '<br><br>' . $redir_str."</div>";
                    RM_Utilities::redirect($url, false, 0, true);
                }
                
            }
            else
            {
                $url= get_permalink( get_option('woocommerce_myaccount_page_id') );
                $wc_my_ac_page = get_post(get_option('woocommerce_myaccount_page_id'));
                $page_title = $wc_my_ac_page? $wc_my_ac_page->post_title: '#' . $page_id . ' '.__('(No Title)','regustrationmagic-gold');
                echo $msg_str . '<br><br>' .__('Redirecting you to ','regustrationmagic-gold').$page_title."</div>";
                RM_Utilities::redirect($url, false, 0, true);
            }
           
}

private function after_registration($customer_id,$user_auto_approval)
{
   
    $user_service = new RM_User_Services();
    $user= get_user_by('ID', $customer_id);
    update_user_meta($customer_id, 'rm_activation_time', date('Y-m-d H:i:s'));
   
    $rm_front_form_service= new RM_Front_Form_Service();
    
    $gopt = new RM_Options();
    $check_setting=null;
         
    if($user_auto_approval=='default')
    {
        $check_setting=$gopt->get_value_of('user_auto_approval');
    }
    else
    {
        $check_setting=$user_auto_approval;
    }
    $user_approval = $check_setting;
      
    if ($user_approval != "yes") {
       $user_service->deactivate_user_by_id($user->ID); 
        do_action('rm_user_registered',$customer_id);
    }
   else
       $user_service->activate_user_by_id($user->ID);
}
   
 public function update_user_profile($customer_id){
    if($this->form==null)
      return;
    
    $db_data= $this->get_db_data();
    $profile_array= array();
    
    if(count($db_data)>0):
        foreach ($db_data as $field)
        {
          if ($field->type === 'Fname' || $field->type === 'Lname' || $field->type === 'BInfo'|| $field->type === 'Nickname'|| $field->type === 'SecEmail'|| $field->type === 'Website')
               {
                   $profile_array[$field->type] = $field->value;
               }
        }
    endif;

    if(count($profile_array)>0):
        $service= new RM_Front_Form_Service();
        $service->update_user_profile($customer_id,$profile_array,false);
    endif;
    
    $form_options=$this->form->get_form_options();
    update_user_meta($customer_id,'RM_UMETA_FORM_ID',$this->form->get_form_id());
    $this->after_registration($customer_id,$form_options->user_auto_approval);
        
   }
   
   private function should_form_render(){
       if($this->form==null)
            return false; 
        //checking IP banned!
       $service= new RM_Front_Form_Service;
        if ($service->is_ip_banned())
        {
             echo RM_UI_Strings::get('MSG_BANNED');
            return false;
        }
        //Checking form expiration
        $service = new RM_Services;
        if($service->is_form_expired($this->form))
        {
             echo RM_UI_Strings::get('MSG_FORM_EXPIRY');
             return false;
        }
              
        
        return true;
   }
   
   private function get_invalid_fields(){
       $fields = array();
       if(isset($_SESSION['rm_wc_errors'])){
            $form_errors = maybe_unserialize($_SESSION['rm_wc_errors'])->errors; 
            foreach($form_errors as $field_name => $error):
                $field_name_ex = explode('_', $field_name);
                $field_id = isset($field_name_ex[1])?$field_name_ex[1]: null;
                if((int)$field_id)
                    $fields[$field_name] = $field_id;
            endforeach;
        } 
        
        return $fields;
   }
   
   public function assign_custom_role($args) {
        $def_form_role = $this->form->get_default_form_user_role();
        if (isset($_POST['role_as']) && !empty($_POST['role_as']))
        {            
            $args['role'] = $_POST['role_as'];
        } 
        else
        {
            if (!empty($def_form_role))
            {
                $args['role'] = $def_form_role;
            }
        }
  
        return $args;
   }

}
endif;
