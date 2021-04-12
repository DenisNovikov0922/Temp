<?php
/*
 * Service class to handle Mailchimp operations
 *
 *
 */
class RM_NLetter_Service {
    
    public $nletter_active= false;
     
    public function __construct() {
      $this->nletter_active= rm_is_nletter_active();
    }
    /*
     * list all the mailing lists
     */
    public function get_list($dropdown= false) {
        global $wpdb;
        $lists= array(""=> "Select List");
        $sub_lists = get_option('newsletter_subscription_lists');
        $notoptions = wp_cache_get( 'notoptions', 'options' );

        // Check against older versions of Newsletter
        if (isset($notoptions['newsletter_subscription_lists']))
            $sub_lists = get_option('newsletter_profile');
        // Newsletter list limit is 20
        if(!defined('NEWSLETTER_PROFILE_MAX'))
            return array();
        
        for($i=1;$i<NEWSLETTER_PROFILE_MAX+1 ;$i++)
        {
            if(isset($sub_lists['list_'.$i]) && !empty($sub_lists['list_'.$i]))
            {
                $lists[$i]= $sub_lists['list_'.$i];
            }
        }
 
        if($dropdown)
        {
            $list_dropdown= array();
            foreach($lists as $key=>$value){
                $list_dropdown[$key]= $value;
            } 
            return $list_dropdown;
        }
        return $lists;
               
    }

    public function subscribe($data,$list_id)
     {
        if(empty($data['email']))
            return;
           
        if(!class_exists('NewsletterSubscription'))
            return;
        
        $newsletter_subscription = NewsletterSubscription::instance();
        // Inserting email and list IDs for newsletter subscription
        $_REQUEST['ne']= $data['email'];
        $_REQUEST['nl']= array($list_id);
        $_REQUEST['nn']= isset($data['nn'])? $data['nn']: '';
        $_REQUEST['ns']= isset($data['ns'])? $data['ns']: '';
        $_REQUEST['nx']= isset($data['nx'])? $data['nx']: '';
        for($i=1;$i<NEWSLETTER_PROFILE_MAX+1 ;$i++) {
            $_REQUEST['np' . $i]= '';
        }
        $_REQUEST['np']= ''; // Left blank intentionally to avoid warning.    
        
        // $newsletter_subscription->subscribe();
        $status = null;
        $emails = true;
        $options_profile = $newsletter_subscription->get_options('profile');
         
        // Validation
        $ip = $newsletter_subscription->get_remote_ip();
        $email = $newsletter_subscription->normalize_email(stripslashes($_REQUEST['ne']));
        $first_name = '';
        if (isset($_REQUEST['nn'])) {
            $first_name = $newsletter_subscription->normalize_name(stripslashes($_REQUEST['nn']));
        }

        $last_name = '';
        if (isset($_REQUEST['ns'])) {
            $last_name = $newsletter_subscription->normalize_name(stripslashes($_REQUEST['ns']));
        }

        $full_name = trim($first_name . ' ' . $last_name);

        //$newsletter_subscription->valid_subscription_or_die($email, $full_name, $ip);

        $opt_in = (int) $newsletter_subscription->options['noconfirmation']; // 0 - double, 1 - single
        if (!empty($newsletter_subscription->options['optin_override']) && isset($_REQUEST['optin'])) {
            switch ($_REQUEST['optin']) {
                case 'single': $opt_in = NewsletterSubscription::OPTIN_SINGLE;
                    break;
                case 'double': $opt_in = NewsletterSubscription::OPTIN_DOUBLE;
                    break;
            }
        }

        if ($status != null) {
            // If a status is forced and it is requested to be "confirmed" is like a single opt in
            // $status here can only be confirmed or not confirmed 
            // TODO: Add a check on status values
            if ($status == Newsletter::STATUS_CONFIRMED) {
                $opt_in = NewsletterSubscription::OPTIN_SINGLE;
            } else {
                $opt_in = NewsletterSubscription::OPTIN_DOUBLE;
            }
        }

        $user = $newsletter_subscription->get_user($email);

        if ($user != null) {
            // Email already registered in our database
            $newsletter_subscription->logger->info('Subscription of an address with status ' . $user->status);

            // Bounced
            // TODO: Manage other cases when added
            if ($user->status == 'B') {
                // Non persistent status to decide which message to show (error)
                $user->status = 'E';
                return $user;
            }

            // Is there any relevant data change? If so we can proceed otherwise if repeated subscriptions are disabled
            // show an already subscribed message

            if (empty($newsletter_subscription->options['multiple'])) {
                $user->status = 'E';
                return $user;
            }

            // If the subscriber is confirmed, we cannot change his data in double opt in mode, we need to
            // temporary store and wait for activation
            if ($user->status == Newsletter::STATUS_CONFIRMED && $opt_in == NewsletterSubscription::OPTIN_DOUBLE) {

                set_transient($newsletter_subscription->get_user_key($user), $_REQUEST, 3600 * 48);

                // This status is *not* stored it indicate a temporary status to show the correct messages
                $user->status = 'S';

                $newsletter_subscription->send_message('confirmation', $user);

                return $user;
            }
        }

        // Here we have a new subscription or we can process the subscription even with a pre-existant user for example
        // because it is not confirmed
        if ($user != null) {
            $newsletter_subscription->logger->info("Email address subscribed but not confirmed");
            $user = array('id' => $user->id);
        } else {
            $newsletter_subscription->logger->info("New email address");
            $user = array('email' => $email);
        }

        $user = $newsletter_subscription->update_user_from_request($user);


        $user['token'] = $newsletter_subscription->get_token();
        $ip = $newsletter_subscription->process_ip($ip);
        $user['ip'] = $ip;
        $user['geo'] = 0;
        $user['status'] = $opt_in == NewsletterSubscription::OPTIN_SINGLE ? Newsletter::STATUS_CONFIRMED : Newsletter::STATUS_NOT_CONFIRMED;

        $user['updated'] = time();

        $user = apply_filters('newsletter_user_subscribe', $user);

        $user = $newsletter_subscription->save_user($user);

        $newsletter_subscription->add_user_log($user, 'subscribe');

        // Notification to admin (only for new confirmed subscriptions)
        if ($user->status == Newsletter::STATUS_CONFIRMED) {
            do_action('newsletter_user_confirmed', $user);
            $newsletter_subscription->notify_admin($user, 'Newsletter subscription');
            //setcookie('newsletter', $user->id . '-' . $user->token, time() + 60 * 60 * 24 * 365, '/');
        }

        if ($emails) {
            $newsletter_subscription->send_message(($user->status == Newsletter::STATUS_CONFIRMED) ? 'confirmed' : 'confirmation', $user);
        }

        return $user;
     }
     
     public function get_lists_by_form_id($form_id)
     {
        $form= $this->get_form($form_id);
        $lists= array();
        if(!empty($form))
        {
            // decode form data
            $data = unserialize(base64_decode($form['data']));
            $lists= $data['settings']['lists'];
            if(is_array($lists))
                return $lists;
        }
        return $lists;
     }
     
     public function get_supported_rm_fields($form,$type,$dropdown=false)
     {
         $form_service= new RM_Services();
         $form_fields = $form_service->get_all_form_fields($form);
         
         $fields= array();
         if($dropdown):
            $fields[""]= "Select a Field";  
           
            foreach($form_fields as $form_field)
            {   
                $insert_field= false;
                
                if($type=="nn" && in_array(strtolower($form_field->field_type),array('textbox','nickname','fname')))
                {
                    $insert_field= true;
                }
                
                else if($type=="ns" && in_array(strtolower($form_field->field_type),array('textbox','nickname','lname')))
                {
                    $insert_field= true;
                }
                
                else if($type=="nx" && in_array(strtolower($form_field->field_type),array('radio','select','gender')))
                {
                    $insert_field= true;
                }
                
                if($insert_field)
                    $fields[$form_field->field_type.'_'.$form_field->field_id]= $form_field->field_label;
            }

            return $fields;
         endif;
         return $form_fields;
     }
     
     public function map_fields($request)
     {
         $data= array();
         $enable_newsletter= isset($request['enable_newsletter']) ? (int) $request['enable_newsletter'] : null;
         $newsletter_list_id= isset($request['newsletter_list_id']) ? (int) $request['newsletter_list_id'] : null;
         if(empty($newsletter_list_id) && empty($enable_newsletter))
             return null;
        
         $newsletter_f_fields= $this->get_default_fields($newsletter_list_id);
         if(count($newsletter_f_fields))
         {
             foreach($newsletter_f_fields as $key=>$value)
             {
                 $data[$key]= isset($request[$key]) ? $request[$key] : null;
             }
             
         }
         return $data;
     }
     
     /*
     * Get default fields
     */
    public function get_default_fields($request)
    {
        $data= array();
        $data['nn']= isset($request['nn']) ? $request['nn']: '';
        $data['ns']= isset($request['ns']) ? $request['ns']: '';
        $data['nx']= isset($request['nx']) ? $request['nx']: '';
        return $data;
    }
}