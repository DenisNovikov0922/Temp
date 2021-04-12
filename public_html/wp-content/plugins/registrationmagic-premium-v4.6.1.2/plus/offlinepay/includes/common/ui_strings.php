<?php

/**
 * This class works as a repository of all the string resources used in product UI
 * for easy translation and management. 
 *
 * @author CMSHelplive
 */

class RM_OLP_UI_Strings
{
    public static function get($identifier)
    {
        switch($identifier)
        {
            case 'OLP_INFO_EMAIL_DEF_SUB':
                return __('Payment details for your submission on - {{SITE_NAME}}','registrationmagic-gold');
            
            case 'OLP_INFO_EMAIL_DEF_BODY':
                return __('<div>Hi,<br/><p>Please pay {{TOTAL_AMOUNT}} to complete your registration. If you require further details regarding offline payment please contact administrator.</p></div>','registrationmagic-gold');
            
            case 'HELP_OLP_SEND_EMAIL_INFO':
                return sprintf(__("Send a message to the user with offline payment details. You can also include offline payment steps in your autoresponder message. <a target='_blank' class='rm-more' href='%s'>More</a>",'registrationmagic-gold'),'https://registrationmagic.com/knowledgebase/payments/#htsendemail');
            
            case 'HELP_OLP_EMAIL_INFO':
                return sprintf(__("Content of the email message with payment information like bank account etc. <a target='_blank' class='rm-more' href='%s'>More</a>",'registrationmagic-gold'),'https://registrationmagic.com/knowledgebase/payments/#htmessage');

            case 'LABEL_OLP_SEND_EMAIL':
                return __('Send Email','registrationmagic-gold');
            
            case 'LABEL_OLP_SEND_EMAIL_INFO':
                return __('Message','registrationmagic-gold');               
            
            case 'LABEL_OLP_PAY_OFFLINE':
                return __('Pay Offline','registrationmagic-gold');
            
            default:
                return __("NO STRING FOUND (rmolp)", 'registrationmagic-gold');
        }
    }
}