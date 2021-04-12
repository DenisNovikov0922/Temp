<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class_rm_services
 *
 * @author CMSHelplive
 */
class RM_Front_Form_Service extends RM_Services {

    private $user_service;

    public function __construct() {
        $this->user_service = new RM_User_Services();
    }

    public function get_user_service() {
        return $this->user_service;
    }

    private function get_user_ip() {
        switch (true) {
            case (!empty($_SERVER['HTTP_X_REAL_IP'])) : return $_SERVER['HTTP_X_REAL_IP'];
            case (!empty($_SERVER['HTTP_CLIENT_IP'])) : return $_SERVER['HTTP_CLIENT_IP'];
            case (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) :
                //This might include multiple IPs separated with comma, pick last IP in that case.
                $ips = explode(',',$_SERVER['HTTP_X_FORWARDED_FOR']);
                return trim(end($ips));
            case (!empty($_SERVER['REMOTE_ADDR'])) : return $_SERVER['REMOTE_ADDR'];
            default : return null;
        }
    }

    public function is_ip_banned($user_ip=null) {
        //return true;
        $banned_ip_formats = $this->get_setting('banned_ip');
        $banned = false;
        if($user_ip==null)
        $user_ip = $this->get_user_ip();
        
        if (!$user_ip)
            return true;
        //if ($user_ip == '::1')
          //  return false;
        
        if((bool)filter_var($user_ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6))
                $sanitized_user_ip = $user_ip;
        else
        {
            //Prepare IP address into proper format
            $ip_as_arr = explode('.', $user_ip);
            if (count($ip_as_arr) !== 4)
                return true;

            //$sanitized_user_ip = sprintf("%'03s.%'03s.%'03s.%'03s", $ip_as_arr[0], $ip_as_arr[1], $ip_as_arr[2], $ip_as_arr[3]);
            $sanitized_user_ip = sprintf("%s.%s.%s.%s", $ip_as_arr[0], $ip_as_arr[1], $ip_as_arr[2], $ip_as_arr[3]);
        }
        
        if (is_array($banned_ip_formats))
            foreach ($banned_ip_formats as $banned_ip_format) {
                if (RM_Utilities::is_banned_ip($sanitized_user_ip, $banned_ip_format)) {
                    $banned = true;
                    break;
                }
            }

        return $banned;
    }

    public function is_email_banned($email) {
        //return true;
        $banned_email_formats = $this->get_setting('banned_email');
        $banned = false;

        if (is_array($banned_email_formats))
            foreach ($banned_email_formats as $banned_email_format) {
                if (RM_Utilities::is_banned_email($email, $banned_email_format)) {
                    $banned = true;
                    break;
                }
            }

        return $banned;
    }

    public function create_stat_entry($params) {

        $form_id = (int) $params['form_id'];
        $visited_on = time();

        $user_ip = $this->get_user_ip();

        if ($user_ip == null)
            die(__('Unauthorised request. Access denied.','registrationmagic-gold'));

        if (isset($_SERVER['HTTP_USER_AGENT']))
            $ua_string = $_SERVER['HTTP_USER_AGENT'];
        else
            $ua_string = "no_user_agent_found";

        require_once plugin_dir_path(plugin_dir_path(__FILE__)) . 'external/Browser/Browser.php';

        $browser = new RM_Browser($ua_string);
        $browser_name = $browser->getBrowser();

        return RM_DBManager::insert_row('STATS', array('form_id' => $form_id, 'user_ip' => $user_ip, 'ua_string' => $ua_string, 'browser_name' => $browser_name, 'visited_on' => $visited_on), array('%d', '%s', '%s', '%s'));
    }

    //$op = update => update entry
    //$op = ban => update as banned submission
    //$op = delete =>remove stat entry
    public function update_stat_entry($stat_id, $op = 'update',$sub_id=null) {
        
        if($stat_id === '__uninit')
            return;
        
        switch ($op) {
            case 'update':
                $submitted_on = time();
                $visited_on = RM_DBManager::get_row('STATS', $stat_id);
                if ($visited_on) {
                    $diff_in_secs = $submitted_on - $visited_on->visited_on;
                    return RM_DBManager::update_row('STATS', $stat_id, array('submitted_on' => $submitted_on, 'time_taken' => $diff_in_secs,'submission_id'=>$sub_id), array('%s', '%d','%d'));
                } else
                    return false;
                break;

            case 'ban':
                return RM_DBManager::update_row('STATS', $stat_id, array('submitted_on' => 'banned'), array('%s'));
                break;

            case 'delete':
                return RM_DBManager::remove_row('STATS', $stat_id);
                break;

            default:
                return null;
        }
    }

    //Check if the form is being submitted through browser reload feature.
    public function is_browser_reload_duplication($stat_id) {
        //Not browser reload related, but if stat_id is not set then form submission is not valid or
        // it is just form creation, hence prevent submission.
        if ($stat_id === null || $stat_id === '__uninit')
            return true;

        $stat_entry = RM_DBManager::get_row('STATS', $stat_id);

        if ($stat_entry) {
            if ($stat_entry->submitted_on == null)
                return false;
            else
                return true;
        }
        return true; //No entry found in db, prevent submission.
    }

    public function is_off_limit_submission($form_id,$form_options) {
        $form_limit=$form_options->sub_limit_antispam;
        
        $submission_limit_per_ip_per_form = (int) $this->get_setting('sub_limit_antispam');
        if($form_limit != null)
        {
            $submission_limit_per_ip_per_form = (int)$form_limit;
        }
   
        if ($submission_limit_per_ip_per_form == 0)
            return false;

        //Calculate starting and ending timestamp for today.
        $N = time();
        $n = 24 * 60 * 60;
        $t = $N % $n;

        $start_ts = $N - $t;
        $end_ts = $start_ts + $n - 1;

        $ip = $this->get_user_ip();
        $res = RM_DBManager::get_generic('STATS', "COUNT(#UID#) AS `count`", "`form_id` = $form_id AND `user_ip` = '$ip' AND `submitted_on` != 'banned' AND `submitted_on` BETWEEN '$start_ts' AND '$end_ts'");

        if (!$res)
            return false;

        // IMP: Do not use '<='. As it counts already done submissions which excludes current submission.
        // If already done submissios are limit-1 then allow this one. Otherwise there will be one extra submission.
        if ((int) $res[0]->count < $submission_limit_per_ip_per_form)
            return false;
        else
            return true;
    }

    public function export_to_external_url($url, $submissions_data) {
        $exporter = new RM_Export_POST($url);
        $exporter->prepare_data($submissions_data);
        $exporter->send_data();
    }

    public function subscribe_to_mailchimp($request, $form_options_mc) {
      
        if (!isset($form_options_mc->mailchimp_mapped_email))
            return;
        $merge_fields_array = array();

        $list_id = $form_options_mc->mailchimp_list;
        $mailchimp = new RM_MailChimp_Service();
        $details = $mailchimp->get_list_field($list_id);
        if (isset($details['merge_fields'])) {

            foreach ($details['merge_fields'] as $det) {
               $mc_tag= trim($det['tag']);
                $mc_list_id_tag = $list_id . '_' . $mc_tag;
               
                $mc_list_id_tag = trim($mc_list_id_tag);
                $field_value = null;
               
                if (isset($form_options_mc->mailchimp_relations->$mc_list_id_tag)) {
  
                    $field_tag_id = $form_options_mc->mailchimp_relations->$mc_list_id_tag;

                    if ($det['type'] == 'dropdown' || $det['type'] == 'radio') {

                        foreach ($det['options']['choices'] as $choice) {
                            if (isset($request[$field_tag_id]) && ($choice == $request[$field_tag_id])) {
                                $field_value = $request[$field_tag_id];
                            } else {
                                
                            }
                        }
                    } elseif (isset($request[$field_tag_id])) {
                        if(is_array($request[$field_tag_id]))
                            $field_value = implode(',',$request[$field_tag_id]);
                        else
                            $field_value = $request[$field_tag_id];
                    }

                    $field_value = trim($field_value);
                } else
                    $field_value = '';
                
  
                if ($field_value != null)
                    $merge_fields_array[$mc_tag] = $field_value;
            }
            
        }
      
        if(isset($request[$form_options_mc->mailchimp_mapped_email]))
        {
             
            $email = $request[$form_options_mc->mailchimp_mapped_email];
            $mailchimp->subscribe($merge_fields_array, $email, $list_id);
        }
    }
public function subscribe_to_ccontact($request, $form_options_cc) {
        if (!isset($form_options_cc->cc_relations->email) || !isset($form_options_cc->cc_list))
            return;
        $merge_fields_array = array();
        $cconatct = new RM_Constant_Contact_Service();
        if(isset($request[$form_options_cc->cc_relations->email]))
        {
            $cconatct->subscribe($request,$form_options_cc);
        }
    }
public function subscribe_to_aweber($request, $form_options_aw) {
   
      
        if (!isset($form_options_aw->aw_relations->email) || !isset($form_options_aw->aw_list))
            return;
        $merge_fields_array = array();
            $aweber = new RM_Aweber_Service();
           
        if(isset($request[$form_options_aw->aw_relations->email]))
        {
           
            $aweber->subscribe($request,$form_options_aw);
        }
    }
    public function register_user($username, $email, $password, $is_paid = true, $user_auto_approval = null, $form_id) {
        $gopt = new RM_Options();
 
        //No password!! Generate one.
        if (!$password)
            $password = wp_generate_password(8, false, false);
        
        $user_id = wp_create_user($username, $password, $email);
        
        if (is_wp_error($user_id)) {
            foreach ($user_id as $err) {
                foreach ($err as $error) {
                    echo $error[0];
                    die;
                }
            }
        } else {
            $required_params = new stdClass();
            $required_params->email = $email;
            $required_params->username = $username;
            $required_params->password = $password;
            $required_params->form_id= $form_id;
            $rm_service= new RM_Services();
            $password_field= $rm_service->get_primary_field_options('userpassword',$form_id);
            
            if ($this->get_setting('send_password') === 'yes' || empty($password_field)) {
                RM_Email_Service::notify_new_user($required_params);
            }


            /*
             * Deactivate the user in case auto approval is off
             */
            
           $check_setting=null;
             
            if($user_auto_approval=='default')
            {
                $check_setting = $gopt->get_value_of('user_auto_approval');
            }
            else
            {
                $check_setting = $user_auto_approval;
            }
            $user_approval = $check_setting;

            if (($is_paid != true) || $user_approval != "yes") {
                $this->user_service->deactivate_user_by_id($user_id);                                
            } else {
                $this->user_service->activate_user_by_id($user_id);
            }
             
            if($user_approval != "yes" && $user_approval != "verify"){
                $link = $this->user_service->create_user_activation_link($user_id);
                $required_params->link = $link;
                $required_params->form_id = $form_id;
                RM_Email_Service::notify_admin_to_activate_user($required_params);
            } 
        }

        return $user_id;
    }
    
    public function register_user_on_custom_status($username, $email, $password, $is_paid = true, $user_auto_approval = null, $form_id) {
        $gopt = new RM_Options();
        $user_id = wp_create_user($username, $password, $email);
        
        if (is_wp_error($user_id)) {
            foreach ($user_id as $err) {
                foreach ($err as $error) {
                    echo $error[0];
                    die;
                }
            }
        } else {
            $required_params = new stdClass();
            $required_params->email = $email;
            $required_params->username = $username;
            $required_params->password = $password;
            $required_params->form_id= $form_id;
            RM_Email_Service::notify_new_user($required_params);


            /*
             * Deactivate the user in case auto approval is off
             */
            
           $check_setting=null;
             
            if($user_auto_approval=='default')
            {
                $check_setting = $gopt->get_value_of('user_auto_approval');
            }
            else
            {
                $check_setting = $user_auto_approval;
            }
            $user_approval = $check_setting;

            if (($is_paid != true) || $user_approval != "yes") {
                $this->user_service->deactivate_user_by_id($user_id);                                
            }
            else
                $this->user_service->activate_user_by_id($user_id);
             
            if($user_approval != "yes" && $user_approval != "verify"){
                $link = $this->user_service->create_user_activation_link($user_id);
                $required_params->link = $link;
                $required_params->form_id = $form_id;
                RM_Email_Service::notify_admin_to_activate_user($required_params);
            } 
        }

        return $user_id;
    }
    
    public function save_wc_meta($form_id, $data, $email){
        $user = get_user_by( 'email', $email );
        if($user){
            $userID = $user->ID;
            $service = new RM_Services();
            $fields = $service->get_all_form_fields($form_id);

            $wc_fields_arr = array();
            foreach($fields as $field){
                if($field->field_type=='WCBilling'){
                    if(isset($data[$field->field_id])){
                        $field_data=(array) $data[$field->field_id];
                        $values= $field_data['value'];
                        if(isset($values['firstname'])){
                            update_user_meta($userID, 'billing_first_name', $values['firstname']);
                        }
                        if(isset($values['lastname'])){
                            update_user_meta($userID, 'billing_last_name', $values['lastname']);
                        }
                        if(isset($values['company'])){
                            update_user_meta($userID, 'billing_company', $values['company']);
                        }
                        if(isset($values['add1'])){
                            update_user_meta($userID, 'billing_address_1', $values['add1']);
                        }
                        if(isset($values['add2'])){
                            update_user_meta($userID, 'billing_address_2', $values['add2']);
                        }
                        if(isset($values['city'])){
                            update_user_meta($userID, 'billing_city', $values['city']);
                        }
                        if(isset($values['state'])){
                            update_user_meta($userID, 'billing_state', $values['state']);
                        }
                        if(isset($values['phone'])){
                            update_user_meta($userID, 'billing_phone', $values['phone']);
                        }
                        if(isset($values['email'])){
                            update_user_meta($userID, 'billing_email', $values['email']);
                        }
                        if(isset($values['zip'])){
                            update_user_meta($userID, 'billing_postcode', $values['zip']);
                        }
                        if(isset($values['country'])){
                            $country_arr = RM_Utilities::get_countries();
                            if(!empty($values['country']) && stristr($values['country'],'[')){
                                $bill_country = str_replace(array($country_arr[$values['country']],'[',']'),'',$values['country']);
                            }
                            else
                            {
                                $bill_country= $values['country'];
                            }
                            
                            update_user_meta($userID, 'billing_country', $bill_country);
                        }                    
                    }
                }else if($field->field_type=='WCShipping'){
                    $field_data=(array) $data[$field->field_id];
                    $values= $field_data['value'];
                    if(isset($values['firstname'])){
                        update_user_meta($userID, 'shipping_first_name', $values['firstname']);
                    }
                    if(isset($values['lastname'])){
                        update_user_meta($userID, 'shipping_last_name', $values['lastname']);
                    }
                    if(isset($values['company'])){
                        update_user_meta($userID, 'shipping_company', $values['company']);
                    }
                    if(isset($values['add1'])){
                        update_user_meta($userID, 'shipping_address_1', $values['add1']);
                    }
                    if(isset($values['add2'])){
                        update_user_meta($userID, 'shipping_address_2', $values['add2']);
                    }
                    if(isset($values['city'])){
                        update_user_meta($userID, 'shipping_city', $values['city']);
                    }
                    if(isset($values['state'])){
                        update_user_meta($userID, 'shipping_state', $values['state']);
                    }
                    if(isset($values['zip'])){
                        update_user_meta($userID, 'shipping_postcode', $values['zip']);
                    }
                    if(isset($values['country'])){
                        $country_arr = RM_Utilities::get_countries();
                        if(!empty($values['country']) && stristr($values['country'],'[')){
                            $ship_country = str_replace(array($country_arr[$values['country']],'[',']'),'',$values['country']);
                        }
                        else
                        {
                            $ship_country= $values['country'];
                        }
                        update_user_meta($userID, 'shipping_country', $ship_country);
                    }
                }else if($field->field_type=='WCBillingPhone'){
                    $field_data=(array) $data[$field->field_id];
                    update_user_meta($userID, 'billing_phone', $field_data['value']);
                }
            }
        }
    }

    public function save_submission($form_id, $data, $email, $modified_by = null, $unique_token = null) {
        $submission_row = array('form_id' => $form_id, 'data' => $data, 'user_email' => $email, 'modified_by' => $modified_by);
        $submissions = new RM_Submissions;
        $submissions->set($submission_row);
        $submission_id = $submissions->insert_into_db($unique_token);
        if(!$unique_token)
            $unique_token = $submissions->get_unique_token();
        $submission_field = new RM_Submission_Fields;
        $submission_field_row['submission_id'] = $submission_id;
        $submission_field_row['form_id'] = $form_id;

        foreach ($data as $field_id => $field_data) {
            $submission_field_row['field_id'] = $field_id;
            $submission_field_row['value'] = $field_data->value;

            $submission_field->set($submission_field_row);
            $submission_field->insert_into_db(true);
        }

        return (object) array('submission_id' => $submission_id, 'token' => $unique_token);
    }
    
    //Save a edited submission
    public function save_edited_submission($form_id, $submission_id, $newdata, $email) {
        $prev_sub = new RM_Submissions;
        $prev_sub->load_from_db($submission_id);
        $old_data = $prev_sub->get_data();
        
        $submission_field_row = array();      
        
        $updated_field_ids= array();
        foreach ($old_data as $field_id => $field_data){
            if(!isset($newdata[$field_id]))
                $newdata[$field_id] = $field_data;
            elseif($newdata[$field_id]->type === 'File' || $newdata[$field_id]->type === 'image'){
                if(!$newdata[$field_id]->value)
                    $newdata[$field_id]->value = $field_data->value;
            }
            
            if(isset($newdata[$field_id])) {
                $sub_field_id = RM_DBManager::get('SUBMISSION_FIELDS',
                                                  array('submission_id' => $submission_id,
                                                        'field_id' => $field_id,
                                                        'form_id' => $form_id),
                                                  array('%d','%d','%d'),'var',0,0,'sub_field_id');
                $submission_field = new RM_Submission_Fields;
                $submission_field_row['field_id'] = $field_id;
                $submission_field_row['value'] = $newdata[$field_id]->value;
                if(!$sub_field_id) {
                    $submission_field_row['submission_id'] = $submission_id;
                    $submission_field_row['form_id'] = $form_id;
                    $submission_field->set($submission_field_row);
                    $submission_field->insert_into_db(true);
                } else {
                    $submission_field->load_from_db($sub_field_id);
                    $submission_field->set($submission_field_row);
                    $submission_field->update_into_db();
                }
                $updated_field_ids[]= $field_id;
            }
            
            
        }

        // Inserting data for newly added fields which are not present in the serialized data
        if(is_array($newdata)){
            foreach($newdata as $field_id=>$field_data){
                if(in_array($field_id,$updated_field_ids))
                        continue;
                if($newdata[$field_id]->type === 'File' || $newdata[$field_id]->type === 'image'){
                if(!$newdata[$field_id]->value)
                    $newdata[$field_id]->value = $field_data->value;
                }
                
                $sub_field_id = RM_DBManager::get('SUBMISSION_FIELDS',
                                                  array('submission_id' => $submission_id,
                                                        'field_id' => $field_id,
                                                        'form_id' => $form_id),
                                                  array('%d','%d','%d'),'var',0,0,'sub_field_id');
                $submission_field = new RM_Submission_Fields;
                $submission_field_row['field_id'] = $field_id;
                $submission_field_row['value'] = $newdata[$field_id]->value;
                if(!$sub_field_id) {
                    $submission_field_row['submission_id'] = $submission_id;
                    $submission_field_row['form_id'] = $form_id;
                    $submission_field->set($submission_field_row);
                    $submission_field->insert_into_db(true);
                } else {
                    $submission_field->load_from_db($sub_field_id);
                    $submission_field->set($submission_field_row);
                    $submission_field->update_into_db();
                }
            }
        }
        
        $front_service = new RM_Front_Service;
        $modified_by = $front_service->get_user_email();  
        //$user = 'sds';
        $child_sub = (object) array('submission_id' => $submission_id, 'token' => $prev_sub->get_unique_token());//$this->save_submission($form_id, $newdata, $email, $modified_by, $prev_sub->get_unique_token());
        //$prev_sub->set_child_id($child_sub->submission_id);
        //$prev_sub_last_child = $prev_sub->get_last_child();
        $prev_sub->set_data($newdata);
        //if($prev_sub_last_child != 0)
        //    RM_DBManager::update_submission_group_last_child($prev_sub_last_child, $child_sub->submission_id);
        //    $prev_sub->set_last_child ($child_sub->submission_id);  
        
        $prev_sub->update_into_db();
        
        $note = new RM_Notes;
        $note_data = array('submission_id' => $submission_id, 'notes' => '', 'status' => 'draft', 'type' => 'notification');
        $note->set($note_data);
        $note_id = $note->insert_into_db();        
        
        $child_sub->prev_sub_id = $submission_id;
        return $child_sub;
    }

    //Params is an object containing form_options and form name.
    //Right now this function only redirects, it may have other functionality in future, that is why redirect is just a parameter.
    public function after_submission_proc($params, $prevent_redirection = false) {
        global $wp;
        $form_options = $params->form_options;
        if(!empty($_GET['rm_pproc_id'])){
            $pproc= absint($_GET['rm_pproc_id']);
            $log = RM_DBManager::get_row('PAYPAL_LOGS', $pproc);
            $params->form_id= $log->form_id;
            $params->sub_id= $log->submission_id;
        }
        
        $msg_str = "<div class='rm-post-sub-msg'>";
        if($form_options->auto_login){
        ?>    
          <script>jQuery(document).ready(function(){rm_send_dummy_ajax_request();});</script>
        <?php   
        }
        $msg_str .= $form_options->form_success_message != "" ? apply_filters('rm_form_success_msg',$form_options->form_success_message,$params->form_id,$params->sub_id) : $params->form_name . " ".__('Submitted','registrationmagic-gold');
        if (!$prevent_redirection) {
            if ($form_options->redirection_type) {
                $redir_str = "<br>" . RM_UI_Strings::get("MSG_REDIRECTING_TO") . "<br>";
                //echo "<br>", var_dump(),die;

                if ($form_options->redirection_type === "page") {
                    $page_id = $form_options->redirect_page;
                    $page = get_post($page_id);
                    if($page instanceof WP_Post)
                    {
                        $page_title = $page->post_title ? $page->post_title : '#' . $page_id . ' '.__('(No Title)','registrationmagic-gold');
                        $redir_str .= $page_title;
                        RM_Utilities::redirect(null, true, $page_id, true); 
                       // die();
                    }
                } else {
                    $url = $form_options->redirect_url;
                    $redir_str .= $url;
                    RM_Utilities::redirect($url, false, 0, true);
                    //die();
                }
                return $msg_str . '<br><br>' . $redir_str."</div>";
            }
        }
        
        if($form_options->auto_login && !is_user_logged_in()){
            $global_option = new RM_Options;
            $gauto_approval= $global_option->get_value_of('user_auto_approval');
            $prov_act_acc= $global_option->get_value_of('prov_act_acc');
            $prov_acc_act_criteria= $global_option->get_value_of('prov_acc_act_criteria');;
            if($form_options->user_auto_approval=="yes" || (in_array($gauto_approval,array('yes','verify')) && $form_options->user_auto_approval=="default")){
                
                if(isset($_POST['rm_payment_method']) && $_POST['rm_payment_method']=="offline"){
                    return $msg_str."</div>";

                } else if(isset($_REQUEST['rm_pproc']) && $_REQUEST['rm_pproc']=="success"){
                    
                }
                else{ 
                    if(!in_array($gauto_approval,array('verify')) || (in_array($gauto_approval,array('verify')) && !empty($prov_act_acc) && ($prov_acc_act_criteria=='until_user_logsout' || $prov_acc_act_criteria=='until_act_link_expires')))
                        $msg_str .= '<div id="rm_ajax_login">'.RM_UI_Strings::get("MSG_ASYNC_LOGIN").'</div><br><br>';
                   
                }
                
                if(isset($params->form_id)){
                    $current_url = home_url(add_query_arg(array(),$wp->request)); 
                    $current_url=add_query_arg( array('rm_success'=>'1','rm_form_id'=>$params->form_id,'rm_sub_id'=>$params->sub_id), $current_url);
                    if(!in_array($gauto_approval,array('verify')) || (in_array($gauto_approval,array('verify')) && !empty($prov_act_acc) && ($prov_acc_act_criteria=='until_user_logsout' || $prov_acc_act_criteria=='until_act_link_expires'))){
                        RM_Utilities::redirect($current_url, false, 0, true);
                    }
                }
            }
            
        }
        
        return $msg_str."</div>";
    }

    public function send_user($email, $username, $password, $content) {
        $send_details = parent::get_setting('send_password');

        //echo $content;
        if ($send_details == "yes") {
            RM_Utilities::send_email($email, $content);
        }
    }

    public function register_user_old($request, $form, $is_auto_generate, $is_paid = true) {
        $gopt = new RM_Options();
        $username = $request->req['username'];

        if ($is_auto_generate !== "yes")
            $password = $request->req['password'];
        else
            $password = wp_generate_password(8, false, false);

        $primary_emails = $this->get_primary_email_fields($form->form_id);

        $request_keys = array_keys($request->req);
        $emails = array_intersect($request_keys, $primary_emails);

        foreach ($emails as $email) {
            $email_field_name = $email;
            break;
        }

        $email = $request->req[$email_field_name];

        $user_id = wp_create_user($username, $password, $email);
        if (is_wp_error($user_id)) {
            foreach ($user_id as $err) {
                foreach ($err as $error) {
                    echo $error[0];
                    die;
                }
            }
        } else {
            /*
             * User created. Check if details has to send via an email
             */
            

            $required_params = new stdClass();
            $required_params->email = $email;
            $required_params->username = $username;
            $required_params->password = $password;
            $required_params->form_id= $form->form_id;
            if ($this->get_setting('send_password') === 'yes' || $this->get_setting('auto_generated_password') === 'yes') {
                RM_Email_Service::notify_new_user($required_params);
            }

            /*
             * Deactivate the user in case auto approval is off
             */


            if (!$is_paid || ($gopt->get_value_of('user_auto_approval') != "yes" && $gopt->get_value_of('user_auto_approval')!='verify')) {

                $this->user_service->deactivate_user_by_id($user_id);
            }

            /*
             * If role is chosen by registrar
             */
            if (isset($request->req['role_as']) && !empty($request->req['role_as'])) {
                $this->user_service->set_user_role($user_id, $request->req['role_as']);
            } else {
                $tmp = $form->get_default_form_user_role();
                if (!empty($tmp)) {
                    /*
                     * Assign user role if configured by default
                     */
                    $this->user_service->set_user_role($user_id, $form->get_default_form_user_role());
                }
            }
        }

        return $user_id;
    }

    public function get_primary_email_fields($form_id) { 
        $primary_fields = RM_DBManager::get_primary_fields_by_type($form_id, 'Email');
        // print_r($primary_fields); die;
        if (is_array($primary_fields['emails']))
            $email_fields = $primary_fields['emails'];
        else
            $email_fields = array();

        return $email_fields;
    }

    public function process_payment($form, $request, $params) {
        if (isset($request->req['rm_payment_method']))
            $payment_method = $request->req['rm_payment_method'];
        else {
            $payment_gateways = $this->get_setting('payment_gateway');

            if (!$payment_gateways || count($payment_gateways) == 0)
                return;

            if (!is_array($payment_gateways))
                $payment_gateways = array($payment_gateways);

            $payment_method = $payment_gateways[0];
        }

        
        // Paypal handling
        if ($payment_method === "paypal") {
            $paypal_service = new RM_Paypal_Service();
            $pricing_details = $form->get_pricing_detail($request->req);
            if($pricing_details === null)
                return;
            $data = new stdClass();
            $data->form_id = $form->get_form_id();
            $data->submission_id = $params['sub_detail']->submission_id;
            $data->user_email = $params['user_email'];
            if ($form->get_form_type() === RM_REG_FORM)
                $data->user_id = $form->get_registered_user_id();

            return $paypal_service->charge($data, $pricing_details);
        }
        else if ($payment_method === "stripe") { 
            $stripe_service = RM_Stripe_Service::get_instance();
            $pricing_details = $form->get_pricing_detail($request->req);
            if($pricing_details === null)
                return;
            $data = new stdClass();
            $data->form_id = $form->get_form_id();
            $data->submission_id = $params['sub_detail']->submission_id;
            $data->user_email = $params['user_email'];
            return $stripe_service->show_card_elements($data,$pricing_details);
        }
        else { /* pass it on to extensions */
            $payment_done = false;
            $payment_done = apply_filters('rm_process_payment', $payment_done, $form, $request, $params);        
            return $payment_done;
        }
    }

    public function user_exists($form, $request) {
        $valid = false;
        $primary_emails = $this->get_primary_email_fields($form->get_form_id());


        $form_type = $form->get_form_type();
        //var_dump($form_type == RM_REG_FORM);
        if ($form_type == RM_REG_FORM && isset($request->req['username'])) {
            $username = $request->req['username'];
            $email_field_name = '';

            $user = get_user_by('login', $username);
            if (!empty($user)) {
                //RM_PFBC_Form::setError('form_' . $form->form_id,RM_UI_Strings::get("USERNAME_EXISTS"));
                $valid = true;
            }

            $request_keys = array_keys($request->req);
            $emails = array_intersect($request_keys, $primary_emails);

            foreach ($emails as $e) {
                $email_field_name = $e;
            }

            if (isset($request->req[$email_field_name])) {
                $email = $request->req[$email_field_name];
                $user = get_user_by('email', $email);
                if (!empty($user)) {
                    //RM_PFBC_Form::setError('form_' . $form->form_id,RM_UI_Strings::get("USEREMAIL_EXISTS"));
                    $valid = true;
                }
            }
        }

        return $valid;
    }

    public function update_user_profile($user_id_or_email, array $profile, $is_email = false) {
        
        $return = true;

        if ((int) $user_id_or_email) {
            $user_id = $user_id_or_email;
        } elseif (is_email($user_id_or_email)) {
            if ($is_email) {
                $user = get_user_by('email', $user_id_or_email);
                if (!isset($user->ID))
                    return false;
                if ((int) $user->ID)
                    $user_id = $user->ID;
                else
                    return false;
            } else
                return false;
        } else
            return false;

        $name = '';
        foreach ($profile as $type => $pr) {
            if ($type === 'Fname' || $type === 'Lname' || $type === 'BInfo'|| $type === 'Nickname'|| $type === 'SecEmail'|| $type === 'Website'){
                switch ($type) {
                    case 'Fname' :
                        $return = update_user_meta($user_id, 'first_name', $pr);
                        $name .= !empty($pr) ? $pr.' ' : '';
                        break;
                    case 'Lname' :
                        $return = update_user_meta($user_id, 'last_name', $pr);
                        $name .= !empty($pr) ? $pr : '';
                        break;
                    case 'BInfo' :
                        $return = update_user_meta($user_id, 'description', $pr);
                        break;
                    case 'Nickname' :
                        $return = update_user_meta($user_id, 'nickname', $pr);
                        break;
                    case 'SecEmail' :
                        $return = update_user_meta($user_id, 'sec_email', $pr);
                        break;
                    case 'Website' :
                        $return = wp_update_user( array( 'ID' => $user_id, 'user_url' => $pr ) );
                        break;
                }
            } else {
                $return = update_user_meta( $user_id, $type, $pr );
            }
        }
        if(!empty($name)){
            wp_update_user(array('ID'=>$user_id,'display_name'=>$name));
        }
        return $return;
    }

     public function set_properties(stdClass $options) {
        $properties = array();
        if (isset($options->field_placeholder) && null != $options->field_placeholder)
            $properties['placeholder'] = $options->field_placeholder;
        
            $properties['longDesc'] = isset($options->help_text) ? $options->help_text: '';
        if (isset($options->field_css_class) && null != $options->field_css_class)
            $properties['class'] = $options->field_css_class;
        if (isset($options->field_max_length) && null != $options->field_max_length)
            $properties['maxlength'] = $options->field_max_length;
        if (isset($options->field_timezone))
            $properties['field_time_zone'] = $options->field_timezone;
        if (isset($options->field_textarea_columns) && null != $options->field_textarea_columns)
            $properties['cols'] = $options->field_textarea_columns;
        if (isset($options->field_textarea_rows) && null != $options->field_textarea_rows)
            $properties['rows'] = $options->field_textarea_rows;
        if (isset($options->field_is_required) && null != $options->field_is_required)
            $properties['required'] = $options->field_is_required;
        if (isset($options->field_is_required_scroll))
            $properties['required_scroll'] = $options->field_is_required_scroll;
        if (isset($options->field_is_required_range))
            $properties['required_range'] = $options->field_is_required_range;
        if (isset($options->field_is_required_max_range))
            $properties['required_max_range'] = $options->field_is_required_max_range;
        if (isset($options->field_is_required_min_range))
            $properties['required_min_range'] = $options->field_is_required_min_range;
        if (isset($options->field_is_show_asterix))
            $properties['show_asterix'] = $options->field_is_show_asterix;
        if (isset($options->field_default_value) && null != $options->field_default_value)
            $properties['value'] = maybe_unserialize($options->field_default_value);
        if (isset($options->field_is_other_option) && null != $options->field_is_other_option)
            $properties['rm_is_other_option'] = $options->field_is_other_option;
        if (isset($options->style_textfield) && null != $options->style_textfield)
            $properties['style'] = $options->style_textfield;
        if (isset($options->style_label) && null != $options->style_label)
            $properties['labelStyle'] = $options->style_label;
        if (isset($options->field_validation))
            $properties['field_validation'] = $options->field_validation;
        if (isset($options->custom_validation))
            $properties['custom_validation'] = $options->custom_validation;
        if (isset($options->field_is_multiline))
            $properties['field_is_multiline'] = $options->field_is_multiline;
        if (isset($options->date_format))
            $properties['date_format'] = $options->date_format;
        if (isset($options->field_is_unique))
            $properties['field_is_unique'] = $options->field_is_unique;
       
        
        return $properties;
    }
    
    public function is_unique_field_value($field_id,$value)
    {   
        $count= RM_DBManager::count("SUBMISSION_FIELDS",array('sub_field_id' => $field_id,'value'=>$value),array('%d','%s'));
        return $count>0?false: true;
        
    }
   
}
