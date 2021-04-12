<?php

class RM_OLP_Email_Service extends RM_Email_Service
{
    public static function notify_user_offline_payment_info($params)
    {
        $rm_email= new RM_Email();
        $rm_email->message($params->body);
        $rm_email->subject($params->sub);
        $rm_email->to($params->user_email);
        $rm_email->from($params->from);
        
        if($rm_email->send())
        {   
            self::save_sent_emails($params,$rm_email,RM_OLP_EMAIL_INFO_TO_USER);
        }
    }
}

