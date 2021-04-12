<?php

class RM_OLP_Pay_Service implements RM_Gateway_Service
{
    private $options;
    private $currency;

    function __construct() {
        $this->options= new RM_OLP_Options();
        $this->currency = $this->options->get_value_of('currency');
    }

    function getOptions() {
        return $this->options;
    }

    public function callback($payment_status,$rm_pproc_id, $note = '')
    {
        if ($rm_pproc_id)
        {
            $log_id = $rm_pproc_id;
            $log = RM_DBManager::get_row('PAYPAL_LOGS', $rm_pproc_id);
            if (!$log) return false;
        }
        else return false;
        
        switch ($payment_status)
            {
                case 'success':
                    $ex_data = maybe_unserialize($log->ex_data);
                    $pay_log = maybe_unserialize($log->log);
                    if($note)
                    {
                        if(!isset($pay_log['notes']))
                            $pay_log['notes'] = array();
                        $pay_log['notes'][] = $note;
                    }
                    $user_id = (int)$ex_data['user_id'];
                    $form_id = $log->form_id;
                    $form = new RM_Forms;
                    $form->load_from_db($form_id);
                    if($form->form_options->user_auto_approval == 'default')
                    {
                       $check_setting = $this->options->get_value_of('user_auto_approval');
                    }
                    else
                    {
                        $check_setting = $form->form_options->user_auto_approval;
                    }
                    if ($user_id && $check_setting == "yes")
                    {   
                        $user_service = new RM_User_Services();
                        $user_service->activate_user_by_id($user_id);                                    
                    }    
                    $curr_date = RM_Utilities::get_current_time();
                    RM_DBManager::update_row('PAYPAL_LOGS', $rm_pproc_id, array(
                        'status' => __( 'Completed', 'registrationmagic-gold' ),
                        'posted_date' => $curr_date,
                        'log' => maybe_serialize($pay_log)), array('%s', '%s', '%s'));
                    return true;

                case 'cancel':
                    $pay_log = maybe_unserialize($log->log);
                    if($note)
                    {
                        if(!isset($pay_log['notes']))
                            $pay_log['notes'] = array();
                        $pay_log['notes'][] = $note;
                    }
                    $curr_date = RM_Utilities::get_current_time();
                    RM_DBManager::update_row('PAYPAL_LOGS', $rm_pproc_id, array(
                        'status' => __( 'Canceled', 'registrationmagic-gold' ),
                        'posted_date' => $curr_date,
                        'log' => maybe_serialize($pay_log)), array('%s', '%s', '%s'));
                    return true;
                
                case 'refund':
                    $pay_log = maybe_unserialize($log->log);
                    if($note)
                    {
                        if(!isset($pay_log['notes']))
                            $pay_log['notes'] = array();
                        $pay_log['notes'][] = $note;
                    }
                    $curr_date = RM_Utilities::get_current_time();
                    RM_DBManager::update_row('PAYPAL_LOGS', $rm_pproc_id, array(
                        'status' => __( 'Refunded', 'registrationmagic-gold' ),
                        'posted_date' => $curr_date,
                        'log' => maybe_serialize($pay_log)), array('%s', '%s', '%s'));
                    return true;
                
                case 'pending':
                    $pay_log = maybe_unserialize($log->log);
                    if($note)
                    {
                        if(!isset($pay_log['notes']))
                            $pay_log['notes'] = array();
                        $pay_log['notes'][] = $note;
                    }
                    $curr_date = RM_Utilities::get_current_time();
                    RM_DBManager::update_row('PAYPAL_LOGS', $rm_pproc_id, array(
                        'status' => __( 'Pending', 'registrationmagic-gold' ),
                        'posted_date' => $curr_date,
                        'log' => maybe_serialize($pay_log)), array('%s', '%s', '%s'));
                    return true;
            }
    }

    public function cancel() {

    }

    public function charge($data,$pricing_details) {
        $form_id= $data->form_id;
        global $rm_form_diary;
        $form_no = $rm_form_diary[$form_id];
        
        $invoice = (string) date("His") . rand(1234, 9632);
        
        $total_amount = $pricing_details->total_price;        
        
        $log = array(); //Create a Paypal style log.
        $ex_data = array(); //Store additional data to pick up payment at a later point.
        $i = 1;
        foreach ($pricing_details->billing as $item)
        {
            $log['amount_' . $i] = $item->price;
            $i++;
        }
        
        $i = 1;
        foreach ($pricing_details->billing as $item)
        {
            $qty = isset($item->qty) ? $item->qty : 1;
            $log['quantity_' . $i] = $qty;
            $i++;
        }
        
        //After payment this should also include other details such as 
        //DD no, bank A/C txn number or cheque number and so.
        $log['total_amount'] = $total_amount;
        $log['invoice'] = $invoice;
        $log['currency_code'] = $this->currency;
        if($this->options->get_value_of('ex_olp_send_info_email') == 'yes')
            $log['info_template_used'] = $this->options->get_value_of('ex_olp_info');
        
        $ex_data['user_id'] = isset($data->user_id) ? $data->user_id : null;
        if($this->options->get_value_of('ex_olp_send_info_email') == 'yes')
            $ex_data['info_template_used'] = $this->options->get_value_of('ex_olp_info'); //currently null, it should contain data about what details provided to user at that time for payment, such as A/C number etc.
        //$ex_data['should_user_be_activated']
        
        //Insert into PayPal log table
        $curr_date = RM_Utilities::get_current_time(); //date_i18n(get_option('date_format'));

        if ($total_amount <= 0.0)
        {
            $log_entry_id = RM_DBManager::insert_row('PAYPAL_LOGS', array('submission_id' => $data->submission_id,
                        'form_id' => $form_id,
                        'invoice' => $invoice,
                        'status' => __( 'Completed', 'registrationmagic-gold' ),
                        'total_amount' => $total_amount,
                        'currency' => $this->currency,
                        'log' => maybe_serialize($log),
                        'posted_date' => $curr_date,
                        'pay_proc' => __( 'Offline', 'registrationmagic-gold' ),
                        'bill' => maybe_serialize($pricing_details),
                        'ex_data' => maybe_serialize($ex_data)), array('%d', '%d', '%s', '%s', '%f', 
                                                                       '%s', '%s', '%s', '%s', '%s', '%s'));

            return true;
        } else
        {
            $log_entry_id = RM_DBManager::insert_row('PAYPAL_LOGS', array('submission_id' => $data->submission_id,
                        'form_id' => $form_id,
                        'invoice' => $invoice,
                        'status' => __( 'Pending', 'registrationmagic-gold' ),
                        'total_amount' => $total_amount,
                        'currency' => $this->currency,
                        'log' => maybe_serialize($log),
                        'posted_date' => $curr_date,
                        'pay_proc' => 'offline',
                        'bill' => maybe_serialize($pricing_details),
                        'ex_data' => maybe_serialize($ex_data)), array('%d', '%d', '%s', '%s', '%f',
                                                                       '%s', '%s', '%s', '%s', '%s', '%s'));
        }
       
        //Send info email if configured   
        if($this->options->get_value_of('ex_olp_send_info_email') == 'yes')
        {
            $sub = RM_OLP_UI_Strings::get('OLP_INFO_EMAIL_DEF_SUB');
            $body = $this->options->get_value_of('ex_olp_info');
            if(!$body)
                $body = RM_OLP_UI_Strings::get('OLP_INFO_EMAIL_DEF_BODY');
            //available mail merge options
            $placeholders = array("{{SITE_NAME}}" => get_bloginfo('name'),
                                  "{{ADMIN_EMAIL}}" => get_bloginfo('admin_email'),
                                  "{{SITE_URL}}" => get_bloginfo('url'),
                                  "{{TOTAL_AMOUNT}}" => $this->options->get_formatted_amount($total_amount),
                                  "{{USER_EMAIL}}" => $data->user_email);

            if(isset($data->user_id))
            {
                $user_info = get_userdata($data->user_id);
                if($user_info instanceof WP_User)                
                {
                    $placeholders["{{USERNAME}}"] = $user_info->user_login;
                    $placeholders["{{USER_FIRSTNAME}}"] = $user_info->first_name;
                    $placeholders["{{USER_LASTNAME}}"] = $user_info->last_name;
                }
            }

            //replace placeholders if present
            foreach($placeholders as $placeholder => $value)
            {
                $sub = str_replace($placeholder, $value, $sub);
                $body = str_replace($placeholder, $value, $body);
            }
                        
            $params = new stdClass;
            $params->body = $body;
            $params->sub = $sub;
            $params->user_email = $data->user_email;
            $params->from = $this->options->get_value_of('senders_email_formatted');
            $params->form_id = $form_id;
            $params->sub_id = $data->submission_id;
            require_once rm_olp()->includes_dir.'/common/email_service.php';
            RM_OLP_Email_Service::notify_user_offline_payment_info($params);
//           / RM_Utilities::quick_email($data->user_email, $sub, $body, RM_OLP_EMAIL_INFO_TO_USER);
        }
        
        return false;
    }

    public function refund() {
        
    }

    public function subscribe() {
        
    }

}

