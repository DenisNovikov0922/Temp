<?php

class RM_Wepay_Options extends RM_Options
{
    private $enc_data_keys= array('wepay_client_id','wepay_client_secret','wepay_access_token','wepay_account_id');
    public function __construct() {
        parent::__construct();
        
        $this->options_name_and_methods['wepay_client_id']= null;
        $this->options_name_and_methods ['wepay_client_secret']= null;
        $this->options_name_and_methods['wepay_access_token']= null;
        $this->options_name_and_methods['wepay_account_id']= null;
        $this->options_name_and_methods ['wepay_test_mode']= 'sanitize_checkbox';                
    }
    
    public function get_value_of($option)
    {
        
        $value= parent::get_value_of($option);
        if(!empty($value) && in_array($option, $this->enc_data_keys))
        {       
                $value= RM_Utilities::dec_str ($value);
        }
        
        return $value;
    }
    
}
