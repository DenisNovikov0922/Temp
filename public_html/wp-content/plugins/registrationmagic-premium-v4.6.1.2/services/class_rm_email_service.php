<?php

/**

 * Description of RM_Email_Service

 *

 * @author CMSHelplive

 */

class RM_Email_Service

{

    /*

     * Sending submission details to admin

     */                    

    public static function notify_submission_to_admin($params,$token='')

    {

        $gopt = new RM_Options();

        $rm_email= new RM_Email();

        

        $notification_msg= self::get_notification_message($params->form_id,'form_admin_ns_notification'); 

     

        $email_content='';

        $user_email = '';

        /*

         * Loop through serialized data for submission

         */

        if (is_array($params->sub_data)) {

            foreach ($params->sub_data as $field_id => $val) {

                $email_content .= '<div class="rm-email-content-row-new"> <span class="key">' . $val->label . ':</span>';



                if (is_array($val->value)) {

                    $values = '';

                    // Check attachment type field

                    if (isset($val->value['rm_field_type']) && $val->value['rm_field_type'] == 'File') {

                        unset($val->value['rm_field_type']);



                        /*

                         * Grab all the attachments as links

                         */

                        foreach ($val->value as $attachment_id) {

                            $values .= wp_get_attachment_link($attachment_id) . '    ';

                        }



                        $email_content .= '<span class="key-val">' . $values . '</span><br/>';

                    }elseif (isset($val->value['rm_field_type']) && $val->value['rm_field_type'] == 'Address'){

                        unset($val->value['rm_field_type']);

                        foreach($val->value as $in =>  $value){

                           if(empty($value))

                               unset($val->value[$in]);

                        }

                        $email_content .= '<span class="key-val">' . implode(', ', $val->value) . '</span><br/>';

                    } elseif ($val->type == 'Checkbox') {   

                         $email_content .= '<span class="key-val">' . implode(', ',RM_Utilities::get_lable_for_option($field_id, $val->value)) . '</span><br/>';

                    }

//                     elseif($val->type == 'Price' ){

//                     

//                    if (count($val->value) == 0)

//                        $email_content = null;

//                    else{

//                    $values = array();

//                        foreach ($val->value as $value){

//                            $tmp = array();

//                            $tmp = explode('&times;', $value);

//                            $values[] = implode('quantity',$tmp);

//                        }

//                        $email_content .= '<span class="key-val">'.implode(', ',$values) . '</span><br/>';

//                    }

//                }

                    

                    else {

                        $email_content .= '<span class="key-val">' . implode(', ', $val->value) . '</span><br/>';

                    }

                } else {

                    $primary_fields= RM_DBManager::get_primary_fields_id($params->form_id,'email');

                    if ($val->type == 'Email' && $user_email=='' && in_array($field_id,$primary_fields)){

                        $user_email = $val->value;

                    }

                    if ($val->type == 'Radio' || $val->type == 'Select') {   

                       $email_content .= '<span class="key-val">' . RM_Utilities::get_lable_for_option($field_id, $val->value). '</span><br/>';

                    }

                    else

                        $email_content .= '<span class="key-val">' . $val->value . '</span><br/>';

                }



                 $email_content .= "</div>";

            }

        }

        /*

          Set unique token */

        if ($token) {

            $email_content .= '<div class="rm-email-content-row"> <span class="key">' . RM_UI_Strings::get('LABEL_UNIQUE_TOKEN_EMAIL') . ':</span>';

            $email_content .= '<span class="key-val">' . $token . '</span><br/>';

            $email_content .= "</div>";

        }



        $notification_msg= str_replace('{{SUBMISSION_DATA}}', $email_content, $notification_msg);

        

        $history_content = '';

        $edd_user_content = '';

        $wc_user_content = '';

        $rm_user_content = '';

        if($user_email!=''){

            //Submission History Start

            $service = new RM_Services();

            $submissions = $service->get_recent_submissions_for_user($user_email);

            $history_content = '<h3>'.__('User Submission History','registrationmagic-gold').'</h3>';

            if(count($submissions)>1){

                $i=0;

                foreach ($submissions as $submission){

                    if($i>0 && $i<6 && $submission->child_id==0){

                        $submission_arr = unserialize($submission->data);

                        //echo '<pre>';print_r($submission_arr);echo '</pre>';

                        $email_history_content = '';

                        foreach ($submission_arr as $field_id => $val) {

                            $email_history_content .= '<div class="rm-email-content-row"> <span class="key">' . $val->label . ':</span>';



                            if (is_array($val->value)) {

                                $values = '';

                                if (isset($val->value['rm_field_type']) && $val->value['rm_field_type'] == 'Address'){

                                    unset($val->value['rm_field_type']);

                                    foreach($val->value as $in =>  $value){

                                       if(empty($value))

                                           unset($val->value[$in]);

                                    }

                                    $email_history_content .= '<span class="key-val">' . implode(', ', $val->value) . '</span><br/>';

                                } elseif ($val->type == 'Checkbox') {   

                                     $email_history_content .= '<span class="key-val">' . implode(', ',RM_Utilities::get_lable_for_option($field_id, $val->value)) . '</span><br/>';

                                } else {

                                    $email_history_content .= '<span class="key-val">' . implode(', ', $val->value) . '</span><br/>';

                                }

                            } else {

                                if ($val->type == 'Radio' || $val->type == 'Select') {   

                                   $email_history_content .= '<span class="key-val">' . RM_Utilities::get_lable_for_option($field_id, $val->value). '</span><br/>';

                                } else {

                                    $email_history_content .= '<span class="key-val">' . $val->value . '</span><br/>';

                                }



                            }

                            $email_history_content .= "</div>";

                        }

                        

                        $history_content .= "<span style='width: 350px; display: block;' ><strong>".__('Submitted on','registrationmagic-gold').": ".date('F j, Y', strtotime($submission->submitted_on))."</strong><br>".$email_history_content."<hr></span>";

                    }

                    $i++;

                }

            }else{

                $history_content .= "<p>".__('No previous submissions from this user.','registrationmagic-gold')."</p>";

            }

            //Submission History End

            

            //EDD History Start

            $edd_user_content = '';

            if ( class_exists( 'Easy_Digital_Downloads' ) ){

                $edd_user_details = $service->get_edd_user_details($user_email);

                $edd_user_content = '<h3>'.__("EDD Details",'registration-gold').'</h3>';

                $edd_user_content .= '<p><strong>'.__("Customer Details",'registration-gold').':</strong></p>';

                if(!empty($edd_user_details)){

                    $edd_payment_details = $service->get_recent_edd_orders_for_user($edd_user_details->payment_ids);

                    $edd_payment_content = '<p><strong>'.__("Payment History",'registration-gold').':</strong></p>';

                    $edd_payment_content .= "<table border='1' cellpadding='10' cellspacing='0' max-width='600' width='100%' align='center' style='border: 1px solid #d0d0d0;border-collapse: collapse;'><tbody align='center'><tr><th>ID</th><th>".__("Details",'registration-gold')."</th><th>".__("Date",'registration-gold')."</th><th>".__("Amount",'registration-gold')."</th><th>".__("Status",'registration-gold')."</th></tr>";

                    foreach ($edd_payment_details as $edd_payment_detail){

                        $edd_payment_content .= "<tr><td>".$edd_payment_detail['ID']."</td><td><a href='".admin_url()."edit.php?post_type=download&page=edd-payment-history&view=view-order-details&id=".$edd_payment_detail['ID']."'>".__("View Order Details",'registration-gold')."</a></td><td>".date('F j, Y', strtotime($edd_payment_detail['date']))."</td><td>".$edd_payment_detail['currency'].' '.number_format($edd_payment_detail['amount'],2)."</td><td>".$edd_payment_detail['status']."</td></tr>";

                    }

                    $edd_payment_content .= "</tbody ></table>";



                    $edd_user_content .= "<table border='1' cellpadding='10' cellspacing='0' max-width='600' width='100%' align='center' style='border: 1px solid #d0d0d0;border-collapse: collapse;'><tbody align='center'><tr><th>".__("Name",'registration-gold')."</th><th>".__("Purchases",'registration-gold')."</th><th>".__("Total Spent",'registration-gold')."</th><th>".__("Date Created",'registration-gold')."</th></tr>";

                    $edd_user_content .= "<tr><td><a href='".admin_url()."edit.php?post_type=download&page=edd-customers&view=overview&id=".$edd_user_details->id."'>".$edd_user_details->name."</a></td><td>".$edd_user_details->purchase_count."</td><td>".edd_get_currency().' '.number_format($edd_user_details->purchase_value,2)."</td><td>".date('F j, Y', strtotime($edd_user_details->date_created))."</td></tr>";

                    $edd_user_content .= "</tbody></table>";

                    $edd_user_content .= "<p>".__("Note: The total value reflects default currency set in your dashboard. For earnings in other currencies, see the table below",'registration-gold')."</p>";



                    $edd_user_content .= $edd_payment_content;

                }else{

                    $edd_user_content .= "<p>".__("No customer record found for this user.",'registration-gold')."</p>";

                    $edd_user_content .= '<p><strong>'.__("Payment History",'registration-gold').':</strong></p>';

                    $edd_user_content .= "<p>".__("No payment records found for this user.",'registration-gold')."</p>";

                }

            }                

            //EDD History End

            

            //WooCommerce History Start

            //$customer = new WC_Customer( $user_details_by_email->ID );

            $user_details_by_email = get_user_by( 'email', $user_email );

            $wc_user_content = '';

            if ( class_exists( 'WooCommerce' ) && function_exists('wc_get_orders')){

                $wc_payment_details= wc_get_orders(array('email'=>$user_email));

                $wc_order_content = '<p><strong>'.__("Order History",'registration-gold').':</strong></p>';

                $wc_order_total = 0;

                $wc_item_total = 0;

                $skip_wc_email= false;

                if(!empty($wc_payment_details)){

                    $wc_order_content .= "<table border='1' cellpadding='10' cellspacing='0' max-width='600' width='100%' align='center' style='border: 1px solid #d0d0d0;border-collapse: collapse;'><tbody align='center'><tr><th>ID</th><th>".__("Date",'registration-gold')."</th><th>Total</th><th>".__("Status",'registration-gold')."</th></tr>";

                    foreach ($wc_payment_details as $wc_payment_detail){

                        if(!method_exists($wc_payment_detail,'get_date_created') || !method_exists($wc_payment_detail,'get_total') || !method_exists($wc_payment_detail,'get_items') || !method_exists($wc_payment_detail,'get_quantity')){

                            $skip_wc_email= true;

                            break;

                        }

                        $wc_date_object = $wc_payment_detail->get_date_created();

                        $wc_order_content .= "<tr><td><a href='".admin_url()."post.php?post=".$wc_payment_detail->get_id()."&action=edit'>".$wc_payment_detail->get_id()."</a></td><td>".$wc_date_object->date('F j, Y')."</td><td>".$wc_payment_detail->get_currency().' '.number_format($wc_payment_detail->get_total(),2)."</td><td>". ucfirst($wc_payment_detail->get_status())."</td></tr>";

                        if($wc_payment_detail->get_status()=='completed'){

                            $wc_order_total += $wc_payment_detail->get_total();



                            $wc_item_details = $wc_payment_detail->get_items();

                            foreach($wc_item_details as $wc_item_detail){

                                $wc_item_total += $wc_item_detail->get_quantity();

                            }

                        }

                    }

                    $wc_order_content .= "</tbody></table>";

                }else{

                    $wc_order_content .= "<p>".__("No previous orders found for this user.",'registration-gold')."</p>";

                }



                $wc_user_content = '<h3>'.__("Woocommerce Details",'registration-gold').'</h3>';

                $wc_user_content .= '<p><strong>'.__("Customer Details",'registration-gold').':</strong></p>';

                if(!empty($user_details_by_email)){

                    $wc_user_content .= "<table border='1' cellpadding='10' cellspacing='0' max-width='600' width='100%' align='center' style='border: 1px solid #d0d0d0;border-collapse: collapse;'><tbody align='center'><tr><th>".__("Name",'registration-gold')."</th><th>".__("Orders Placed",'registration-gold')."</th><th>".__("Products Purchased",'registration-gold')."</th><th>".__("Total Spent",'registration-gold')."</th></tr>";

                    $wc_user_content .= "<tr><td><a href='".admin_url()."admin.php?page=rm_user_view&user_id=".$user_details_by_email->ID."'>".$user_details_by_email->display_name."</a></td><td>".count($wc_payment_details)."</td><td>".$wc_item_total."</td><td>".get_woocommerce_currency_symbol().' '.number_format($wc_order_total,2)."</td></tr>";

                    $wc_user_content .= "</tbody></table>";

                    $wc_user_content .= "<p>Note: ".__("The total value reflects default currency set in your dashboard. For earnings in other currencies, see the table below",'registration-gold')."</p>";

                }else{

                    $wc_user_content .= "<p>".__("This user is not registered on the site.",'registration-gold')."</p>";

                }



                $wc_user_content .= $wc_order_content;

                if($skip_wc_email){

                    $wc_user_content='';                }

            }

            //echo '<pre>';print_r($wc_payment_details); echo '</pre>';

            //WooCommerce History End

            

            //RM History Start

            //echo '<pre>';print_r($submissions); echo '</pre>';

            $rm_submission_content = '<p><strong>'.__("Submission History",'registration-gold').':</strong></p>';

            $rm_submission_content .= "<table border='1' cellpadding='10' cellspacing='0' max-width='600' width='100%' align='center' style='border: 1px solid #d0d0d0;border-collapse: collapse;'><tbody align='center'><tr><th>".__("ID",'registration-gold')."</th><th>".__("Form Name",'registration-gold')."</th><th>".__("Submitted On",'registration-gold')."</th><th>".__("Details",'registration-gold')."</th></tr>";

            

            $rm_payment_str = '';

            $rm_order_total = 0;

            $submission_count = 0;

            foreach ($submissions as $submission){

                if($submission->child_id==0){

                    $form_details= new RM_Forms();

                    $form_results = $form_details->load_from_db($submission->form_id);

                    $rm_submission_content .= "<tr><td>".$submission->submission_id."</td><td><a href='".admin_url()."admin.php?page=rm_submission_manage&rm_form_id=".$submission->form_id."'>".$form_details->get_form_name()."</a></td><td>".date('F j, Y', strtotime($submission->submitted_on))."</td><td><a href='".admin_url()."admin.php?page=rm_submission_view&rm_submission_id=".$submission->submission_id."'>".__("View Details",'registration-gold')."</a></td></tr>";







                    $parent_sub_id = $service->get_oldest_submission_from_group($submission->submission_id);

                    $payment = $service->get('PAYPAL_LOGS', array('submission_id' => $parent_sub_id), array('%d'), 'row', 0, 99999);

                    if(!empty($payment)){

                        //echo '<pre>';print_r($payment); echo '</pre>';

                        $rm_payment_status = ($params->sub_id==$payment->submission_id?($payment->status=='succeeded'?__("Succeeded",'registration-gold'):__("In Progress",'registration-gold')):ucfirst($payment->status));

                        $rm_txn_id = ($payment->txn_id!='' && $payment->txn_id!=0)?$payment->txn_id:$payment->invoice;

                        $rm_payment_str .= "<tr><td>".$rm_txn_id."</td><td>".date('F j, Y', strtotime($payment->posted_date))."</td><td>".$payment->currency.' '.number_format($payment->total_amount,2)."</td><td>".$rm_payment_status."</td></tr>";

                        if(in_array(strtolower($payment->status),array('completed','succeeded'))){

                            $rm_order_total += $payment->total_amount;

                        }

                    }

                    $submission_count++;

                }

            }

            $rm_submission_content .= "</tbody></table>";

            

            $rm_payment_content = '<p><strong>'.__("Payment History",'registration-gold').':</strong></p>';

            if($rm_payment_str!=''){

                $rm_payment_content .= "<table border='1' cellpadding='10' cellspacing='0' max-width='600' width='100%' align='center' style='border: 1px solid #d0d0d0;border-collapse: collapse;'><tbody align='center'><tr><th>".__("Transaction ID/ Invoice",'registration-gold')."</th><th>".__("Date",'registration-gold')."</th><th>".__("Amount",'registration-gold')."</th><th>".__("Status",'registration-gold')."</th></tr>";

                $rm_payment_content .= $rm_payment_str;

                $rm_payment_content .= "</tbody></table>";

            }else{

                $rm_payment_content .= "<p>".__("No payment history exists for this user.",'registration-gold')."</p>";

            }

            

            $rm_user_content = '<h3>'.__('User Details','registrationmagic-gold').'</h3>';

            $rm_user_content .= '<p><strong>'.__("Account Details",'registration-gold').':</strong></p>';

            if(!empty($user_details_by_email)){

                //echo '<pre>';print_r($user_details_by_email); echo '</pre>';

                $rm_user_content .= "<table border='1' cellpadding='10' cellspacing='0' max-width='600' width='100%' align='center' style='border: 1px solid #d0d0d0;border-collapse: collapse;'><tbody align='center'><tr><th>".__("Name",'registration-gold')."</th><th>".__("Registered On",'registration-gold')."</th><th>".__("Submissions",'registration-gold')."</th><th>".__("Total Spent",'registration-gold')."</th></tr>";

                $rm_user_content .= "<tr><td><a href='".admin_url()."admin.php?page=rm_user_view&user_id=".$user_details_by_email->ID."'>".$user_details_by_email->display_name."</a></td><td>".date('F j, Y', strtotime($user_details_by_email->user_registered))."</td><td>".$submission_count."</td><td>".$gopt->get_value_of('currency').' '.number_format($rm_order_total,2)."</td></tr>";

                $rm_user_content .= "</tbody></table>";

                $rm_user_content .= "<p>Note: ".__("The total value reflects default currency set in your dashboard. For earnings in other currencies, see the table below.",'registration-gold')."</p>";

            }else{

                $rm_user_content .= "<p>".__("The user is currently not registered on the site. No user account data is available.",'registration-gold')."</p>";

            }

            

            $rm_user_content = $rm_user_content.$rm_submission_content.$rm_payment_content;

            //echo '<pre>';print_r($rm_payment_details); echo '</pre>';

            //RM History End

        }

        $notification_msg= str_replace('{{SUBMISSION_HISTORY}}', $history_content, $notification_msg);

        $notification_msg= str_replace('{{RM_EDD_DETAILS}}', $edd_user_content, $notification_msg);

        $notification_msg= str_replace('{{RM_WOO_DETAILS}}', $wc_user_content, $notification_msg);

        $notification_msg= str_replace('{{RM_USERDATA}}', $rm_user_content, $notification_msg);

        $notification_msg = $notification_msg.'<style></style>';

        $notification_msg= wpautop($notification_msg);

        

        

        $is_wc_fields = 0;

        

        $service = new RM_Services();

        $fields = $service->get_all_form_fields($params->form_id);

        foreach($fields as $field){

            if($field->field_type=='WCBilling'){

                $is_wc_fields = 1;

            }else if($field->field_type=='WCShipping'){

                $is_wc_fields = 1;

            }else if($field->field_type=='WCBillingPhone'){

                $is_wc_fields = 1;

            }

        }

        

        $user_details_by_email = get_user_by( 'email', $user_email );

        

        $form = new RM_Forms();

        $form->load_from_db($params->form_id);

        

        if($is_wc_fields==1 && $form->get_form_type()!=1 && !$user_details_by_email){

            $notification_msg .= $notification_msg.'<br><br>Note: Billing/ Shipping/ Phone number field was not update in customer profile since this user is not registered on your site yet.';

        }

        

        $notification_msg= do_shortcode(wpautop($notification_msg));

        $rm_email->message($notification_msg);

        // Prepare recipients



        $to = array();

        $header = '';



       

        $admin_email= $form->form_options->admin_email;

        $notification_override= $form->form_options->admin_notification;

        if(!empty($admin_email) && !empty($notification_override)){

            $to = explode(',',$admin_email);

        }

        else if ($gopt->get_value_of('admin_notification') == "yes") {

            $to = explode(',',$gopt->get_value_of('admin_email'));

        }

        

        $subject= $form->form_options->form_admin_ns_notification_sub;

        if(empty($subject))

            $subject = $params->form_name . " " . RM_UI_Strings::get('LABEL_NEWFORM_NOTIFICATION') . " ";

        $rm_email->subject($subject);

        $rm_email->useAdminFrom= false; 

        

        $from_email= $gopt->get_value_of('an_senders_email');

        $from_email= trim($from_email);

        if($from_email=="{{useremail}}"){

            $primary_fields= RM_DBManager::get_primary_fields_id($params->form_id,'email');

            if(count($primary_fields)){

                $from_email= isset($params->sub_data[$primary_fields[0]]) ? $params->sub_data[$primary_fields[0]]->value : '';

            }

        }

        $disp_name= $gopt->get_value_of('an_senders_display_name'); 

        $dname= '';

        if(stristr($disp_name, '{{user}}')){

            $sub_data= $params->sub_data;

            $first_name='';

            $last_name='';

            $user_email;

            if(!empty($sub_data)){

                foreach($sub_data as $fdata){

                     if($fdata->type=='Fname'){

                        $first_name=  $fdata->value;

                     } else if($fdata->type=='Lname'){

                         $last_name=  $fdata->value;

                     }  

                }

            }

            $dname= $first_name.' '.$last_name;

            if(trim($dname)==''){

                $primary_fields= RM_DBManager::get_primary_fields_id($params->form_id,'email');

                $dname= isset($params->sub_data[$primary_fields[0]]) ? $params->sub_data[$primary_fields[0]]->value : '';

            }

        }

        $disp_name= str_replace('{{user}}', $dname, $disp_name);

        if(empty($disp_name))

        {

            $disp_name= get_bloginfo('name', 'display');

        }



       // $from_email = $disp_name . " <" . $from_email . ">";

        $rm_email->set_from_name($disp_name);

        $rm_email->from($from_email);  

        $rm_email->attach(array($params->attachment));

        

        foreach($to as $recepient)

        {

            $rm_email->to($recepient);

            if($rm_email->send())

                $params->sent_successfully = true;     

            else

                $params->sent_successfully = false;     

            

            self::save_sent_emails($params,$rm_email,RM_EMAIL_POSTSUB_ADMIN);

            

        }

        

    }

    /*

     * Sending Username and Password credentials on new user registration.

     */

    public static function notify_new_user($params)

    {  

        // Check if it is disabled from custom filter

        $enabled = apply_filters('rm_new_user_enabled',true,$params);

        if(empty($enabled))

            return;

        $gopt = new RM_Options();

        $rm_email= new RM_Email();

        $notification_msg= self::get_notification_message($params->form_id,'form_nu_notification'); 

        $notification_msg = str_replace('{{SITE_NAME}}', get_bloginfo('name', 'display'), $notification_msg);

        $notification_msg = str_replace('%SITE_NAME%', get_bloginfo('name', 'display'), $notification_msg);

        

        $notification_msg = str_replace('{{USER_NAME}}', sanitize_text_field($params->username), $notification_msg);

        $notification_msg = str_replace('%USER_NAME%', sanitize_text_field($params->username), $notification_msg);

        

        $notification_msg = str_replace('{{USER_PASS}}', $params->password, $notification_msg);

        $notification_msg = str_replace('%USER_PASS%', $params->password, $notification_msg);

        $notification_msg = str_replace('{{SITE_URL}}',site_url(), $notification_msg);

        $notification_msg = str_replace('{{SITE_ADMIN}}',get_option('admin_email'),$notification_msg);

        $notification_msg= do_shortcode(wpautop($notification_msg));

        // Allows to edit the content just before setting the message body

        $notification_msg = apply_filters('rm_new_user_message',$notification_msg,$params);

        $rm_email->message($notification_msg);

        

        $form= new RM_Forms();

        $form->load_from_db($params->form_id);        

        $form_options= $form->form_options;

        

        $subject= $form_options->form_nu_notification_sub;

        if(empty($subject))

            $subject= RM_UI_Strings::get('MAIL_NEW_USER_DEF_SUB');

        $rm_email->subject($subject);

        $rm_email->to($params->email);

        $rm_email->from($gopt->get_value_of('senders_email_formatted'));

        $rm_email->send();

    }

    

    /*

     * Sending user activation link to admin

     */

    public static function notify_admin_to_activate_user($params)

    {

        // Check if it is disabled from custom filter

        $enabled = apply_filters('rm_user_activation_link_to_admin',true,$params);

        if(empty($enabled))

            return;

        

        $gopt = new RM_Options();

        $rm_email= new RM_Email();

        $user_email = $params->email;

        

        if(isset($params->form_id))        

        	$notification_msg= self::get_notification_message($params->form_id,'form_activate_user_notification'); 

        else	

        	$notification_msg= self::get_notification_message('social_media','form_activate_user_notification');

        

        

        $notification_msg = str_replace('{{SITE_NAME}}', get_bloginfo('name', 'display'), $notification_msg);

        $notification_msg = str_replace('%SITE_NAME%', get_bloginfo('name', 'display'), $notification_msg);

        

        if(isset($params->username)){

        $notification_msg = str_replace('{{USER_NAME}}', $params->username, $notification_msg);

        $notification_msg = str_replace('%USER_NAME%', $params->username, $notification_msg);

        }

        else{        

        $notification_msg = str_replace('{{USER_NAME}}', '', $notification_msg);

        $notification_msg = str_replace('%USER_NAME%','', $notification_msg);        

        }

       

        if(isset($params->email)){

        $notification_msg = str_replace('{{USER_EMAIL}}', $user_email, $notification_msg);

        $notification_msg = str_replace('%USER_EMAIL%', $user_email, $notification_msg);}

        else{

         $notification_msg = str_replace('{{USER_EMAIL}}', '', $notification_msg);

        $notification_msg = str_replace('%USER_EMAIL%', '', $notification_msg);

        }

         

        $notification_msg = str_replace('{{ACTIVATION_LINk}}', $params->link, $notification_msg);

        $notification_msg = str_replace('%ACTIVATION_LINk%', $params->link, $notification_msg);

        //Fix for lower case 'k'

        $notification_msg = str_replace('{{ACTIVATION_LINK}}', $params->link, $notification_msg);

        $notification_msg = str_replace('%ACTIVATION_LINK%', $params->link, $notification_msg);

        $notification_msg= do_shortcode(wpautop($notification_msg));

        $notification_msg = apply_filters('rm_user_activation_msg_to_admin',$notification_msg,$params);

        $rm_email->message($notification_msg);

        

        $form= new RM_Forms();

        $form->load_from_db($params->form_id);        

        $form_options= $form->form_options;

        

        $subject=$form_options->form_activate_user_notification_sub;

        if(empty($subject))

            RM_UI_Strings::get('MAIL_ACTIVATE_USER_DEF_SUB');

        $rm_email->subject($subject);

        $rm_email->to(get_option('admin_email'));

        $rm_email->from($gopt->get_value_of('senders_email_formatted'));

        

        if($rm_email->send())

            $params->sent_successfully = true;     

        else

            $params->sent_successfully = false;     

        

        self::save_sent_emails($params,$rm_email,RM_EMAIL_USER_ACTIVATION_ADMIN);

    }

    /*

     *  Send auto reponder message to user on new submission

     */

    public static function auto_responder($params,$token='')

    {

        //ob_start(); print_r($params); $string = ob_get_contents();ob_end_clean(); error_log($string);

        $gopt = new RM_Options();

        $rm_email= new RM_Email();



       

        $email_content = '<div class="mail-wrapper">';

        /* Preparing content for front end notification */

        $email_content .= $params->email_content . '<br><br>';

        

        // Replacing Username and password

        if(!empty($params->req['username'])){

            $email_content = str_replace('{{Username}}',$params->req['username'], $email_content);

        }

        if(!empty($params->req['pwd'])){

            $email_content = str_replace('{{UserPassword}}',$params->req['pwd'], $email_content);

        }

        /*

          Set unique token */

        if ($token) {

            $email_content .= '<div class="rm-email-content-row"> <span class="key">' . RM_UI_Strings::get('LABEL_UNIQUE_TOKEN_EMAIL') . ':</span>';

            $email_content .= '<span class="key-val">' . $token . '</span><br/>';

            $email_content .= "</div>";

        }

        foreach ($params->req as $key => $val) {

            $key_parts = explode('_', $key);

            if (!is_array($val)){

                if ($key_parts[0] == 'File' || $key_parts[0] == 'Image') {



                    $field_id = $key_parts[1];

                    //Try to find value in db_data if provided.                        

                    $values='';

                    if(isset($params->db_data, $params->db_data[$field_id]))

                    {

                        /*

                        * Grab all the attachments as links

                        */

                        if(is_array($params->db_data[$field_id]->value) && count($params->db_data[$field_id]->value)>0)

                            foreach ($params->db_data[$field_id]->value as $attachment_id) {

                                if($attachment_id != 'File')

                                $values .= wp_get_attachment_link($attachment_id) . '    ';

                            }



                    }



                    $email_content = str_replace('{{' . $key . '}}', $values, $email_content);



                }

                elseif ($key_parts[0] == 'Radio' || $key_parts[0] == 'Select') {   

                   $values = '';

                   $values =  RM_Utilities::get_lable_for_option($key_parts[1], $val);

                   $email_content = str_replace('{{' . $key . '}}', $values, $email_content);

                }

                else

                    $email_content = str_replace('{{' . $key . '}}', $val, $email_content);                   

            }

            else {

                if (isset($val['rm_field_type']) && $val['rm_field_type'] == 'Address'){

                unset($val['rm_field_type']);

                            foreach ($val as $in => $value) {

                                if (empty($value))

                                    unset($val[$in]);

                            }

                }

                elseif ($key_parts[0] == 'Checkbox') {   

                     $val = RM_Utilities::get_lable_for_option($key_parts[1], $val);

                }

                $email_content = str_replace('{{' . $key . '}}', implode(', ', $val), $email_content);

                

            }

        }



                         

        $out = array();

        $preg_result = preg_match_all('/{{(.*?)}}/', $email_content, $out);



        if ($preg_result) {

            $id_vals = array();



            foreach ($params->req as $key => $val) {

                //$val would be like '{field_type}_{field_id}'



                $key_parts = explode('_', $key);

               

                $k_c = count($key_parts);

                if ($k_c >= 2 && is_numeric($key_parts[$k_c - 1])) {

                    if (is_array($val))

                        $val = implode(", ", $val);



                    if ($key_parts[0] === 'Fname' || $key_parts[0] === 'Lname' || $key_parts[0] === 'BInfo') {

                        $id_vals[$key_parts[0]] = $val;

                    } 

                    

                    else

                        $id_vals[$key_parts[1]] = $val;

                

                }

            }



            foreach ($out[1] as $caught) {

               // ob_start(); echo 'caught'.$email_content; $string = ob_get_contents();ob_end_clean(); error_log($string);

                         

                //echo "<br>".$caught;$parameters

                $x = explode("_", $caught);

                $id = $x[count($x) - 1];

                if (is_numeric($id)) {

                    if (isset($id_vals[(int) $id]))

                        $email_content = str_replace('{{' . $caught . '}}', $id_vals[(int) $id], $email_content);

                    

                }

                else {

                    switch ($caught) {

                        case 'first_name':

                            if (isset($id_vals['Fname']))

                                $email_content = str_replace('{{' . $caught . '}}', $id_vals['Fname'], $email_content);

                            break;



                        case 'last_name':

                            if (isset($id_vals['Lname']))

                                $email_content = str_replace('{{' . $caught . '}}', $id_vals['Lname'], $email_content);

                            break;



                        case 'description':

                            if (isset($id_vals['BInfo']))

                                $email_content = str_replace('{{' . $caught . '}}', $id_vals['BInfo'], $email_content);

                            break;

                    }

                }



                //Blank the placeholder if still any remaining.

                $email_content = str_replace('{{' . $caught . '}}', '', $email_content);

            }

        }

        

        $email_content .=  "</div>";

        $email_content= do_shortcode(wpautop($email_content));

        $rm_email->message($email_content);

        // Prepare recipients

        $rm_email->subject($params->email_subject? : RM_UI_Strings::get('MAIL_REGISTRAR_DEF_SUB'));

        $rm_email->to($params->email);

        $rm_email->from($gopt->get_value_of('senders_email_formatted'));

        

        if($rm_email->send())

            $params->sent_successfully = true;     

        else

            $params->sent_successfully = false;     

        

        

        self::save_sent_emails($params,$rm_email,RM_EMAIL_AUTORESP);        

        

    }

    

    /*

     * Send notification to user as soon as account is activated.

     */

    public static function notify_user_on_activation($params)

    {

        $gopt = new RM_Options();

        $rm_email= new RM_Email();

        $notification_msg= self::get_notification_message($params->form_id,'form_user_activated_notification'); 

        $notification_msg = str_replace('{{SITE_NAME}}',get_bloginfo('name', 'display'), $notification_msg);

        $notification_msg = str_replace('%SITE_NAME%',get_bloginfo('name', 'display'), $notification_msg);

        

        $notification_msg = str_replace('{{SITE_URL}}',get_site_url(),$notification_msg);

        $notification_msg = str_replace('{{SITE_ADMIN}}',get_option('admin_email'),$notification_msg);

        $notification_msg = str_replace('%SITE_URL%',get_site_url(),$notification_msg);

        $notification_msg= do_shortcode(wpautop($notification_msg));

        $rm_email->message($notification_msg);

        $form= new RM_Forms();

        $form->load_from_db($params->form_id);        

        $form_options= $form->form_options;

        $subject= $form_options->form_user_activated_notification_sub;

        if(empty($subject))

            $subject= RM_UI_Strings::get('MAIL_ACOOUNT_ACTIVATED_DEF_SUB');

        $rm_email->subject($subject);

        $rm_email->to($params->email);

        $rm_email->from($gopt->get_value_of('senders_email_formatted'));

        

        if($rm_email->send())

            $params->sent_successfully = true;     

        else

            $params->sent_successfully = false;     

        

        self::save_sent_emails($params,$rm_email,RM_EMAIL_USER_ACTIVATED_USER);

        

    }

    

    /*

     * Quickly send generic emails, used on user view page, back-end.

     */

    public static function quick_email($params)

    {

        $gopt = new RM_Options();

        $rm_email= new RM_Email();

        

        $rm_email->message($params->message);

        $rm_email->subject($params->subject);

        $rm_email->to($params->to);

        $rm_email->from($gopt->get_value_of('senders_email_formatted'));

        

        if($rm_email->send())

            $params->sent_successfully = true;     

        else

            $params->sent_successfully = false;

        

        if(!$params->do_not_save)

        self::save_sent_emails($params,$rm_email,$params->type);

    }

    

    protected static function save_sent_emails($params,$rm_email,$type)

    {

            

        $additional_data = array();

        if(isset($params->sub_id))

            $additional_data['exdata'] = $params->sub_id;

        if(isset($params->form_id))

            $additional_data['form_id'] = $params->form_id;



        $sent_on = gmdate('Y-m-d H:i:s');  

        $form_id = null;

        $exdata = null;

        $was_sent_successfully = (isset($params->sent_successfully) && $params->sent_successfully) ? 1 : 0 ;

        if(is_array($additional_data) && count($additional_data) > 0)

        {

            if(isset($additional_data['form_id'])) $form_id = $additional_data['form_id'];

            if(isset($additional_data['exdata'])) $exdata = $additional_data['exdata'];

        }

        $row_data = array('type' => $type, 'to' => $rm_email->get_to(), 'sub' => htmlspecialchars($rm_email->get_subject()), 'body' => htmlspecialchars($rm_email->get_message()), 'sent_on' => $sent_on, 'headers' => $rm_email->get_header(), 'form_id' => $form_id,'exdata' => $exdata,'was_sent_success' => $was_sent_successfully);

        $fmts = array('%d','%s','%s','%s','%s', '%s', '%d', '%s', '%d');



        RM_DBManager::insert_row('SENT_EMAILS', $row_data, $fmts);

        

    }

    

    private static function get_notification_message($form_id,$type)

    {

        $form= new RM_Forms();

        

        if($form_id=='social_media')

        	 return self::get_default_messages($type);

        

        $form->load_from_db($form_id);

        if(isset($form->form_options->$type) && trim($form->form_options->$type)!="")

            return wpautop($form->form_options->$type);

        else

            return wpautop(self::get_default_messages($type));

    }

    

    public static function get_default_messages($type)

    {

        $email_content= '';

        if($type=="form_nu_notification")

        {

            $email_content = '<div class="mail-wrapper">'.RM_UI_Strings::get('MAIL_BODY_NEW_USER_NOTIF').'</div>';

        }elseif($type=="form_user_activated_notification")

        {

             $email_content = '<div style="font-size:14px">';

             $email_content .=  RM_UI_Strings::get('MAIL_ACCOUNT_ACTIVATED');

             $email_content .= '</div>';

        

        }elseif($type=="form_activate_user_notification")

        {

            $email_content = '<div style="font-size:14px">';

            $email_content .= '<div class="mail-wrapper" style="border: 1px solid black; padding: 20px; box-shadow: .1px .1px 8px .1px grey; font-size: 14px; font-family: monospace;"> <div class="mail_body" style="padding: 20px;">' . RM_UI_Strings::get('MAIL_NEW_USER1') . '.<br/> ' . RM_UI_Strings::get('LABEL_USER_NAME') . ' : {{USER_NAME}} <br/> ' . RM_UI_Strings::get('LABEL_USEREMAIL') . ' : {{USER_EMAIL}} <br/> <br/>' . RM_UI_Strings::get('MAIL_NEW_USER2') . '<br/> <div class="rm-btn-link" style="width: 100%; text-align: center; margin-top: 10px; margin-bottom: 15px;"><a class="rm_btn" href="{{ACTIVATION_LINk}}" style="border: 1px solid; padding: 4px; background-color: powderblue; box-shadow: 1px 1px 3px .1px;">'.__("Activate",'registration-gold').'</a></div> <div class="link-div" style="border: 1px dotted; padding: 13px; background-color: white; margin-top: 4px; width: 100%;"> ' . RM_UI_Strings::get('MAIL_NEW_USER3') . '.<br/> <a class="rm-link" href="{{ACTIVATION_LINk}}" style="color: blue; font-size: 11px;">{{ACTIVATION_LINk}}</a> </div> </div> </div>';            

            $email_content .= '</div>';

        } elseif($type=='form_admin_ns_notification')

        {

            $email_content= '{{SUBMISSION_DATA}}';

        }

        elseif($type=='act_link_message'){

            $email_content = '<div style="font-size:14px">';

            $email_content .= RM_UI_Strings::get('DEFAULT_ACT_LINK_MSG_VALUE');

            $email_content .= '</div>';

        }

        

        return $email_content;

    }

    

    public static function send_activation_link($user_id){

        // Check if activation link is configured

        $gopts= new RM_Options();

        $user_auto_approval= $gopts->get_value_of('user_auto_approval');

        

        if($user_auto_approval!='verify')

            return;

        

        $user_status= get_user_meta($user_id,'rm_user_status',true);

        if(empty($user_status))

            return;

        

        $sub_page_id = $gopts->get_value_of('front_sub_page_id');   

        $sub_page_url= get_permalink($sub_page_id);

        $random_number= wp_rand(99999,99999999);

        $hash = md5( $random_number );

        $url= add_query_arg( array(

                    'rm_user' => $user_id,

                    'rm_hash' => $hash

               ), $sub_page_url );

        $url = '<a href="'.$url.'">'.RM_UI_Strings::get('LABEL_CLICK_HERE').'</a>';

        $form_id= absint(get_user_meta($user_id,'RM_UMETA_FORM_ID',true));

   

        if(empty($form_id))

            return;

        

        $form= new RM_Forms();

        $form->load_from_db($form_id);

        $form_options= $form->get_form_options();

        $act_link_message= $form_options->act_link_message;

        if(empty($act_link_message))

            $act_link_message= RM_UI_Strings::get('DEFAULT_ACT_LINK_MSG_VALUE');

        update_user_meta( $user_id, 'rm_activation_hash', $hash );

        update_user_meta( $user_id, 'rm_activation_time', date('Y-m-d H:i:s'));

        $user_info = get_userdata($user_id);   

        

        $subject= $form_options->act_link_sub;

        if(empty($subject))

            $subject = __("Email Verification",'registration-gold'); 

        $message= str_replace(array('{{EMAIL_VERFICATION_LINK}}','{{EMAIL_VERIFICATION_CODE}}'), array($url,$hash), $act_link_message);

        $rm_email= new RM_Email();

        $message= do_shortcode(wpautop($message));

		

		//Override Work Start | $subject & $message

		$activ_url = add_query_arg( array(

			'rm_user' => $user_id,

			'rm_hash' => $hash

		), $sub_page_url );

		$codex_merged = codex_merged_email_data(array('url'=>$activ_url, 'hash'=>$hash, 'form_id'=>$form_id, 

													  'user_id'=>$user_id)

													  );

		if(is_array($codex_merged) && !empty($codex_merged)){

			$rm_email->set_content_type('html');
			extract($codex_merged);
			
			//Attach PDF
			if(isset($pdf_file_url) && '' != $pdf_file_url ){
				$rm_email->attach( array($pdf_file_path) );	
			}
			
		}

		//Override Work Ends

		

        $rm_email->message($message);

        $rm_email->subject($subject);

        $rm_email->to($user_info->user_email);

        $rm_email->from($gopts->get_value_of('senders_email_formatted'));

        $rm_email->send();

    }

    

    public static function send_2fa_otp($options){

        $gopt = new RM_Options();

        $rm_email= new RM_Email();

        $rm_email->message($options['message']);

        $rm_email->subject(__('OTP','registrationmagic-gold'));

        $user= get_user_by('login', $options['username']);

        $rm_email->to($user->user_email);

        $rm_email->from($gopt->get_value_of('senders_email_formatted'));

        $rm_email->send();

    }

    

    public static function notify_failed_login_to_user($user){

        $login_service= new RM_Login_Service;

        $template_options= $login_service->get_template_options();

        $message= wpautop(str_replace(array('{{username}}','{{sitename}}','{{Login_IP}}','{{login_time}}'),array($user->user_login,get_bloginfo('title'),$_SERVER["REMOTE_ADDR"], RM_Utilities::get_current_time(current_time('timestamp'))),$template_options['failed_login_err']));

        $rm_email= new RM_Email();

        $rm_email->message($message);

        $rm_email->subject(__("Failed login Attempt",'registration-gold'));

        $rm_email->to($user->user_email);

        $gopt = new RM_Options();

        $rm_email->from($gopt->get_value_of('admin_email'));

        $rm_email->send();

    }

    

    public static function notify_failed_login_to_admin($user){

        $login_service= new RM_Login_Service;

        $template_options= $login_service->get_template_options();

        $message= wpautop(str_replace(array('{{username}}','{{sitename}}','{{Login_IP}}','{{login_time}}'),array($user->user_login,get_bloginfo('title'),$_SERVER["REMOTE_ADDR"], RM_Utilities::get_current_time(current_time('timestamp'))),$template_options['failed_login_err_admin']));

        $rm_email= new RM_Email();

        $rm_email->message($message);

        $rm_email->subject(__("Failed login Attempt",'registration-gold'));

        $gopt = new RM_Options();

        $rm_email->to($gopt->get_value_of('admin_email'));

        $rm_email->send();

    }

    

    public static function notify_admin_on_ip_ban($args){

       $login_service= new RM_Login_Service;

        $template_options= $login_service->get_template_options();

        $message= wpautop(str_replace(array('{{login_IP}}','{{ban_period}}','{{ban_trigger}}'),array($_SERVER["REMOTE_ADDR"],$args['ban_period'],$args['ban_trigger']),$template_options['ban_message_admin']));

        $rm_email= new RM_Email();

        $rm_email->message($message);

        $rm_email->subject(__("IP Blocked",'registration-gold'));

        $gopt = new RM_Options();

        $rm_email->to($gopt->get_value_of('admin_email'));

        $rm_email->send();

    }

    

    public static function notify_lost_password_token($user){

        $login_service= new RM_Login_Service;

        $gopt = new RM_Options();

        $template_options= $login_service->get_template_options();

        $recovery_options= $login_service->get_recovery_options();

        $username= $user->user_login;

        $page_id= $recovery_options['recovery_page'];

        if(empty($page_id)){

            return false;

        }

        

        $recovery_link= get_permalink($page_id);

        $token= wp_generate_password(8,false );

        update_user_meta($user->ID,'rm_pass_token',$token);

        $hours= $recovery_options['rec_link_expiry'];

        if(!empty($hours)){

            update_user_meta($user->ID,'rm_pass_expiry_token',time() + ($hours*3600));

        }

        else{

            update_user_meta($user->ID,'rm_pass_expiry_token',0);

        }

        $recovery_link= add_query_arg('reset_token',$token,$recovery_link);

        $message= wpautop(str_replace(array('{{site_name}}','{{username}}','{{password_recovery_link}}','{{security_token}}'),array(get_bloginfo('name'),$username,$recovery_link,$token),$template_options['pass_reset']));

        //echo $message;

        $rm_email= new RM_Email();

        //echo $message;

        $rm_email->message($message);

        $rm_email->subject(__("Reset Password",'registration-gold'));

        $gopt = new RM_Options();

        $rm_email->to($user->user_email);

        $rm_email->send();

        return true;

    }

}





