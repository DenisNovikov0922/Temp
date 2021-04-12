<?php

/**
 * This class works as a repository of all the string resources used in product UI
 * for easy translation and management. 
 *
 * @author CMSHelplive
 */

class RM_WEPAY_UI_Strings
{
    public static function get($identifier)
    {
        switch($identifier)
        {
            case 'LABEL_TEST_MODE':
                return __('Test Mode','registrationmagic-gold');
            
            case 'LABEL_WEPAY_CLIENT_ID':
                return __('WePay Client ID','registrationmagic-gold');
            
            case 'LABEL_WEPAY_CLIENT_SECRET':
                return __('WePay Client Secret','registrationmagic-gold');
                
            case 'LABEL_WEPAY_ACCESS_TOKEN':
                return __('WePay Access Token','registrationmagic-gold');
                
            case 'LABEL_WEPAY_ACCOUNT_ID':
                return __('WePay Account ID','registrationmagic-gold');
                
            
                
            case 'HELP_OPTIONS_WEPAY_TESTMODE':
                return sprintf(__("This will put WePay payments on test mode. Useful for testing and troubleshooting payment system. <a target='_blank' class='rm-more' href='%s'>More</a>", 'registrationmagic-gold'),'https://registrationmagic.com/knowledgebase/payments/#htenabletestmode');
            
            case 'HELP_OPTIONS_WEPAY_CLIENT_ID':
                return sprintf(__("Enter your WePay Client ID here. <a target='_blank' class='rm-more' href='%s'>More</a>", 'registrationmagic-gold'),'https://registrationmagic.com/knowledgebase/payments/#htwepayclientid');
                
            case 'HELP_OPTIONS_WEPAY_CLIENT_SECRET':
                return sprintf(__("Enter your WePay Client secret here. <a target='_blank' class='rm-more' href='%s'>More</a>", 'registrationmagic-gold'),'https://registrationmagic.com/knowledgebase/payments/#htwepayclientsecret');
                
            case 'HELP_OPTIONS_WEPAY_ACCESS_TOKEN':
                return sprintf(__("Enter your WePay Access Token here. <a target='_blank' class='rm-more' href='%s'>More</a>", 'registrationmagic-gold'),'https://registrationmagic.com/knowledgebase/payments/#htwepayaccesstoken');
                
            case 'HELP_OPTIONS_WEPAY_ACCOUNT_ID':
                return sprintf(__("Enter your WePay Account ID here. <a target='_blank' class='rm-more' href='%s'>More</a>", 'registrationmagic-gold'),'https://registrationmagic.com/knowledgebase/payments/#htwepayaccountid');
            
            case 'MSG_PAYMENT_SUCCESS':
                return __("Payment Successfull", 'registrationmagic-gold');

            case 'MSG_PAYMENT_FAILED':
                return __("Payment Failed!", 'registrationmagic-gold');

            case 'MSG_PAYMENT_PENDING':
                return __("Payment Pending.", 'registrationmagic-gold');

            case 'MSG_PAYMENT_CANCEL':
                return __("Transaction Cancelled", 'registrationmagic-gold');
                
            default:
                return __("NO STRING FOUND (wepay)", 'registrationmagic-gold');
        }
    }
}