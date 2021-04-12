<?php

/**
 * Model class for submissions
 * 
 * @author cmshelplive
 */
class RM_Submissions extends RM_Base_Model
{

    private $submission_id;
    private $form_id;
    private $data;
    private $submitted_on;
    private $user_email;
    private $child_id;
    private $last_child;
    private $modified_by;
    //private $initialized;
    private $unique_token;
    //errors submission validation
    private $errors;
    private $is_read;
   

    public function __construct()
    {
        $this->initialized = false;
        $this->submission_id = NULL;
        $this->child_id = 0;
        $this->is_read= 0;
    }
    
     /*     * *Getters** */
    
    public static function get_identifier()
    {
        return 'SUBMISSIONS';
    }
    
    public function get_submission_id()
    {
        return $this->submission_id;
    }
    
    function get_child_id() {
        return $this->child_id;
    }
    function set_child_id($child_id) {
        $this->child_id = $child_id;
    }
    
    function get_last_child() {
        return $this->last_child;
    }

    function set_last_child($last_child) {
        $this->last_child = $last_child;
    }
    
    public function add_new_filter()
    {
       $url=$_POST['url'];
       $name=sanitize_text_field($_POST['name']);
       $gopts= new RM_Options;
       $filters=$gopts->get_value_of('rm_submission_filters');
       if($filters != null)
       $filters=  maybe_unserialize($filters);
       
       
       if(isset($filters[$name])){
             echo 'NAME_EXIST' ;die;
       }
       if(in_array($url, $filters))
       {
           echo 'URL_EXIST' ;die;
       }
       $filters[$name]= $url;
       $filters = !is_array($filters) ? array() : $filters;
       $filters[$name]= $url;
       $filters=  maybe_serialize($filters);
       $gopts->set_value_of('rm_submission_filters', $filters);
     
       echo 'saved';die;
      
    }
     public function delete_filter()
    {
       $url=$_POST['url'];
       $gopts= new RM_Options;
       $filters=$gopts->get_value_of('rm_submission_filters');
        
       if($filters != null)
       $filters=  maybe_unserialize($filters);
       if(in_array($url, $filters))
       {
           $filters = array_diff($filters, array($url));
       }
       $filters=  maybe_serialize($filters);
       
       $gopts->set_value_of('rm_submission_filters', $filters);
     
       echo 'Deleted';die;
      
    }

        
   public function get_submission_ip()
    {
       $service = new RM_Services;
       $sub_id = $service->get_oldest_submission_from_group($this->submission_id);
       $where=array('submission_id'=>$sub_id);
       $sub_ip=  RM_DBManager::get('STATS', $where,array('%d'), 'col', 0,1,'user_ip');
       return isset($sub_ip['0'])?$sub_ip['0']:null;
    }
    
    public function get_submission_browser()
    {
       $service = new RM_Services;
       $sub_id = $service->get_oldest_submission_from_group($this->submission_id);
       $where=array('submission_id'=>$sub_id);
       $sub_bw=  RM_DBManager::get('STATS', $where,array('%d'), 'col', 0,1,'browser_name');
       return isset($sub_bw['0'])?$sub_bw['0']:null;
    }
    
    public function get_subs_counts($url)
    {
        $request = new stdClass;
        parse_str($url,$req);

        $request->req=$req;
        $service=new RM_Submission_Service;
        $filter= new RM_Submission_Filter($request,$service);
     
        $total_records = RM_DBManager::get_submissions($filter->get_form(),$filter,"count(*) as count");

        if(count($total_records)>0)
            return $total_records[0]->count;
        else
            return 0;
    }
    
    public function get_form_id()
    {
        return $this->form_id;
    }

    public function get_data()
    {
        return RM_Utilities::strip_slash_array(maybe_unserialize($this->data));
    }

    public function get_submitted_on()
    {
        return $this->submitted_on;
    }

    public function get_user_email()
    {
        return trim($this->user_email);
    }
    
    public function get_unique_token()
    {
        return trim($this->unique_token);
    } 
    
    public function get_payment_status()
    {
        $service=new RM_Services;
        //First get the parent submission as edited submissions do not have any payment assosiated.
        $parent_sub_id = $service->get_oldest_submission_from_group($this->get_submission_id());        
        
        $payment = $service->get('PAYPAL_LOGS', array('submission_id' => $parent_sub_id), array('%d'), 'row', 0, 99999);
         if($payment == null)   
             return null;
         else
             return $payment->status;
    }
    public function get_note_status()
    {
        $return=array('note'=>0,
                      'message'=>0 );
        $service=new RM_Services;
        $parent_sub_id = $service->get_oldest_submission_from_group($this->get_submission_id());
        $note = $service->get('NOTES', array('submission_id' => $parent_sub_id), array('%d'), 'results', 0, 99999, '*', null, true);                
     if(isset($note))
     {
         foreach($note as $nt){
           
             $note_options=  maybe_unserialize ($nt->note_options);
             if(!isset($note_options->type) || $note_options->type == 'note' || $note_options->type == 'notification')
                 $return['note']=1;
             else
                 $return['message']=1;
         }
     }
       return $return;
    }
    
     public function is_blocked()
    {
        if($this->is_blocked_ip($this->get_submission_ip()) || $this->is_blocked_email($this->get_user_email()))
            return true;
        else
            return false;
    }
     public function is_blocked_ip($ip)
    {
         $service= new RM_Front_Form_Service;
         return $service->is_ip_banned($ip);
//       $gopt=new RM_Options;
//       $blocked_ips=array();
//       $blocked_ips=$gopt->get_value_of('banned_ip');
//       if(empty ($blocked_ips))
//           return false;
//       else
//           return in_array($ip,$blocked_ips)?true:false;
    }
    
    public function is_blocked_email($email)
    {
       $gopt=new RM_Options;
       $blocked_emails=array();
       $blocked_emails=$gopt->get_value_of('banned_email');
       if(empty ($blocked_emails))
           return false;
       else
           return in_array($email,$blocked_emails)?true:false;
    }
    public function is_have_attcahment()
    {
       $data=$this->get_data();
       foreach($data as $sub_data)
       {
           if(isset($sub_data->type) && $sub_data->type== 'File' && $sub_data->value != null)
           {
               return true;
           }
       }
     return false;
    }
    public function block_email($email)
    {
       $gopt=new RM_Options;
       $blocked_emails=$gopt->get_value_of('banned_email');
       if(empty($blocked_emails))
           $blocked_emails=array($email);
       else
           array_push ($blocked_emails, $email);
       
       update_option('rm_option_banned_email', $blocked_emails, false);
      
    }
    
    public function unblock_email($email)
    {
       $gopt=new RM_Options;
       $blocked_emails=array();
       $blocked_emails=$gopt->get_value_of('banned_email');
       if(empty($blocked_emails))
           return false;
       else
          $blocked_emails= array_diff ($blocked_emails, array($email));
         update_option('rm_option_banned_email', $blocked_emails, false);
    }
    public function block_ip($ip)
    {
       $gopt=new RM_Options;
       $blocked_ips=$gopt->get_value_of('banned_ip');
       if(empty($blocked_ips)){
           $blocked_ips=array($ip);
           $gopt->set_value_of('banned_ip',$blocked_ips);
       }
       else{
           if(!in_array($ip, $blocked_ips)){
             array_push ($blocked_ips, $ip);
             $gopt->set_value_of('banned_ip',$blocked_ips);
           }
       }
    }
    
    public function unblock_ip($ip)
    {
       
       $gopt=new RM_Options;
       $blocked_ips=array();
       $blocked_ips=$gopt->get_value_of('banned_ip');
        $ip_as_arr = explode('.', $ip);
        if (count($ip_as_arr) !== 4)
            return true;

        //$sanitized_user_ip = sprintf("%'03s.%'03s.%'03s.%'03s", $ip_as_arr[0], $ip_as_arr[1], $ip_as_arr[2], $ip_as_arr[3]);
        $sanitized_user_ip = sprintf("%s.%s.%s.%s", $ip_as_arr[0], $ip_as_arr[1], $ip_as_arr[2], $ip_as_arr[3]);
       if(empty($blocked_ips))
           return false;
       else
          $blocked_ips= array_diff ($blocked_ips, array($sanitized_user_ip));
        $gopt->set_value_of('banned_ip',$blocked_ips);
        
        do_action('rm_ip_unblocked',array('ip'=>$ip));
    }
    /*     * *Setters** */

    public function set_submission_id($submission_id)
    {
        $this->submission_id = $submission_id;
    }

    public function set_unique_token($unique_token)
    {
        $this->unique_token = $unique_token;
    }
    
    public function set_form_id($form_id)
    {
        $this->form_id = $form_id;
    }

    public function set_data($data)
    {
        $this->data = maybe_serialize($data);
    }

    public function set_submitted_on($submitted_on)
    {
        $this->submitted_on = $submitted_on;
    }

    public function set_user_email($user_email)
    {
        $this->user_email = $user_email;
    }
    
    function get_modified_by() {
        return $this->modified_by;
    }
 
    function set_modified_by($modified_by) {
        $this->modified_by = $modified_by;
    }
//    public function set($request)
//    {
//
//        foreach ($request as $property => $value)
//        {
//            if (property_exists ($this ,$property ))
//            {
//                $this->$property = $value;
//            }
//        }
//    }

    /*     * *validations** */

    private function validate_form_id()
    {
        if (empty($this->form_id))
        {
            $this->errors['FORM_ID'] = __("Form id can not be empty.",'registrationmagic-gold');
        }
    }

    private function validate_data()
    {
        if (empty($this->data))
        {
            $this->errors['DATA'] = __("No data submitted.",'registrationmagic-gold');
        }
        if (!is_array($this->data))
        {
            $this->errors['DATA'] = __("Invalid Date Format",'registrationmagic-gold');
        } $this->errors['DATA'] = __("Invalid Date Format",'registrationmagic-gold');;
    }

    private function validate_user_email()
    {
        if (empty($this->user_email))
        {
            $this->errors['USER_EMAIL'] = __("User email must not be empty.",'registrationmagic-gold');
        }
        if (!is_email($this->user_email))
        {
            $this->errors['USER_EMAIL'] = __("Invalid Email Format.",'registrationmagic-gold');
        }
    }
    
     public function is_valid()
    {
        $this->validate_form_id();
        $this->validate_data();
        $this->validate_user_email();
        
        return count($this->errors) === 0;
    }
    
    public function errors(){
        return $this->errors;
    }
    
     /*     * **Database Operations*** */

    public function insert_into_db($unique_token=null)
    {

        if (!$this->initialized)   
        {
            return false;
        }

        if ($this->submission_id)
        {
            return false;
        }
        
        if(!$unique_token)
            $this->unique_token = $this->form_id.time().rand(100,10000);
        else
            $this->unique_token = $unique_token;
        
        $data = array(            
                    'form_id' => $this->form_id,
                    'data' => $this->data,
                    'user_email' => $this->user_email,
                    'submitted_on' => gmdate('Y-m-d H:i:s'),
                    'unique_token'=> $this->unique_token,
                    'is_read'=> $this->is_read
                    );

        $data_specifiers = array(
            '%d',
            '%s',
            '%s',
            '%s',
            '%s',
            '%d'
        );

        $result = RM_DBManager::insert_row('SUBMISSIONS', $data, $data_specifiers);

        if (!$result)
        {
            return false;
        }

        $this->submission_id = $result;
        $this->last_child = $result;
        $this->update_into_db();

        return $result;
    }

    public function update_into_db()
    {  
        if (!$this->initialized)
        {   
            return false;
        }
        if (!$this->submission_id)
        {
            return false;
        }

        $data = array(            
                    'form_id' => $this->form_id,
                    'data' => $this->data,
                    'user_email' => $this->user_email,
                    'is_read'=> $this->is_read,
                    'child_id' => $this->child_id,
                    'last_child' => $this->last_child ? $this->last_child : $this->submission_id
                    );

        $data_specifiers = array(
            '%d',
            '%s',
            '%s',
            '%d',
            '%d',
            '%d'
        );
        
        $result = RM_DBManager::update_row('SUBMISSIONS', $this->submission_id, $data, $data_specifiers);

        if (!$result)
        {
            return false;
        }

        return true;
    }

    public function load_from_db($submission_id,$should_set_id=true)
    {

        $result = RM_DBManager::get_row('SUBMISSIONS', $submission_id);

        if (null !== $result)
        {       
                if($should_set_id)
                    $this->submission_id = $submission_id;
                $this->form_id = $result->form_id;
                $this->data = $result->data;
                $this->user_email = $result->user_email;
                $this->submitted_on = $result->submitted_on;
                $this->unique_token = $result->unique_token;
                $this->is_read= $result->is_read;
                $this->child_id = $result->child_id;
                $this->last_child = $result->last_child ? $result->last_child : $submission_id;
                //$this->modified_by = $result->modified_by;
                $this->initialized= true;
        } else
        {
            return false;
        }
        
        $this->initialized = true;
        return true;
    }

    public function remove_from_db()
    {
        return RM_DBManager::remove_row('SUBMISSIONS', $this->submission_id);
    }
    function get_is_read() {
        return $this->is_read;
    }

    function set_is_read($is_read) {
        $this->is_read = $is_read;
    }


}
